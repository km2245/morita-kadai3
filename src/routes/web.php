<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminLoginController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::middleware('auth')->group(function () {

    Route::get('/attendance', [AttendanceController::class, 'create'])
        ->name('attendance.create');

    Route::post('/attendance', [AttendanceController::class, 'store'])
        ->name('attendance.store');

    Route::post('/attendance/leave', [AttendanceController::class, 'leave'])
        ->name('attendance.leave');

    Route::post('/attendance/break/start', [AttendanceController::class, 'breakStart'])
        ->name('attendance.break.start');

    Route::post('/attendance/break/end', [AttendanceController::class, 'breakEnd'])
        ->name('attendance.break.end');

    Route::get('/attendance/list', [AttendanceController::class, 'index'])
        ->name('attendance.index');

    Route::get('/attendance/detail/{id}', [AttendanceController::class, 'show'])
        ->name('attendance.show');

    Route::post(
        '/attendance/detail/{id}/request',
        [AttendanceController::class, 'requestCorrection']
    )->name('attendance.request');
    Route::get(
        '/stamp_correction_request/list',
        [AttendanceController::class, 'requestList']
    )->name('attendance.request.list');
    Route::get('/admin/login', function () {
        return view('admin.login');
    })->name('admin.login');
    Route::post(
        '/admin/login',
        [AdminLoginController::class, 'login']
    )->name('admin.login.post');
    Route::get(
        '/admin/attendance/list',
        [AttendanceController::class, 'adminIndex']
    )->name('admin.attendance.list');
    Route::get(
        '/admin/staff/list',
        [AttendanceController::class, 'staffList']
    )->name('admin.staff.list');
    Route::get(
        '/admin/attendance/staff/{id}',
        [AttendanceController::class, 'staffAttendance']
    )->name('admin.staff.attendance');
    Route::get(
        '/admin/stamp_correction_request/list',
        [AttendanceController::class, 'adminRequestList']
    )->name('admin.request.list');
    Route::get(
        '/stamp_correction_request/approve/{id}',
        [AttendanceController::class, 'approvePage']
    )->name('admin.request.approve');
    Route::post(
        '/stamp_correction_request/approve/{id}',
        [AttendanceController::class, 'approveRequest']
    )->name('admin.request.approve.post');
});