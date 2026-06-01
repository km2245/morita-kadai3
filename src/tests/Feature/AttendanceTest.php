<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;



    public function test_user_can_view_attendance_list()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/attendance/list');

        $response->assertStatus(200);
    }

    public function test_user_can_start_work()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/attendance');

        $response->assertStatus(302);

        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
        ]);
    }
    public function test_admin_can_login()
    {
        $admin = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@test.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin/attendance/list');
    }
    public function test_user_can_start_break()
    {
        $user = User::factory()->create();

        $attendance = \App\Models\Attendance::create([
            'user_id' => $user->id,
            'date' => now()->format('Y-m-d'),
            'start_time' => now()->format('H:i:s'),
            'status' => 'working',
        ]);

        $response = $this
            ->actingAs($user)
            ->post('/attendance/break/start');

        $response->assertStatus(302);

        $this->assertDatabaseHas('breaktimes', [
            'attendance_id' => $attendance->id,
        ]);
    }
    public function test_user_can_view_attendance_detail()
    {
        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => now()->format('Y-m-d'),
            'start_time' => now()->format('H:i:s'),
            'status' => 'working',
        ]);

        $response = $this
            ->actingAs($user)
            ->get("/attendance/detail/{$attendance->id}");

        $response->assertStatus(200);
    }
    public function test_user_can_request_attendance_correction()
    {
        $user = User::factory()->create();
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => now()->format('Y-m-d'),

            'start_time' => now()->format('H:i:s'),
            'end_time' => now()->format('H:i:s'),

            'status' => 'working',
        ]);

        $response = $this
            ->actingAs($user)
            ->post("/attendance/detail/{$attendance->id}/request", [
                'reason' => '修正したいです',
                'start_time' => '09:00',
                'end_time' => '18:00',
            ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('stamp_correction_requests', [
            'user_id' => $user->id,
            'reason' => '修正したいです',
        ]);
    }

    public function test_user_can_leave_work()
    {
        $user = User::factory()->create();

        Attendance::create([
            'user_id' => $user->id,
            'date' => now()->format('Y-m-d'),
            'start_time' => now()->format('H:i:s'),
            'status' => 'working',
        ]);

        $response = $this
            ->actingAs($user)
            ->post('/attendance/leave');

        $response->assertStatus(302);
    }
    public function test_user_can_end_break()
    {
        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => now()->format('Y-m-d'),
            'start_time' => now()->format('H:i:s'),
            'status' => 'break',
        ]);

        \App\Models\Breaktime::create([
            'attendance_id' => $attendance->id,
            'start_time' => now()->format('H:i:s'),
        ]);

        $response = $this
            ->actingAs($user)
            ->post('/attendance/break/end');

        $response->assertStatus(302);
    }
    public function test_reason_is_required()
    {
        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => now()->format('Y-m-d'),
            'start_time' => now()->format('H:i:s'),
            'end_time' => now()->format('H:i:s'),
            'status' => 'working',
        ]);

        $response = $this
            ->actingAs($user)
            ->post("/attendance/detail/{$attendance->id}/request", [
                'reason' => '',
            ]);

        $response->assertSessionHasErrors('reason');
    }


    public function test_start_time_must_be_before_end_time()
    {
        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => now()->format('Y-m-d'),
            'start_time' => now()->format('H:i:s'),
            'end_time' => now()->format('H:i:s'),
            'status' => 'working',
        ]);

        $response = $this
            ->actingAs($user)
            ->post("/attendance/detail/{$attendance->id}/request", [
                'reason' => '修正',
                'start_time' => '18:00',
                'end_time' => '09:00',
            ]);

        $response->assertSessionHasErrors();
    }
    public function test_end_time_is_required()
    {
        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => now()->format('Y-m-d'),
            'start_time' => now()->format('H:i:s'),
            'end_time' => now()->format('H:i:s'),
            'status' => 'working',
        ]);

        $response = $this
            ->actingAs($user)
            ->post("/attendance/detail/{$attendance->id}/request", [
                'reason' => '修正',
                'start_time' => '09:00',
                'end_time' => '',
            ]);

        $response->assertSessionHasErrors('end_time');
    }
}
