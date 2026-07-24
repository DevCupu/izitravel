<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $testUser = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password123'),
            ]
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password123'),
            ]
        );

        if ($admin->role !== 'admin') {
            $admin->forceFill([
                'role' => 'admin',
            ])->save();
        }

        User::firstOrCreate(
            ['email' => 'staff@example.com'],
            [
                'name' => 'Staff Operasional',
                'role' => 'admin',
                'password' => Hash::make('password123'),
            ]
        );

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