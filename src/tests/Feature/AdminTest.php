<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTest extends TestCase
{
    use RefreshDatabase;
    public function test_admin_can_view_staff_list()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this
            ->actingAs($admin)
            ->get('/admin/staff/list');

        $response->assertStatus(200);
    }
    public function test_admin_can_view_request_list()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this
            ->actingAs($admin)
            ->get('/admin/stamp_correction_request/list');

        $response->assertStatus(200);
    }
    public function test_admin_can_approve_request()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => now()->format('Y-m-d'),
            'start_time' => now()->format('H:i:s'),
            'status' => 'working',
        ]);

        $request = \App\Models\StampCorrectionRequest::create([
            'user_id' => $user->id,
            'attendance_id' => $attendance->id,

            'before_start_time' => '09:00:00',
            'before_end_time' => '18:00:00',

            'after_start_time' => '10:00:00',
            'after_end_time' => '19:00:00',

            'reason' => '修正',
            'status' => 'pending',
        ]);

        $response = $this
            ->actingAs($admin)
            ->post("/stamp_correction_request/approve/{$request->id}");

        $response->assertStatus(302);

        $this->assertDatabaseHas('stamp_correction_requests', [
            'id' => $request->id,
            'status' => 'approved',
        ]);
    }
    public function test_admin_can_download_csv()
    {
        $this->withoutExceptionHandling();

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $user = User::factory()->create();

        $response = $this
            ->actingAs($admin)
            ->get("/admin/attendance/staff/{$user->id}/csv?month=2026-05");

        $response->assertStatus(200);
    }
    public function test_admin_can_view_attendance_by_date()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this
            ->actingAs($admin)
            ->get('/admin/attendance/list?date=2026-05-01');

        $response->assertStatus(200);
    }
    public function test_user_cannot_view_admin_page()
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/admin/staff/list');

        $response->assertStatus(403);
    }
    public function test_user_cannot_view_approve_page()
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/stamp_correction_request/approve/1');

        $response->assertStatus(403);
    }
}
