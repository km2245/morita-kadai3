<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminLoginController;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

//
// 🔓 ログイン不要
//

// 管理者ログイン画面
Route::get('/admin/login', function () {
    return view('admin.login');
})->name('admin.login');

// 管理者ログイン処理
Route::post('/admin/login', [AdminLoginController::class, 'login'])
    ->name('admin.login.post');

//
// 🔒 一般ユーザー
//
Route::middleware('auth')->group(function () {

    // 出勤登録画面
    Route::get('/attendance', [AttendanceController::class, 'create'])
        ->name('attendance.create');

    // 出勤処理
    Route::post('/attendance', [AttendanceController::class, 'store'])
        ->name('attendance.store');

    // 退勤
    Route::post('/attendance/leave', [AttendanceController::class, 'leave'])
        ->name('attendance.leave');

    // 休憩開始
    Route::post('/attendance/break/start', [AttendanceController::class, 'breakStart'])
        ->name('attendance.break.start');

    // 休憩終了
    Route::post('/attendance/break/end', [AttendanceController::class, 'breakEnd'])
        ->name('attendance.break.end');

    // 勤怠一覧
    Route::get('/attendance/list', [AttendanceController::class, 'index'])
        ->name('attendance.index');

    // 勤怠詳細
    Route::get('/attendance/detail/{id}', [AttendanceController::class, 'show'])
        ->name('attendance.show');

    // 修正申請
    Route::post('/attendance/detail/{id}/request', [AttendanceController::class, 'requestCorrection'])
        ->name('attendance.request');

    // 申請一覧（一般）
    Route::get('/stamp_correction_request/list', [AttendanceController::class, 'requestList'])
        ->name('attendance.request.list');
});

//
// 🔒 管理者
//
Route::middleware(['auth', 'admin'])->group(function () {
    // 管理者 勤怠一覧
    Route::get('/admin/attendance/list', [AttendanceController::class, 'adminIndex'])
        ->name('admin.attendance.list');

    // スタッフ一覧
    Route::get('/admin/staff/list', [AttendanceController::class, 'staffList'])
        ->name('admin.staff.list');

    // スタッフ別勤怠
    Route::get('/admin/attendance/staff/{id}', [AttendanceController::class, 'staffAttendance'])
        ->name('admin.staff.attendance');

    // 管理者 申請一覧
    Route::get('/admin/stamp_correction_request/list', [AttendanceController::class, 'adminRequestList'])
        ->name('admin.request.list');

    // 承認画面
    Route::get('/stamp_correction_request/approve/{id}', [AttendanceController::class, 'approvePage'])
        ->name('admin.request.approve');

    // 承認処理
    Route::post('/stamp_correction_request/approve/{id}', [AttendanceController::class, 'approveRequest'])
        ->name('admin.request.approve.post');

    Route::get(
        '/admin/attendance/staff/{id}/csv',
        [AttendanceController::class, 'exportCsv']
    )->name('admin.attendance.csv');
});

//
// ログアウト
//
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');
