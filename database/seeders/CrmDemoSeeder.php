<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Lead;
use App\Models\WhatsAppCs;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CrmDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (! in_array(app()->environment(), ['local', 'testing'], true)) {
            throw new RuntimeException('Seeder contoh CRM hanya boleh dijalankan di lingkungan local atau testing.');
        }

        $cs = WhatsAppCs::query()
            ->whereNotNull('user_id')
            ->whereHas('user', fn ($query) => $query->where('is_active', true))
            ->orderByDesc('is_active')
            ->orderBy('id')
            ->first();

        if (! $cs) {
            throw new RuntimeException('Buat dan aktifkan akun login CS terlebih dahulu sebelum mengisi contoh CRM.');
        }

        $samples = [
            ['phone' => '6280000000001', 'name' => '[DEMO] Info paket', 'city' => 'Makassar', 'month' => 1, 'need' => 'package_price', 'stage' => 'baru'],
            ['phone' => '6280000000002', 'name' => '[DEMO] Jadwal keberangkatan', 'city' => 'Gowa', 'month' => 2, 'need' => 'departure_schedule', 'stage' => 'dihubungi', 'task' => ['Hubungi kembali untuk pilihan jadwal', 'overdue']],
            ['phone' => '6280000000003', 'name' => '[DEMO] Fasilitas', 'city' => 'Kendari', 'month' => 3, 'need' => 'facilities', 'stage' => 'konsultasi', 'task' => ['Kirim rincian fasilitas hotel', 'today']],
            ['phone' => '6280000000004', 'name' => '[DEMO] Dokumen', 'city' => 'Palu', 'month' => 4, 'need' => 'documents', 'stage' => 'penawaran', 'task' => ['Jelaskan dokumen yang diperlukan', 'tomorrow']],
            ['phone' => '6280000000005', 'name' => '[DEMO] Kebutuhan lainnya', 'city' => 'Parepare', 'month' => 5, 'need' => 'other', 'summary' => 'Pendamping lansia dan bantuan kursi roda', 'stage' => 'booking'],
            ['phone' => '6280000000006', 'name' => '[DEMO] Booking berhasil', 'city' => 'Makassar', 'month' => 6, 'need' => 'package_price', 'stage' => 'berhasil'],
            ['phone' => '6280000000007', 'name' => '[DEMO] Perjalanan ditunda', 'city' => 'Gowa', 'month' => 7, 'need' => 'departure_schedule', 'stage' => 'gagal'],
        ];

        $created = 0;

        DB::transaction(function () use ($samples, $cs, &$created): void {
            foreach ($samples as $sample) {
                $contact = Contact::firstOrCreate(
                    ['phone_normalized' => $sample['phone']],
                    ['name' => $sample['name']]
                );

                // A reserved phone used by someone else must never be repurposed.
                if ($contact->name !== $sample['name'] || $contact->leads()->exists()) {
                    continue;
                }

                $lead = Lead::create([
                    'contact_id' => $contact->id,
                    'display_name' => $sample['name'],
                    'assigned_cs_id' => $cs->id,
                    'attribution_method' => 'unknown',
                    'stage' => $sample['stage'],
                    'city' => $sample['city'],
                    'planned_departure_month' => now('Asia/Makassar')->startOfMonth()->addMonthsNoOverflow($sample['month'])->format('Y-m'),
                    'need_type' => $sample['need'],
                    'needs_summary' => $sample['summary'] ?? null,
                    'first_contacted_at' => $sample['stage'] === 'baru' ? null : now(),
                    'won_at' => $sample['stage'] === 'berhasil' ? now('Asia/Makassar')->startOfDay()->utc() : null,
                    'confirmation_note' => $sample['stage'] === 'berhasil' ? '[DEMO] Booking sudah dikonfirmasi untuk latihan CRM.' : null,
                    'booking_reference' => $sample['stage'] === 'berhasil' ? 'DEMO-BOOK-001' : null,
                    'lost_reason' => $sample['stage'] === 'gagal' ? '[DEMO] Calon jemaah menunda perjalanan.' : null,
                ]);

                $lead->activities()->create([
                    'actor_user_id' => $cs->user_id,
                    'type' => 'created',
                    'note' => 'Data contoh otomatis untuk latihan CRM; bukan percakapan pelanggan nyata.',
                ]);
                if ($sample['stage'] !== 'baru') {
                    $lead->activities()->create([
                        'actor_user_id' => $cs->user_id,
                        'type' => 'stage_changed',
                        'note' => 'Tahap contoh untuk latihan CRM.',
                        'changes' => ['stage' => ['from' => 'baru', 'to' => $sample['stage']]],
                    ]);
                }

                if (isset($sample['task'])) {
                    $dueAt = match ($sample['task'][1]) {
                        'overdue' => now('Asia/Makassar')->subDay()->setTime(10, 0),
                        'today' => now('Asia/Makassar')->endOfDay()->subHour(),
                        default => now('Asia/Makassar')->addDay()->setTime(10, 0),
                    };
                    $lead->followups()->create([
                        'assigned_user_id' => $cs->user_id,
                        'due_at' => $dueAt->utc(),
                        'description' => '[DEMO] '.$sample['task'][0],
                        'status' => 'open',
                    ]);
                }

                $created++;
            }
        });

        $this->command?->info("CRM demo: {$created} lead contoh ditambahkan untuk CS {$cs->name}. Jalankan ulang tanpa membuat duplikat.");
    }
}
