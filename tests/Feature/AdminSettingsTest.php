<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Setting;
use Tests\TestCase;

class AdminSettingsTest extends TestCase
{
    public function test_guest_cannot_access_admin_settings(): void
    {
        $response = $this->get(route('admin.settings.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_admin_can_access_settings_page(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.settings.index'));

        $response->assertStatus(200);
        $response->assertSee('Pengaturan', false);
        $response->assertSee('haramain_youtube_makkah');
    }

    public function test_admin_can_update_live_stream_youtube_settings(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)->post(route('admin.settings.update'), [
            'site_name' => 'IZI Travel Premium',
            'haramain_youtube_makkah' => 'https://www.youtube.com/watch?v=Y4Zo-j4-IjA',
            'haramain_youtube_madinah' => 'UCROKYPep-UuODNwyipe6JMw',
            'haramain_density_base' => 300000,
        ]);

        $response->assertRedirect();

        $this->assertEquals('https://www.youtube.com/watch?v=Y4Zo-j4-IjA', Setting::getValue('haramain_youtube_makkah'));
        $this->assertEquals('UCROKYPep-UuODNwyipe6JMw', Setting::getValue('haramain_youtube_madinah'));
        $this->assertEquals('300000', Setting::getValue('haramain_density_base'));
    }

    public function test_admin_can_access_and_update_profile(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin Lama',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('profile.edit'));
        $response->assertStatus(200);
        $response->assertSee('Edit Profil Admin');

        $updateResponse = $this->actingAs($admin)->patch(route('profile.update'), [
            'name' => 'Admin Baru IZI',
            'email' => 'adminbaru@izitravel.com',
        ]);

        $updateResponse->assertRedirect(route('profile.edit'));
        $this->assertEquals('Admin Baru IZI', $admin->fresh()->name);
    }
}
