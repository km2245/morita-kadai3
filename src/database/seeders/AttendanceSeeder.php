<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Breaktime;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        // 一般ユーザー（id=2）の勤怠データを作成
        $attendance = Attendance::create([
            'user_id' => User::first()->id,
            'date' => now()->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '18:00:00',
            'status' => 'finished',
        ]);

        // 休憩データを作成
        Breaktime::create([
            'attendance_id' => $attendance->id,
            'start_time' => '12:00:00',
            'end_time' => '13:00:00',
        ]);
    }
}
