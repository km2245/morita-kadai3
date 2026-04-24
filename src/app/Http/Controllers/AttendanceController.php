<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\StampCorrectionRequest;
use App\Http\Requests\CorrectionRequest;
use App\Models\User;

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
        // 表示する月を取得（なければ今月）
        $month = $request->input('month', now()->format('Y-m'));

        // ログイン中ユーザーの
        // 指定された月の勤怠一覧を取得
        $attendances = Attendance::where('user_id', auth()->id())
            ->where('date', 'like', $month . '%')
            ->orderBy('date', 'desc')
            ->get();

        // ビューに渡す
        return view('attendance.list', compact('attendances', 'month'));
    }

    public function show($id)
    {
        // 選択された勤怠データを取得
        // breaks（休憩）も一緒に取得する
        $attendance = Attendance::with('breaks')
            ->findOrFail($id);

        // 詳細画面に渡す
        return view('attendance.detail', compact('attendance'));
    }
    public function requestCorrection(CorrectionRequest $request, $id)
    {
        // 対象の勤怠データを取得
        $attendance = Attendance::findOrFail($id);

        // 修正申請テーブルに保存
        StampCorrectionRequest::create([

            // ログイン中ユーザー
            'user_id' => auth()->id(),

            // 対象の勤怠ID
            'attendance_id' => $attendance->id,

            // 今回は修正前 = 修正後で仮保存
            // （あとでフォーム入力にする）
            'before_start_time' => $attendance->start_time,
            'after_start_time' => $attendance->start_time,

            'before_end_time' => $attendance->end_time,
            'after_end_time' => $attendance->end_time,

            // 備考（修正理由）
            'reason' => $request->reason,

            // 最初は承認待ち
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
    public function adminIndex()
    {
        // role が user の一般ユーザーだけ取得
        $users = User::where('role', 'user')
            ->with('attendances')
            ->get();

        // 管理者用一覧画面に渡す
        return view('admin.attendance-list', compact('users'));
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
    public function staffAttendance($id)
    {
        // 対象スタッフを取得
        $user = User::findOrFail($id);

        // そのスタッフの勤怠一覧を取得
        $attendances = Attendance::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->get();

        // ビューに渡す
        return view('admin.staff-attendance', compact('user', 'attendances'));
    }
    public function approvePage($id)
    {
        // 修正申請データを取得
        $request = StampCorrectionRequest::with(['user', 'attendance'])
            ->findOrFail($id);

        // 承認画面へ渡す
        return view('admin.approve', compact('request'));
    }
    public function approveRequest($id)
    {
        // 対象の修正申請を取得
        $request = StampCorrectionRequest::findOrFail($id);

        // status を approved に更新
        $request->update([
            'status' => 'approved',
        ]);

        // 承認後、管理者申請一覧へ戻る
        return redirect()->route('admin.request.list');
    }
}
