<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AdminDashboardSearchTest extends TestCase
{
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
    }

    public function test_admin_dashboard_renders_successfully(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Dashboard');
    }

    public function test_admin_global_search_returns_json_results(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.global-search', ['q' => 'Umrah']));

        $response->assertStatus(200);
        $response->assertJsonIsArray();
    }
}
