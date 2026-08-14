<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jemaah;
use App\Models\Package;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PackageJemaahImportController extends Controller
{
    /**
     * Download a blank .xlsx template with the exact columns the importer expects, plus a
     * dropdown-locked example row so status cells can't be typo'd into an unrecognized value.
     */
    public function template(Package $package)
    {
        $itemLabels = array_values(Registration::ITEM_TYPES);
        $statusLabels = array_values(Registration::STATUSES);
        $lastCol = chr(ord('A') + 1 + count($itemLabels)); // last status column (A=Jemaah, B=Paspor, then one per item type)

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Import');

        $sheet->fromArray(array_merge(['Jemaah', 'No. Paspor'], $itemLabels), null, 'A1');
        $sheet->fromArray(
            array_merge(['Contoh: Ahmad Fauzi', 'A1234567'], array_fill(0, count($itemLabels), $statusLabels[0])),
            null,
            'A2'
        );

        $sheet->getStyle('A1:'.$lastCol.'1')->getFont()->setBold(true);
        $sheet->freezePane('A2');
        foreach (range('A', $lastCol) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $validationList = '"'.implode(',', $statusLabels).'"';
        foreach (range('C', $lastCol) as $col) {
            for ($row = 2; $row <= 200; $row++) {
                $validation = $sheet->getCell($col.$row)->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(DataValidation::STYLE_STOP);
                $validation->setAllowBlank(true);
                $validation->setShowDropDown(true);
                $validation->setShowErrorMessage(true);
                $validation->setErrorTitle('Status tidak valid');
                $validation->setError('Pilih salah satu: '.implode(', ', $statusLabels));
                $validation->setFormula1($validationList);
            }
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'template-import-jemaah-'.$package->slug.'.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Parse the uploaded Excel file, classify every row, and cache the result for confirmation.
     * Nothing is written to the database at this step.
     */
    public function preview(Request $request, Package $package)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx'],
        ]);

        $sheet = IOFactory::load($request->file('file')->getRealPath())->getActiveSheet();
        $rowsData = $sheet->toArray(null, true, true, false);
        $itemTypes = array_keys(Registration::ITEM_TYPES);

        $rows = [];
        $seenPassports = [];

        foreach (array_slice($rowsData, 1) as $rawRow) {
            $name = trim((string) ($rawRow[0] ?? ''));
            $passport = trim((string) ($rawRow[1] ?? ''));

            if ($name === '' && $passport === '') {
                continue;
            }

            $statuses = [];
            $hasWarning = false;

            foreach ($itemTypes as $index => $type) {
                [$status, $recognized] = $this->parseStatus($rawRow[2 + $index] ?? null);
                $statuses[$type] = $status;
                $hasWarning = $hasWarning || ! $recognized;
            }

            if ($passport === '') {
                $classification = 'invalid_passport';
            } elseif (isset($seenPassports[$passport])) {
                $classification = 'duplicate_in_file';
            } else {
                $seenPassports[$passport] = true;
                $jemaah = Jemaah::where('passport_number', $passport)->first();

                if (! $jemaah) {
                    $classification = 'new_jemaah';
                } else {
                    $exists = Registration::where('jemaah_id', $jemaah->id)
                        ->where('package_id', $package->id)
                        ->exists();
                    $classification = $exists ? 'matched_existing_registration' : 'matched_new_registration';
                }
            }

            $rows[] = [
                'name' => $name,
                'passport' => $passport,
                'classification' => $classification,
                'statuses' => $statuses,
                'warning' => $hasWarning,
            ];
        }

        $token = (string) Str::uuid();
        Cache::put("jemaah-import:{$token}", [
            'package_id' => $package->id,
            'rows' => $rows,
        ], now()->addMinutes(15));

        $counts = [
            'total' => count($rows),
            'committable' => collect($rows)->whereNotIn('classification', ['duplicate_in_file', 'invalid_passport'])->count(),
            'new_jemaah' => collect($rows)->where('classification', 'new_jemaah')->count(),
            'duplicate' => collect($rows)->where('classification', 'duplicate_in_file')->count(),
            'invalid' => collect($rows)->where('classification', 'invalid_passport')->count(),
        ];

        return view('admin.packages.jemaah.import-preview', compact('package', 'token', 'rows', 'counts'));
    }

    /**
     * Commit the previously previewed, cached import into the database.
     */
    public function confirm(Request $request, Package $package)
    {
        $request->validate(['token' => ['required', 'string']]);

        $payload = Cache::get("jemaah-import:{$request->string('token')}");

        if (! $payload || $payload['package_id'] !== $package->id) {
            return redirect()->route('admin.packages.jemaah.index', $package)
                ->with('status', 'Sesi import sudah kedaluwarsa. Silakan upload ulang.');
        }

        $imported = 0;

        DB::transaction(function () use ($payload, $package, &$imported) {
            foreach ($payload['rows'] as $row) {
                if (in_array($row['classification'], ['duplicate_in_file', 'invalid_passport'], true)) {
                    continue;
                }

                if ($row['classification'] === 'new_jemaah') {
                    $jemaah = Jemaah::create(['name' => $row['name'], 'passport_number' => $row['passport']]);
                    $registration = Registration::create(['jemaah_id' => $jemaah->id, 'package_id' => $package->id, 'status' => 'active']);
                } else {
                    $jemaah = Jemaah::where('passport_number', $row['passport'])->first();
                    $registration = Registration::firstOrCreate(
                        ['jemaah_id' => $jemaah->id, 'package_id' => $package->id],
                        ['status' => 'active']
                    );
                }

                foreach ($row['statuses'] as $type => $status) {
                    $registration->items()->where('type', $type)->update(['status' => $status]);
                }

                $imported++;
            }
        });

        Cache::forget("jemaah-import:{$request->string('token')}");

        return redirect()->route('admin.packages.jemaah.index', $package)
            ->with('status', "{$imported} data berhasil diimpor.");
    }

    /**
     * Convert a status cell's text into a status key. Reuses Registration::STATUSES as the
     * single source of truth so export/import stay in sync by construction.
     *
     * @return array{0: string, 1: bool} [status key, whether the text was recognized]
     */
    private function parseStatus(mixed $raw): array
    {
        static $map = null;

        if ($map === null) {
            $map = collect(Registration::STATUSES)
                ->mapWithKeys(fn ($label, $key) => [mb_strtolower($label) => $key]);
        }

        $normalized = mb_strtolower(trim((string) $raw));

        return [$map[$normalized] ?? 'missing', $map->has($normalized)];
    }
}
