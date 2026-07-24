<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Premium',
                'slug' => 'premium',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Ekonomi',
                'slug' => 'ekonomi',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'VIP Deluxe',
                'slug' => 'vip-deluxe',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Haji Khusus',
                'slug' => 'haji-khusus',
                'order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
