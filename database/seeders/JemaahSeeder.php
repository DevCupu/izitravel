<?php

namespace Database\Seeders;

use App\Models\Jemaah;
use App\Models\Package;
use App\Models\Registration;
use Illuminate\Database\Seeder;

class JemaahSeeder extends Seeder
{
    /**
     * Seed a sample departure with jemaah covering all 4 overall-status buckets
     * (not_started, in_progress, ready, attention) for testing the roster,
     * admin tracking, and public passport lookup pages.
     */
    public function run(): void
    {
        $package = Package::firstOrCreate(
            ['slug' => 'umrah-12-hari-15-desember-2026'],
            [
                'name' => 'Umrah 12 Hari',
                'duration_days' => 12,
                'departure_date' => '2026-12-15',
                'airline' => 'Saudia Airlines',
                'hotel_makkah' => 'Swissotel Al Maqam Makkah',
                'hotel_makkah_nights' => 5,
                'hotel_madinah' => 'Pullman Zamzam Madinah',
                'hotel_madinah_nights' => 5,
                'manasik_date' => '2026-11-20',
                'manasik_location' => 'Aula Kantor IZI Travel, Jakarta',
                'price' => 32000000,
                'is_active' => true,
                'order' => 1,
            ]
        );

        $jemaahData = [
            [
                'name' => 'Rini Ladulu Samauna',
                'passport_number' => 'A1234567',
                'statuses' => ['passport' => 'completed', 'vaccine' => 'missing', 'ktp' => 'completed', 'kk' => 'missing', 'equipment' => 'completed', 'visa' => 'completed', 'ticket' => 'in_progress'],
            ],
            [
                'name' => 'Aisyah Sinta',
                'passport_number' => 'A2345678',
                'statuses' => ['passport' => 'completed', 'vaccine' => 'missing', 'ktp' => 'missing', 'kk' => 'missing', 'equipment' => 'completed', 'visa' => 'completed', 'ticket' => 'in_progress'],
            ],
            [
                'name' => 'Zahra Rayhanna',
                'passport_number' => 'A3456789',
                'statuses' => array_fill_keys(array_keys(Registration::ITEM_TYPES), 'completed'),
            ],
            [
                'name' => 'Budi Santoso',
                'passport_number' => 'A4567890',
                'statuses' => array_fill_keys(array_keys(Registration::ITEM_TYPES), 'missing'),
            ],
            [
                'name' => 'Siti Aminah',
                'passport_number' => 'A5678901',
                'statuses' => ['passport' => 'completed', 'vaccine' => 'completed', 'ktp' => 'completed', 'kk' => 'completed', 'equipment' => 'completed', 'visa' => 'problem', 'ticket' => 'missing'],
            ],
        ];

        foreach ($jemaahData as $data) {
            $jemaah = Jemaah::firstOrCreate(
                ['passport_number' => $data['passport_number']],
                ['name' => $data['name']]
            );

            $registration = Registration::firstOrCreate(
                ['jemaah_id' => $jemaah->id, 'package_id' => $package->id],
                ['status' => 'active']
            );

            foreach ($data['statuses'] as $type => $status) {
                $registration->items()->where('type', $type)->update(['status' => $status]);
            }
        }
    }
}
