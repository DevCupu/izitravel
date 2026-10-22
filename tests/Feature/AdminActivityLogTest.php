<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\User;
use App\Models\WhatsAppCs;
use Tests\TestCase;

class AdminActivityLogTest extends TestCase
{
    public function test_admin_changes_are_recorded_and_only_admin_can_read_the_log(): void
    {
        $admin = User::factory()->create(['name' => 'Admin Audit', 'role' => 'admin']);
        $cs = User::factory()->create(['role' => 'cs']);
        WhatsAppCs::create(['name' => 'CS Audit', 'phone' => '6281212345678', 'weight' => 1, 'is_active' => true, 'user_id' => $cs->id]);

        $this->get(route('admin.activity-logs.index'))->assertRedirect(route('login'));
        $this->actingAs($cs)->get(route('admin.activity-logs.index'))->assertForbidden();

        $this->actingAs($admin)->post(route('admin.categories.store'), ['name' => 'Kategori Audit'])
            ->assertRedirect();
        $category = Category::where('name', 'Kategori Audit')->firstOrFail();
        $this->actingAs($admin)->put(route('admin.categories.update', $category), ['name' => 'Kategori Baru'])
            ->assertRedirect();
        $this->actingAs($admin)->delete(route('admin.categories.destroy', $category))
            ->assertRedirect();

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
            'actor_name' => 'Admin Audit',
            'action' => 'create',
            'subject' => 'Kategori',
            'route_name' => 'admin.categories.store',
        ]);
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'update', 'subject_id' => (string) $category->id,
            'route_name' => 'admin.categories.update',
        ]);
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'delete', 'subject_id' => (string) $category->id,
            'route_name' => 'admin.categories.destroy',
        ]);

        $this->actingAs($admin)->get(route('admin.activity-logs.index', ['action' => 'delete']))
            ->assertOk()
            ->assertSee('Admin Audit')
            ->assertSee('admin.categories.destroy')
            ->assertDontSee('admin.categories.store');
    }

    public function test_read_requests_and_invalid_changes_are_not_recorded(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get(route('admin.categories.index'))->assertOk();
        $this->actingAs($admin)->post(route('admin.categories.store'), ['name' => ''])
            ->assertSessionHasErrors('name');

        $this->assertSame(0, ActivityLog::count());
    }

    public function test_cs_password_change_is_visible_to_admin_without_storing_password(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $cs = User::factory()->create(['role' => 'cs']);
        WhatsAppCs::create(['name' => 'CS Audit', 'phone' => '6281212345678', 'weight' => 1, 'is_active' => true, 'user_id' => $cs->id]);

        $this->actingAs($cs)->put(route('cs.password.update'), [
            'current_password' => 'password',
            'password' => 'NewStrongPassword123!',
            'password_confirmation' => 'NewStrongPassword123!',
        ])->assertRedirect(route('cs.dashboard'));

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $cs->id,
            'action' => 'update',
            'subject' => 'Kata Sandi',
            'route_name' => 'cs.password.update',
        ]);
        $this->assertStringNotContainsString('NewStrongPassword123!', ActivityLog::first()->toJson());
        $this->actingAs($admin)->get(route('admin.activity-logs.index', ['user_id' => $cs->id]))
            ->assertOk()
            ->assertSee($cs->name);
    }

    public function test_successful_login_and_logout_are_recorded_but_failed_login_is_not(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->post(route('login'), ['email' => $admin->email, 'password' => 'wrong-password'])
            ->assertSessionHasErrors();
        $this->assertSame(0, ActivityLog::count());

        $this->post(route('login'), ['email' => $admin->email, 'password' => 'password'])
            ->assertRedirect();
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id, 'action' => 'login', 'route_name' => 'login',
        ]);

        $this->post(route('logout'))->assertRedirect('/');
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id, 'action' => 'logout', 'route_name' => 'logout',
        ]);
    }
}
