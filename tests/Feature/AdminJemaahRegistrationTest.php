<?php

namespace Tests\Feature;

use App\Models\Jemaah;
use App\Models\Package;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class AdminJemaahRegistrationTest extends TestCase
{
    private User $admin;

    private Package $package;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->package = Package::create([
            'name' => 'Umrah 12 Hari',
            'slug' => 'umrah-12-hari',
            'duration_days' => 12,
            'departure_date' => '2026-12-15',
            'airline' => 'Saudia Airlines',
            'hotel_makkah' => 'Hotel Makkah',
            'hotel_madinah' => 'Hotel Madinah',
            'price' => 30000000,
            'is_active' => true,
            'order' => 1,
        ]);
    }

    public function test_store_creates_registration_with_seven_checklist_items(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.packages.jemaah.store', $this->package), [
            'new_name' => 'Rini Ladulu',
            'new_passport_number' => 'A1234567',
        ]);

        $response->assertRedirect(route('admin.packages.jemaah.index', $this->package));

        $jemaah = Jemaah::where('passport_number', 'A1234567')->first();
        $this->assertNotNull($jemaah);

        $registration = Registration::where('jemaah_id', $jemaah->id)->where('package_id', $this->package->id)->first();
        $this->assertNotNull($registration);
        $this->assertCount(7, $registration->items);
        $this->assertTrue($registration->items->every(fn ($item) => $item->status === 'missing'));
    }

    public function test_store_saves_pic_name_and_roster_can_be_filtered_by_pic(): void
    {
        $this->actingAs($this->admin)->post(route('admin.packages.jemaah.store', $this->package), [
            'new_name' => 'Rini Ladulu',
            'new_passport_number' => 'A1234567',
            'pic_name' => 'Ahmad',
        ]);

        $registration = Registration::whereHas('jemaah', fn ($q) => $q->where('passport_number', 'A1234567'))->first();
        $this->assertSame('Ahmad', $registration->pic_name);

        $response = $this->actingAs($this->admin)->get(route('admin.packages.jemaah.index', ['package' => $this->package, 'pic' => 'Ahmad']));
        $response->assertStatus(200);
        $response->assertSee('Rini Ladulu');

        $response = $this->actingAs($this->admin)->get(route('admin.packages.jemaah.index', ['package' => $this->package, 'pic' => '__unassigned']));
        $response->assertStatus(200);
        $response->assertDontSee('Rini Ladulu');
    }

    public function test_pic_can_be_updated_inline(): void
    {
        $jemaah = Jemaah::create(['name' => 'Budi Santoso', 'passport_number' => 'D9998887']);
        $registration = Registration::create(['jemaah_id' => $jemaah->id, 'package_id' => $this->package->id]);

        $response = $this->actingAs($this->admin)->patch(
            route('admin.packages.jemaah.pic.update', [$this->package, $registration]),
            ['pic_name' => 'Fatimah']
        );

        $response->assertRedirect();
        $this->assertSame('Fatimah', $registration->refresh()->pic_name);
    }

    public function test_jemaah_personal_data_can_be_updated(): void
    {
        $jemaah = Jemaah::create(['name' => 'Rini Ladulu', 'passport_number' => 'A1234567']);

        $response = $this->actingAs($this->admin)->patch(route('admin.jemaah.update', $jemaah), [
            'name' => 'Rini Ladulu Samauna',
            'passport_number' => 'A1234567',
            'birth_date' => '1990-05-20',
            'address' => 'Jl. Contoh No. 1, Jakarta',
        ]);

        $response->assertRedirect();

        $jemaah->refresh();
        $this->assertSame('Rini Ladulu Samauna', $jemaah->name);
        $this->assertSame('1990-05-20', $jemaah->birth_date->format('Y-m-d'));
        $this->assertSame('Jl. Contoh No. 1, Jakarta', $jemaah->address);
    }

    public function test_bulk_update_pic_applies_to_all_selected_registrations_only(): void
    {
        $jemaahA = Jemaah::create(['name' => 'Jemaah A', 'passport_number' => 'BULK0001']);
        $jemaahB = Jemaah::create(['name' => 'Jemaah B', 'passport_number' => 'BULK0002']);
        $jemaahC = Jemaah::create(['name' => 'Jemaah C', 'passport_number' => 'BULK0003']);
        $regA = Registration::create(['jemaah_id' => $jemaahA->id, 'package_id' => $this->package->id]);
        $regB = Registration::create(['jemaah_id' => $jemaahB->id, 'package_id' => $this->package->id]);
        $regC = Registration::create(['jemaah_id' => $jemaahC->id, 'package_id' => $this->package->id]);

        $response = $this->actingAs($this->admin)->post(route('admin.packages.jemaah.bulk-pic', $this->package), [
            'registration_ids' => [$regA->id, $regB->id],
            'pic_name' => 'Ustadz Fulan',
        ]);

        $response->assertRedirect();
        $this->assertSame('Ustadz Fulan', $regA->refresh()->pic_name);
        $this->assertSame('Ustadz Fulan', $regB->refresh()->pic_name);
        $this->assertNull($regC->refresh()->pic_name);
    }

    public function test_bulk_destroy_removes_only_selected_registrations(): void
    {
        $jemaahA = Jemaah::create(['name' => 'Jemaah A', 'passport_number' => 'BULK0011']);
        $jemaahB = Jemaah::create(['name' => 'Jemaah B', 'passport_number' => 'BULK0012']);
        $regA = Registration::create(['jemaah_id' => $jemaahA->id, 'package_id' => $this->package->id]);
        $regB = Registration::create(['jemaah_id' => $jemaahB->id, 'package_id' => $this->package->id]);

        $response = $this->actingAs($this->admin)->delete(route('admin.packages.jemaah.bulk-destroy', $this->package), [
            'registration_ids' => [$regA->id],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('registrations', ['id' => $regA->id]);
        $this->assertDatabaseHas('registrations', ['id' => $regB->id]);
    }

    public function test_bulk_update_status_applies_to_one_item_across_selected_registrations_only(): void
    {
        $jemaahA = Jemaah::create(['name' => 'Jemaah A', 'passport_number' => 'BULK0021']);
        $jemaahB = Jemaah::create(['name' => 'Jemaah B', 'passport_number' => 'BULK0022']);
        $regA = Registration::create(['jemaah_id' => $jemaahA->id, 'package_id' => $this->package->id]);
        $regB = Registration::create(['jemaah_id' => $jemaahB->id, 'package_id' => $this->package->id]);

        $response = $this->actingAs($this->admin)->post(route('admin.packages.jemaah.bulk-status', $this->package), [
            'registration_ids' => [$regA->id],
            'type' => 'visa',
            'status' => 'completed',
        ]);

        $response->assertRedirect();
        $this->assertSame('completed', $regA->refresh()->items->firstWhere('type', 'visa')->status);
        $this->assertSame('missing', $regA->items->firstWhere('type', 'passport')->status);
        $this->assertSame('missing', $regB->refresh()->items->firstWhere('type', 'visa')->status);
    }

    public function test_jemaah_show_page_lists_gender_and_all_registrations(): void
    {
        $jemaah = Jemaah::create(['name' => 'Rini Ladulu Samauna', 'passport_number' => 'A1234567', 'gender' => 'female']);
        Registration::create(['jemaah_id' => $jemaah->id, 'package_id' => $this->package->id, 'pic_name' => 'Ahmad']);

        $response = $this->actingAs($this->admin)->get(route('admin.jemaah.show', $jemaah));

        $response->assertStatus(200);
        $response->assertSee('Rini Ladulu Samauna');
        $response->assertSee('Perempuan');
        $response->assertSee($this->package->name);
        $response->assertSee('Ahmad');
    }

    public function test_jemaah_update_accepts_gender(): void
    {
        $jemaah = Jemaah::create(['name' => 'Budi Santoso', 'passport_number' => 'D1112223']);

        $response = $this->actingAs($this->admin)->patch(route('admin.jemaah.update', $jemaah), [
            'name' => 'Budi Santoso',
            'gender' => 'male',
        ]);

        $response->assertRedirect();
        $this->assertSame('male', $jemaah->refresh()->gender);
    }

    public function test_duplicate_registration_is_not_created_twice(): void
    {
        $jemaah = Jemaah::create(['name' => 'Aisyah Sinta', 'passport_number' => 'B7654321']);

        $this->actingAs($this->admin)->post(route('admin.packages.jemaah.store', $this->package), [
            'jemaah_id' => $jemaah->id,
        ]);
        $this->actingAs($this->admin)->post(route('admin.packages.jemaah.store', $this->package), [
            'jemaah_id' => $jemaah->id,
        ]);

        $this->assertSame(
            1,
            Registration::where('jemaah_id', $jemaah->id)->where('package_id', $this->package->id)->count()
        );
    }

    public function test_checklist_status_toggle_persists_and_leaves_other_items_untouched(): void
    {
        $jemaah = Jemaah::create(['name' => 'Zahra Rayhanna', 'passport_number' => 'C1112223']);
        $registration = Registration::create(['jemaah_id' => $jemaah->id, 'package_id' => $this->package->id]);

        $this->actingAs($this->admin)->patch(
            route('admin.registrations.items.update', [$registration, 'vaccine']),
            ['status' => 'completed']
        );

        $items = $registration->refresh()->items->keyBy('type');

        $this->assertSame('completed', $items['vaccine']->status);
        $this->assertSame('missing', $items['passport']->status);
        $this->assertSame('missing', $items['ktp']->status);
    }

    public function test_public_tracking_search_redirects_to_result_page(): void
    {
        $params = ['passport' => 'A1234567', 'birth_date' => '1990-05-20'];

        $response = $this->get(route('public.jemaah.tracking', $params));

        $response->assertRedirect(route('public.jemaah.tracking.result', $params));
    }

    public function test_public_tracking_finds_jemaah_by_passport_and_birth_date(): void
    {
        $jemaah = Jemaah::create(['name' => 'Rini Ladulu Samauna', 'passport_number' => 'A1234567', 'birth_date' => '1990-05-20']);
        Registration::create(['jemaah_id' => $jemaah->id, 'package_id' => $this->package->id]);

        $response = $this->get(route('public.jemaah.tracking.result', [
            'passport' => 'A1234567',
            'birth_date' => '1990-05-20',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Rini Ladulu Samauna');
        $response->assertSee($this->package->name);
    }

    public function test_public_tracking_reports_not_found_when_birth_date_does_not_match(): void
    {
        $jemaah = Jemaah::create(['name' => 'Rini Ladulu Samauna', 'passport_number' => 'A1234567', 'birth_date' => '1990-05-20']);
        Registration::create(['jemaah_id' => $jemaah->id, 'package_id' => $this->package->id]);

        $response = $this->get(route('public.jemaah.tracking.result', [
            'passport' => 'A1234567',
            'birth_date' => '1991-01-01',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Tidak Ditemukan');
    }

    public function test_public_tracking_reports_not_found_for_unknown_passport(): void
    {
        $response = $this->get(route('public.jemaah.tracking.result', [
            'passport' => 'Z0000000',
            'birth_date' => '2000-01-01',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Tidak Ditemukan');
    }

    public function test_import_preview_classifies_rows_without_writing_to_database(): void
    {
        $existingJemaah = Jemaah::create(['name' => 'Sudah Ada', 'passport_number' => 'E1111111']);
        Registration::create(['jemaah_id' => $existingJemaah->id, 'package_id' => $this->package->id]);

        $file = $this->makeImportFile([
            ['Jemaah Baru', 'F2222222', 'Selesai', 'Belum', 'Selesai', 'Belum', 'Selesai', 'Selesai', 'Proses'],
            ['Sudah Ada', 'E1111111', 'Selesai', 'Selesai', 'Selesai', 'Selesai', 'Selesai', 'Selesai', 'Selesai'],
            ['Duplikat Baris', 'F2222222', 'Selesai', 'Selesai', 'Selesai', 'Selesai', 'Selesai', 'Selesai', 'Selesai'],
        ]);

        $response = $this->actingAs($this->admin)->post(
            route('admin.packages.jemaah.import.preview', $this->package),
            ['file' => $file]
        );

        $response->assertStatus(200);
        $response->assertViewHas('counts', function ($counts) {
            return $counts['total'] === 3
                && $counts['committable'] === 2
                && $counts['new_jemaah'] === 1
                && $counts['duplicate'] === 1
                && $counts['invalid'] === 0;
        });
        $this->assertDatabaseMissing('jemaahs', ['passport_number' => 'F2222222']);
    }

    public function test_import_confirm_creates_new_jemaah_and_updates_existing_registration(): void
    {
        $existingJemaah = Jemaah::create(['name' => 'Sudah Ada', 'passport_number' => 'E1111111']);
        $existingRegistration = Registration::create(['jemaah_id' => $existingJemaah->id, 'package_id' => $this->package->id]);

        $file = $this->makeImportFile([
            ['Jemaah Baru', 'F2222222', 'Selesai', 'Belum', 'Selesai', 'Belum', 'Selesai', 'Selesai', 'Proses'],
            ['Sudah Ada', 'E1111111', 'Selesai', 'Selesai', 'Selesai', 'Selesai', 'Selesai', 'Selesai', 'Selesai'],
        ]);

        $preview = $this->actingAs($this->admin)->post(
            route('admin.packages.jemaah.import.preview', $this->package),
            ['file' => $file]
        );
        $token = $preview->viewData('token');

        $confirm = $this->actingAs($this->admin)->post(
            route('admin.packages.jemaah.import.confirm', $this->package),
            ['token' => $token]
        );

        $confirm->assertRedirect(route('admin.packages.jemaah.index', $this->package));

        $newJemaah = Jemaah::where('passport_number', 'F2222222')->first();
        $this->assertNotNull($newJemaah);
        $newRegistration = Registration::where('jemaah_id', $newJemaah->id)->where('package_id', $this->package->id)->first();
        $this->assertNotNull($newRegistration);
        $this->assertSame('completed', $newRegistration->items->firstWhere('type', 'passport')->status);
        $this->assertSame('missing', $newRegistration->items->firstWhere('type', 'vaccine')->status);

        $existingItems = $existingRegistration->refresh()->items->keyBy('type');
        $this->assertSame('completed', $existingItems['vaccine']->status);
    }

    private function makeImportFile(array $rows): UploadedFile
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray(['Jemaah', 'No. Paspor', 'Paspor', 'Vaksin', 'KTP', 'KK', 'Perlengkapan', 'Visa', 'Tiket'], null, 'A1');

        foreach ($rows as $i => $row) {
            $sheet->fromArray($row, null, 'A'.($i + 2));
        }

        $path = tempnam(sys_get_temp_dir(), 'jemaah_import_').'.xlsx';
        (new Xlsx($spreadsheet))->save($path);

        return new UploadedFile($path, 'import.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
    }
}
