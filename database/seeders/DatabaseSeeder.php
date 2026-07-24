<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $testUser = User::where('email', 'test@example.com')->first();
        if (!$testUser) {
            $testUser = User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        $admin = User::where('email', 'admin@example.com')->first();
        if (!$admin) {
            $admin = User::factory()->create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
            ]);
        }

        if ($admin->role !== 'admin') {
            $admin->forceFill(['role' => 'admin'])->save();
        }

        $staff = User::where('email', 'staff@example.com')->first();
        if (!$staff) {
            $staff = User::factory()->create([
                'name' => 'Staff Operasional',
                'email' => 'staff@example.com',
                'role' => 'admin',
            ]);
        }

        $this->call([
            CategorySeeder::class,
            PackageSeeder::class,
            TestimonialSeeder::class,
            GallerySeeder::class,
            FaqSeeder::class,
            LandingPageSeeder::class,
        ]);
    }
}
