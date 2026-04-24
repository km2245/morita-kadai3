<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // ユーザー情報を作成
        $this->call(UserSeeder::class);

        // 勤怠・休憩データを作成
        $this->call(AttendanceSeeder::class);
    }
}
