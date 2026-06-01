<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\StampCorrectionRequest;
use App\Http\Requests\CorrectionRequest;
use App\Models\User;
use Carbon\Carbon;


class AttendanceController extends Controller
{
    public function create()
    {
        // ログイン中ユーザーの今日の勤怠データを取得
        $attendance = Attendance::where('user_id', auth()->id())
            ->whereDate('date', today())
            ->latest()
            ->first();

        // 現在日時を取得
        $now = now();

        // attendance と now をビューに渡す
        return view('attendance.index', compact('attendance', 'now'));
    }


    public function store()
    {
        // ログイン中ユーザーの今日の勤怠を確認
        $alreadyAttendance = Attendance::where('user_id', auth()->id())
            ->whereDate('date', today())
            ->first();

        // すでに今日の勤怠がある場合は何もしない
        if ($alreadyAttendance) {
            return back();
        }

        // まだ出勤していない場合だけ新規作成
        Attendance::create([

            // ログイン中のユーザーID
            'user_id' => auth()->id(),

            // 今日の日付
            'date' => now()->toDateString(),

            // 現在時刻を出勤時間として保存
            'start_time' => now()->format('H:i:s'),

            // 勤務中ステータス
            'status' => 'working',
        ]);

        // 元の画面に戻る
        return back();
    }
    public function leave()
    {
        // ログイン中のユーザーの「今日」の勤怠データを取得
        // latest()を使って最新のレコードを取る
        $attendance = Attendance::where('user_id', auth()->id())
            ->whereDate('date', today())
            ->latest()
            ->first();
        // 勤怠データが存在する場合だけ更新する
        if ($attendance) {
            // 退勤時間を現在時刻で保存して
            // statusをfinished(退勤済み)に変更する
            $attendance->update([
                'end_time' => now()->format('H:i:s'),
                'status' => 'finished',
            ]);
        }
        // 処理後、下の画面に戻る
        return back();
    }

    public function breakStart()
    {
        // ログイン中ユーザーの今日の勤怠データを取得
        $attendance = Attendance::where('user_id', auth()->id())
            ->whereDate('date', today())
            ->latest()
            ->first();

        // 勤怠データがある場合のみ休憩開始を記録する
        if ($attendance) {

            // breaksテーブルに休憩開始時間を保存
            $attendance->breaks()->create([

                // 現在時刻を休憩開始時間として保存
                'start_time' => now()->format('H:i:s'),
            ]);

            // status を break（休憩中）に変更する
            $attendance->update([
                'status' => 'break',
            ]);
        }

        // 処理後、元の画面に戻る
        return back();
    }

    public function breakEnd()
    {
        // ログイン中ユーザーの今日の勤怠データを取得
        $attendance = Attendance::where('user_id', auth()->id())
            ->whereDate('date', today())
            ->latest()
            ->first();

        // 勤怠データがある場合のみ処理する
        if ($attendance) {

            // 今日の最新の休憩データを取得
            // end_time がまだ入っていないもの = まだ休憩中
            $break = $attendance->breaks()
                ->whereNull('end_time')
                ->latest()
                ->first();

            // 対象の休憩データがある場合
            if ($break) {

                // 現在時刻を休憩終了時間として保存
                $break->update([
                    'end_time' => now()->format('H:i:s'),
                ]);

                // status を working（勤務中）に戻す
                $attendance->update([
                    'status' => 'working',
                ]);
            }
        }

        // 処理後、元の画面に戻る
        return back();
    }

    public function index(Request $request)
    {
        // 表示する月
        $month = $request->input('month', now()->format('Y-m'));

        // 勤怠取得
        $attendances = Attendance::with('breaks')
            ->where('user_id', auth()->id())
            ->where('date', 'like', $month . '%')
            ->orderBy('date', 'desc')
            ->get();

        // 休憩時間と勤務時間を計算
        foreach ($attendances as $attendance) {

            // =========================
            // 休憩合計
            // =========================
            $totalBreakMinutes = 0;

            foreach ($attendance->breaks as $break) {

                if ($break->start_time && $break->end_time) {

                    $start = strtotime($break->start_time);
                    $end = strtotime($break->end_time);

                    $totalBreakMinutes += ($end - $start) / 60;
                }
            }

            // 「1:00」形式にする
            $attendance->break_total =
                floor($totalBreakMinutes / 60)
                . ':'
                . str_pad($totalBreakMinutes % 60, 2, '0', STR_PAD_LEFT);

            // =========================
            // 勤務合計
            // =========================
            if ($attendance->start_time && $attendance->end_time) {

                $workStart = strtotime($attendance->start_time);
                $workEnd = strtotime($attendance->end_time);

                // 勤務時間（分）
                $workMinutes =
                    (($workEnd - $workStart) / 60)
                    - $totalBreakMinutes;

                // 「8:00」形式
                $attendance->work_total =
                    floor($workMinutes / 60)
                    . ':'
                    . str_pad($workMinutes % 60, 2, '0', STR_PAD_LEFT);
            } else {

                $attendance->work_total = '';
            }
        }

        return view(
            'attendance.list',
            compact('attendances', 'month')
        );
    }

    public function show($id)
    {
        $attendance = Attendance::with([
            'breaks',
            'stampCorrectionRequests'
        ])->findOrFail($id);

        // 承認待ちがあるか
        $isPending =
            $attendance->stampCorrectionRequests()
            ->where('status', 'pending')
            ->exists();

        return view(
            'attendance.detail',
            compact('attendance', 'isPending')
        );
    }

    public function requestCorrection(CorrectionRequest $request, $id)
    {
        // 対象の勤怠データを取得
        $attendance = Attendance::findOrFail($id);


        // 修正申請テーブルに保存
        StampCorrectionRequest::create([
            'user_id' => auth()->id(),

            'attendance_id' => $attendance->id,

            'before_start_time' => $attendance->start_time,
            'before_end_time' => $attendance->end_time,

            'after_start_time' => $request->start_time,
            'after_end_time' => $request->end_time,

            'reason' => $request->reason,

            'status' => 'pending',
        ]);

        // 保存後、一覧に戻る
        return redirect()->route('attendance.index');
    }
    public function requestList(Request $request)
    {
        // tab がなければ pending（承認待ち）
        $tab = $request->input('tab', 'pending');

        // ログイン中ユーザーの申請一覧を取得
        // status ごとに絞り込み
        $requests = StampCorrectionRequest::where('user_id', auth()->id())
            ->where('status', $tab)
            ->orderBy('created_at', 'desc')
            ->get();

        // ビューに渡す
        return view('attendance.request-list', compact('requests', 'tab'));
    }
    public function adminIndex(Request $request)
    {
        // 日付取得
        $date = $request->input('date', now()->format('Y-m-d'));

        // 前日
        $prevDate = \Carbon\Carbon::parse($date)
            ->subDay()
            ->format('Y-m-d');

        // 翌日
        $nextDate = \Carbon\Carbon::parse($date)
            ->addDay()
            ->format('Y-m-d');

        // その日の勤怠一覧取得
        $attendances = Attendance::with('user', 'breaks')
            ->where('date', $date)
            ->get();

        // 管理者用一覧画面に渡す
        return view(
            'admin.attendance-list',
            compact(
                'attendances',
                'date',
                'prevDate',
                'nextDate'
            )
        );
    }
    public function staffList()
    {
        // 一般ユーザー（staff）だけ取得
        $users = User::where('role', 'user')
            ->orderBy('name', 'asc')
            ->get();

        // スタッフ一覧画面へ渡す
        return view('admin.staff-list', compact('users'));
    }

    public function staffAttendance(Request $request, $id)
    {
        // スタッフ取得
        $user = User::findOrFail($id);

        // 月取得
        $month = $request->input('month', now()->format('Y-m'));

        // 勤怠取得
        $attendances = Attendance::with('breaks')
            ->where('user_id', $user->id)
            ->where('date', 'like', $month . '%')
            ->orderBy('date', 'asc')
            ->get();

        // 前月
        $prevMonth = Carbon::parse($month . '-01')
            ->subMonth()
            ->format('Y-m');

        // 翌月
        $nextMonth = Carbon::parse($month . '-01')
            ->addMonth()
            ->format('Y-m');

        return view(
            'admin.staff-attendance',
            compact(
                'user',
                'attendances',
                'month',
                'prevMonth',
                'nextMonth'
            )
        );
    }

    public function exportCsv(Request $request, $id)
    {
        // スタッフ取得
        $user = User::findOrFail($id);

        // 月取得
        $month = $request->input('month', now()->format('Y-m'));

        // 勤怠取得
        $attendances = Attendance::with('breaks')
            ->where('user_id', $user->id)
            ->where('date', 'like', $month . '%')
            ->orderBy('date', 'asc')
            ->get();

        // CSVレスポンス
        $response = new StreamedResponse(function () use ($attendances) {

            $handle = fopen('php://output', 'w');

            // ヘッダー
            fputcsv($handle, [
                '日付',
                '出勤',
                '退勤',
                '休憩',
                '合計'
            ]);

            foreach ($attendances as $attendance) {

                // 休憩合計
                $breakMinutes = 0;

                foreach ($attendance->breaks as $break) {

                    if ($break->start_time && $break->end_time) {

                        $start = Carbon::parse($break->start_time);
                        $end = Carbon::parse($break->end_time);

                        $breakMinutes += $end->diffInMinutes($start);
                    }
                }

                // 勤務時間
                $workTime = '';

                if ($attendance->start_time && $attendance->end_time) {

                    $start = Carbon::parse($attendance->start_time);
                    $end = Carbon::parse($attendance->end_time);

                    $totalMinutes =
                        $end->diffInMinutes($start) - $breakMinutes;

                    $hours = floor($totalMinutes / 60);
                    $minutes = $totalMinutes % 60;

                    $workTime =
                        sprintf('%d:%02d', $hours, $minutes);
                }

                // CSV行
                fputcsv($handle, [

                    $attendance->date,

                    $attendance->start_time
                        ? Carbon::parse($attendance->start_time)->format('H:i')
                        : '',

                    $attendance->end_time
                        ? Carbon::parse($attendance->end_time)->format('H:i')
                        : '',

                    $breakMinutes > 0
                        ? floor($breakMinutes / 60)
                        . ':'
                        . sprintf('%02d', $breakMinutes % 60)
                        : '',

                    $workTime
                ]);
            }

            fclose($handle);
        });

        // ダウンロード名
        $fileName =
            $user->name . '_' . $month . '_attendance.csv';

        $response->headers->set(
            'Content-Type',
            'text/csv'
        );

        $response->headers->set(
            'Content-Disposition',
            'attachment; filename="' . $fileName . '"'
        );

        return $response;
    }
    public function approveRequest($id)
    {
        $request = StampCorrectionRequest::findOrFail($id);

        $request->status = 'approved';

        $request->save();

        return redirect()->back();
    }
    public function adminRequestList(Request $request)
    {
        // tab取得
        $tab = $request->input('tab', 'pending');

        // 修正申請一覧取得
        $requests = StampCorrectionRequest::with(['user', 'attendance'])
            ->where('status', $tab)
            ->orderBy('created_at', 'desc')
            ->get();

        // ビューへ
        return view(
            'admin.request-list',
            compact('requests', 'tab')
        );
    }

    public function approvePage($id)
    {
        // 修正申請取得
        $request = StampCorrectionRequest::with([
            'user',
            'attendance',
            'attendance.breaks'
        ])->findOrFail($id);

        // 勤怠取得
        $attendance = $request->attendance;

        // 画面表示
        return view(
            'admin.approve',
            compact('request', 'attendance')
        );
    }
}
