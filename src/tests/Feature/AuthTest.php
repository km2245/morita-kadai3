<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_admin_login_screen_can_be_rendered()
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
    }

    public function test_attendance_screen_requires_login()
    {
        $response = $this->get('/attendance');

        $response->assertStatus(302);
    }
    public function test_guest_cannot_view_admin_page()
    {
        $response = $this
            ->get('/admin/staff/list');

        $response->assertRedirect('/login');
    }
}
