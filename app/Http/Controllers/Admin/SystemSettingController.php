<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SystemSettingController extends Controller
{
    /**
     * Display the System Settings & Diagnostics dashboard.
     */
    public function index()
    {
        $settings = Setting::allValues();

        // 1. Diagnostics information
        $diagnostics = $this->gatherDiagnostics();

        // 2. Database tables overview
        $databaseTables = $this->getDatabaseTables();

        // 3. Log file status
        $logPath = storage_path('logs/laravel.log');
        $logInfo = [
            'exists' => file_exists($logPath),
            'size' => file_exists($logPath) ? $this->formatBytes(filesize($logPath)) : '0 B',
            'updated_at' => file_exists($logPath) ? date('Y-m-d H:i:s', filemtime($logPath)) : '—',
        ];

        return view('admin.system.index', compact('settings', 'diagnostics', 'databaseTables', 'logInfo'));
    }

    /**
     * Update system settings.
     */
    public function update(Request $request)
    {
        $keys = [
            // Maintenance Mode & Access
            'maintenance_mode_enabled',
            'maintenance_bypass_admin',
            'maintenance_bypass_key',
            'maintenance_title',
            'maintenance_message',
            'maintenance_estimated_end',
            'maintenance_emergency_contact',
            'maintenance_allowed_ips',
            'registration_open',
            'registration_closed_message',

            // Cache & Performance
            'cache_lifetime_seconds',
            'image_optimization_quality',
            'minify_html_enabled',
            'asset_version',
        ];

        $request->validate([
            'maintenance_title'            => ['nullable', 'string', 'max:200'],
            'maintenance_message'          => ['nullable', 'string', 'max:2000'],
            'maintenance_bypass_key'       => ['nullable', 'string', 'max:100'],
            'maintenance_emergency_contact'=> ['nullable', 'string', 'max:50'],
            'registration_closed_message'  => ['nullable', 'string', 'max:1000'],
            'cache_lifetime_seconds'       => ['nullable', 'numeric', 'min:0'],
            'image_optimization_quality'   => ['nullable', 'integer', 'min:40', 'max:100'],
        ]);

        // Checkbox toggles (store 1 or 0)
        $checkboxKeys = [
            'maintenance_mode_enabled',
            'maintenance_bypass_admin',
            'registration_open',
            'minify_html_enabled',
        ];

        foreach ($checkboxKeys as $cKey) {
            Setting::setValue($cKey, $request->has($cKey) ? '1' : '0');
        }

        // Other values
        foreach ($keys as $key) {
            if (in_array($key, $checkboxKeys, true)) {
                continue;
            }
            if ($request->has($key)) {
                $val = $request->input($key);
                if ($key === 'maintenance_emergency_contact' && ! empty($val)) {
                    $val = preg_replace('/[^0-9]/', '', $val);
                }
                Setting::setValue($key, $val);
            }
        }

        $activeTab = $request->input('active_tab', 'diagnostics');

        return redirect()->route('admin.system.index')
            ->with('status', 'Pengaturan sistem berhasil diperbarui!')
            ->with('active_tab', $activeTab);
    }

    /**
     * Clear application caches via Artisan.
     */
    public function clearCache(Request $request)
    {
        $type = $request->input('type', 'all');
        $output = '';

        try {
            switch ($type) {
                case 'cache':
                    Artisan::call('cache:clear');
                    $output = 'Cache aplikasi berhasil dibersihkan.';
                    break;
                case 'view':
                    Artisan::call('view:clear');
                    $output = 'Cache template blade / tampilan berhasil dibersihkan.';
                    break;
                case 'route':
                    Artisan::call('route:clear');
                    $output = 'Cache rute URL berhasil dibersihkan.';
                    break;
                case 'config':
                    Artisan::call('config:clear');
                    $output = 'Cache konfigurasi sistem berhasil dibersihkan.';
                    break;
                case 'optimize':
                    Artisan::call('optimize');
                    $output = 'Sistem berhasil dioptimalkan untuk mode produksi.';
                    break;
                case 'storage_link':
                    Artisan::call('storage:link');
                    $output = 'Symlink direktori public storage berhasil diperbaiki.';
                    break;
                case 'all':
                default:
                    Artisan::call('cache:clear');
                    Artisan::call('view:clear');
                    Artisan::call('route:clear');
                    Artisan::call('config:clear');
                    $output = 'Seluruh cache sistem (App, View, Route, Config) berhasil dibersihkan seketika.';
                    break;
            }

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $output]);
            }

            return redirect()->route('admin.system.index')
                ->with('status', $output)
                ->with('active_tab', 'performance');
        } catch (\Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal membersihkan cache: ' . $e->getMessage()], 500);
            }

            return redirect()->route('admin.system.index')
                ->with('error', 'Gagal: ' . $e->getMessage())
                ->with('active_tab', 'performance');
        }
    }



    /**
     * Fetch recent log entries from laravel.log.
     */
    public function getLogs(Request $request)
    {
        $logPath = storage_path('logs/laravel.log');
        if (! file_exists($logPath)) {
            return response()->json(['logs' => [], 'message' => 'File log belum tersedia.']);
        }

        $linesToRead = (int) $request->input('lines', 150);
        $levelFilter = strtolower($request->input('level', 'all'));

        $content = File::get($logPath);
        $lines = explode("\n", $content);
        $recentLines = array_slice($lines, -$linesToRead);

        $parsedLogs = [];
        $currentLog = null;

        foreach ($recentLines as $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }

            // Check if line matches standard Laravel log header: [YYYY-MM-DD HH:II:SS] environment.LEVEL: Message
            if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] ([a-zA-Z0-9_-]+)\.([A-Z]+): (.*)$/', $line, $matches)) {
                if ($currentLog) {
                    $parsedLogs[] = $currentLog;
                }

                $level = strtolower($matches[3]);
                $currentLog = [
                    'timestamp' => $matches[1],
                    'environment' => $matches[2],
                    'level' => $level,
                    'message' => $matches[4],
                    'details' => '',
                ];
            } else {
                if ($currentLog) {
                    $currentLog['details'] .= (empty($currentLog['details']) ? '' : "\n") . $line;
                }
            }
        }

        if ($currentLog) {
            $parsedLogs[] = $currentLog;
        }

        // Apply level filter
        if ($levelFilter !== 'all') {
            $parsedLogs = array_filter($parsedLogs, fn ($item) => $item['level'] === $levelFilter);
        }

        // Reverse to show newest on top
        $parsedLogs = array_reverse(array_values($parsedLogs));

        return response()->json([
            'logs' => $parsedLogs,
            'total' => count($parsedLogs),
            'file_size' => $this->formatBytes(filesize($logPath)),
        ]);
    }

    /**
     * Clear / empty the log file.
     */
    public function clearLogs(Request $request)
    {
        $logPath = storage_path('logs/laravel.log');
        if (file_exists($logPath)) {
            File::put($logPath, '');
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'File log berhasil dikosongkan.']);
        }

        return redirect()->route('admin.system.index')
            ->with('status', 'File log berhasil dikosongkan.')
            ->with('active_tab', 'logs');
    }

    /**
     * Export complete database backup as downloadable SQL stream.
     */
    public function backupDatabase(): StreamedResponse
    {
        $dbName = config('database.connections.' . config('database.default') . '.database');
        $fileName = 'izitravel-backup-' . date('Y-m-d_H-i-s') . '.sql';

        return response()->streamDownload(function () use ($dbName) {
            $handle = fopen('php://output', 'w');

            fwrite($handle, "-- ========================================================\n");
            fwrite($handle, "-- IZI Travel & Umrah Platform - Database SQL Dump\n");
            fwrite($handle, "-- Database: {$dbName}\n");
            fwrite($handle, "-- Generated: " . date('Y-m-d H:i:s') . "\n");
            fwrite($handle, "-- PHP Version: " . PHP_VERSION . "\n");
            fwrite($handle, "-- Laravel Version: " . app()->version() . "\n");
            fwrite($handle, "-- ========================================================\n\n");
            fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n");
            fwrite($handle, "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n");
            fwrite($handle, "SET time_zone = \"+00:00\";\n\n");

            $tables = DB::select('SHOW TABLES');
            $tableKey = 'Tables_in_' . $dbName;

            foreach ($tables as $t) {
                $tableName = $t->$tableKey ?? current((array) $t);

                fwrite($handle, "\n-- --------------------------------------------------------\n");
                fwrite($handle, "-- Table structure for table `{$tableName}`\n");
                fwrite($handle, "-- --------------------------------------------------------\n");
                fwrite($handle, "DROP TABLE IF EXISTS `{$tableName}`;\n");

                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                if (! empty($createTable)) {
                    $createKey = 'Create Table';
                    $ddl = $createTable[0]->$createKey ?? $createTable[0]->{'Create View'} ?? '';
                    fwrite($handle, $ddl . ";\n\n");
                }

                // Dump table rows in chunks
                fwrite($handle, "-- Dumping data for table `{$tableName}`\n");
                $rowsCount = DB::table($tableName)->count();

                if ($rowsCount > 0) {
                    DB::table($tableName)->orderBy(DB::raw('1'))->chunk(200, function ($rows) use ($handle, $tableName) {
                        foreach ($rows as $row) {
                            $rowArray = (array) $row;
                            $columns = array_map(fn ($col) => "`{$col}`", array_keys($rowArray));
                            $values = array_map(function ($val) {
                                if (is_null($val)) {
                                    return 'NULL';
                                }
                                return "'" . addslashes((string) $val) . "'";
                            }, array_values($rowArray));

                            $insertSql = "INSERT INTO `{$tableName}` (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ");\n";
                            fwrite($handle, $insertSql);
                        }
                    });
                }

                fwrite($handle, "\n");
            }

            fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
            fwrite($handle, "-- Dump completed at " . date('Y-m-d H:i:s') . "\n");
            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }

    /**
     * Run table optimization queries for MySQL.
     */
    public function optimizeDatabase(Request $request)
    {
        try {
            $dbName = config('database.connections.' . config('database.default') . '.database');
            $tables = DB::select('SHOW TABLES');
            $tableKey = 'Tables_in_' . $dbName;
            $optimizedCount = 0;

            foreach ($tables as $t) {
                $tableName = $t->$tableKey ?? current((array) $t);
                DB::statement("OPTIMIZE TABLE `{$tableName}`");
                $optimizedCount++;
            }

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Berhasil mengoptimalkan {$optimizedCount} tabel database!",
                ]);
            }

            return redirect()->route('admin.system.index')
                ->with('status', "Berhasil mengoptimalkan {$optimizedCount} tabel database!")
                ->with('active_tab', 'database');
        } catch (\Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }

            return redirect()->route('admin.system.index')
                ->with('error', 'Gagal mengoptimalkan database: ' . $e->getMessage())
                ->with('active_tab', 'database');
        }
    }

    /**
     * Preview the maintenance page without putting the app into maintenance mode.
     */
    public function previewMaintenance()
    {
        $settings = Setting::allValues();
        return response()->view('errors.maintenance', compact('settings'));
    }

    /**
     * Gather system and server health diagnostics.
     */
    private function gatherDiagnostics(): array
    {
        $basePath = base_path();
        $diskFree = @disk_free_space($basePath);
        $diskTotal = @disk_total_space($basePath);
        $diskUsed = ($diskTotal && $diskFree) ? $diskTotal - $diskFree : null;
        $diskPercent = ($diskTotal && $diskUsed) ? round(($diskUsed / $diskTotal) * 100, 1) : 0;

        // DB Size
        $dbName = config('database.connections.' . config('database.default') . '.database');
        $dbSizeMb = 0;
        $totalTables = 0;
        try {
            $dbStats = DB::select('SELECT COUNT(*) as tables_count, SUM(data_length + index_length) as total_size FROM information_schema.tables WHERE table_schema = ?', [$dbName]);
            if (! empty($dbStats)) {
                $totalTables = $dbStats[0]->tables_count ?? 0;
                $dbSizeMb = round(($dbStats[0]->total_size ?? 0) / 1024 / 1024, 2);
            }
        } catch (\Throwable) {
            // Ignore if permission denied
        }

        // PHP Extensions check
        $criticalExtensions = [
            'pdo' => extension_loaded('pdo'),
            'pdo_mysql' => extension_loaded('pdo_mysql'),
            'openssl' => extension_loaded('openssl'),
            'curl' => extension_loaded('curl'),
            'gd' => extension_loaded('gd'),
            'mbstring' => extension_loaded('mbstring'),
            'exif' => extension_loaded('exif'),
            'bcmath' => extension_loaded('bcmath'),
            'zip' => extension_loaded('zip'),
            'fileinfo' => extension_loaded('fileinfo'),
            'sodium' => extension_loaded('sodium'),
        ];

        return [
            'php_version' => PHP_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? (PHP_OS . ' / ' . php_sapi_name()),
            'os' => PHP_OS_FAMILY . ' (' . php_uname('s') . ')',
            'laravel_version' => app()->version(),
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug'),
            'timezone' => config('app.timezone'),
            'server_time' => now()->format('Y-m-d H:i:s T'),
            'memory_limit' => ini_get('memory_limit'),
            'memory_usage' => $this->formatBytes(memory_get_usage(true)),
            'memory_peak' => $this->formatBytes(memory_get_peak_usage(true)),
            'max_execution_time' => ini_get('max_execution_time') . 's',
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'disk_free' => $diskFree ? $this->formatBytes($diskFree) : 'N/A',
            'disk_total' => $diskTotal ? $this->formatBytes($diskTotal) : 'N/A',
            'disk_used' => $diskUsed ? $this->formatBytes($diskUsed) : 'N/A',
            'disk_percent' => $diskPercent,
            'db_connection' => config('database.default'),
            'db_name' => $dbName,
            'db_size_mb' => $dbSizeMb . ' MB',
            'total_tables' => $totalTables,
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'queue_driver' => config('queue.default'),
            'mail_driver' => config('mail.default'),
            'php_extensions' => $criticalExtensions,
        ];
    }

    /**
     * Get overview list of tables and sizes.
     */
    private function getDatabaseTables(): array
    {
        $dbName = config('database.connections.' . config('database.default') . '.database');
        try {
            $tables = DB::select('
                SELECT table_name, engine, table_rows, 
                       ROUND((data_length + index_length) / 1024, 2) AS size_kb
                FROM information_schema.tables 
                WHERE table_schema = ?
                ORDER BY (data_length + index_length) DESC
            ', [$dbName]);

            return array_map(function ($t) {
                return [
                    'name' => $t->TABLE_NAME ?? $t->table_name,
                    'engine' => $t->ENGINE ?? $t->engine ?? 'InnoDB',
                    'rows' => (int) ($t->TABLE_ROWS ?? $t->table_rows ?? 0),
                    'size_kb' => (float) ($t->size_kb ?? 0),
                ];
            }, $tables);
        } catch (\Throwable) {
            return [];
        }
    }

    /**
     * Format bytes into human readable format.
     */
    private function formatBytes(int|float $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
