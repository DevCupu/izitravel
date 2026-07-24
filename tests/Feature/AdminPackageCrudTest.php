<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Package;
use App\Models\User;
use Tests\TestCase;

class AdminPackageCrudTest extends TestCase
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

    public function test_admin_can_view_packages_list(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.packages.index'));

        $response->assertStatus(200);
        $response->assertSee('Paket Umrah');
    }

    public function test_admin_can_create_new_package(): void
    {
        $category = Category::create([
            'name' => 'Umrah Reguler',
            'slug' => 'umrah-reguler',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.packages.store'), [
            'name' => 'Paket Umrah Syawal Exclusive',
            'duration_days' => 9,
            'departure_date' => '2026-08-10',
            'airline' => 'Saudia Airlines',
            'hotel_makkah' => 'Safwah Orchid',
            'hotel_madinah' => 'Al Nokhba',
            'price' => 32000000,
            'category' => $category->slug,
            'is_active' => 1,
            'order' => 1,
        ]);

        $response->assertRedirect(route('admin.packages.index'));
        $this->assertDatabaseHas('packages', [
            'name' => 'Paket Umrah Syawal Exclusive',
            'price' => 32000000,
        ]);
    }

    public function test_admin_can_update_package(): void
    {
        $package = Package::create([
            'name' => 'Paket Umrah Awal',
            'slug' => 'paket-umrah-awal',
            'duration_days' => 9,
            'departure_date' => '2026-09-01',
            'airline' => 'Garuda',
            'hotel_makkah' => 'Makkah Hotel',
            'hotel_madinah' => 'Madinah Hotel',
            'price' => 25000000,
            'is_active' => true,
            'order' => 1,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.packages.update', $package), [
            'name' => 'Paket Umrah Terperbarui',
            'duration_days' => 10,
            'departure_date' => '2026-09-05',
            'airline' => 'Garuda Indonesia',
            'hotel_makkah' => 'Makkah Hotel Deluxe',
            'hotel_madinah' => 'Madinah Hotel Deluxe',
            'price' => 27000000,
            'is_active' => 1,
            'order' => 1,
        ]);

        $response->assertRedirect(route('admin.packages.index'));
        $this->assertDatabaseHas('packages', [
            'id' => $package->id,
            'name' => 'Paket Umrah Terperbarui',
            'price' => 27000000,
        ]);
    }

    public function test_admin_can_delete_package(): void
    {
        $package = Package::create([
            'name' => 'Paket Hapus',
            'slug' => 'paket-hapus',
            'duration_days' => 9,
            'departure_date' => '2026-12-01',
            'airline' => 'Lion Air',
            'hotel_makkah' => 'Hotel A',
            'hotel_madinah' => 'Hotel B',
            'price' => 20000000,
            'is_active' => false,
            'order' => 99,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.packages.destroy', $package));

        $response->assertRedirect(route('admin.packages.index'));
        $this->assertDatabaseMissing('packages', [
            'id' => $package->id,
        ]);
    }
}
