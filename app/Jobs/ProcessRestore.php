<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessRestore implements ShouldQueue
{
    use Queueable;

    protected $fileId;
    protected $fileName;
    protected $user;

    public $timeout = 0; // Unlimited timeout for restore process

    /**
     * Create a new job instance.
     */
    public function __construct($fileId, $fileName, $user)
    {
        $this->fileId = $fileId;
        $this->fileName = $fileName;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Increase limits
        ini_set('memory_limit', '2048M');
        set_time_limit(0); // Unlimited

        $googleDrive = new \App\Services\GoogleDriveService();
        $restoreController = new \App\Http\Controllers\BackupController(); // Hacky but works to reuse protected logic if we make it public or duplicate it.
        // Actually, better to duplicate the restore logic here or move it to a Service. 
        // For now, to minimize risk, I will copy the logic since BackupController methods are protected.
        // Or better, I will instantiate BackupController and use reflection or simple code duplication for stability.
        // Let's duplicate to ensure the Job is self-contained and doesn't rely on Controller state.

        try {
            // Download file
            $tempPath = $googleDrive->downloadFile($this->fileId);

            if (!$tempPath || !file_exists($tempPath)) {
                throw new \Exception('Gagal download file dari Google Drive');
            }

            $extension = pathinfo($this->fileName, PATHINFO_EXTENSION);
            $result = '';

            if ($extension === 'zip') {
                $result = $this->restoreFromZipPath($tempPath, $this->fileName);
            } elseif ($extension === 'sql') {
                $this->restoreDatabase($tempPath);
                $result = 'database (SQL)';
            } elseif ($extension === 'json') {
                $this->restoreDatabaseJSON($tempPath);
                $result = 'database (JSON)';
            } else {
                throw new \Exception('Format file tidak didukung: ' . $extension);
            }

            // Cleanup
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }

            // Log Success
            \App\Models\ActivityLog::create([
                'user_id' => $this->user->id,
                'action' => 'restore',
                'description' => 'Restore dari Google Drive berhasil via Queue: ' . $this->fileName . ' (' . $result . ')',
                'ip_address' => 'System',
                'user_agent' => 'System/Queue',
                'meta_data' => json_encode(['file_id' => $this->fileId, 'file_name' => $this->fileName]),
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Restore Job Failed: " . $e->getMessage());

            \App\Models\ActivityLog::create([
                'user_id' => $this->user->id,
                'action' => 'restore_failed',
                'description' => 'Restore failed: ' . $e->getMessage(),
                'ip_address' => 'System',
                'user_agent' => 'System/Queue',
            ]);
        }
    }

    // --- COPIED & ADAPTED RESTORE LOGIC ---

    protected function restoreFromZipPath($zipPath, $filename)
    {
        $zip = new \ZipArchive();

        if ($zip->open($zipPath) !== true) {
            throw new \Exception('Cannot open zip file');
        }

        $restored = [];

        // Check if this is a storage backup (by filename)
        if (str_contains($filename, 'storage_')) {
            // Determine if this is a legacy backup
            $isLegacy = true;

            for ($i = 0; $i < min($zip->numFiles, 10); $i++) {
                $stat = $zip->statIndex($i);
                $name = $stat['name'];

                if (
                    str_starts_with($name, 'public/') ||
                    str_starts_with($name, 'certificates/') ||
                    str_starts_with($name, 'templates/') ||
                    str_starts_with($name, 'invoices/')
                ) {
                    $isLegacy = false;
                    break;
                }
            }

            if ($isLegacy) {
                $targetPath = storage_path('app/public');
            } else {
                $targetPath = storage_path('app');
            }

            if (!\Illuminate\Support\Facades\File::exists($targetPath)) {
                \Illuminate\Support\Facades\File::makeDirectory($targetPath, 0755, true);
            }

            $zip->extractTo($targetPath);
            $restoredFiles = $zip->numFiles;
            $zip->close();

            $restored[] = "storage ({$restoredFiles} files)";
        } else {
            // Database backup
            $tempPath = storage_path('app/temp_restore_' . uniqid());

            if (!\Illuminate\Support\Facades\File::exists($tempPath)) {
                \Illuminate\Support\Facades\File::makeDirectory($tempPath, 0755, true);
            }

            $zip->extractTo($tempPath);
            $zip->close();

            $sqlFiles = \Illuminate\Support\Facades\File::glob($tempPath . '/*.sql');
            $jsonFiles = \Illuminate\Support\Facades\File::glob($tempPath . '/*.json');

            if (!empty($sqlFiles)) {
                $this->restoreDatabase($sqlFiles[0]);
                $restored[] = 'database (SQL)';
            } elseif (!empty($jsonFiles)) {
                $this->restoreDatabaseJSON($jsonFiles[0]);
                $restored[] = 'database (JSON)';
            }

            \Illuminate\Support\Facades\File::deleteDirectory($tempPath);
        }

        return implode(', ', $restored);
    }

    protected function restoreDatabase($filepath)
    {
        $sql = \Illuminate\Support\Facades\File::get($filepath);

        if (empty($sql)) {
            throw new \Exception('SQL file is empty');
        }

        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            // Backup admin/superadmin users
            $adminUsers = [];
            try {
                $adminUsers = \Illuminate\Support\Facades\DB::table('users')
                    ->whereIn('role', ['admin', 'superadmin'])
                    ->get()
                    ->map(fn($u) => (array) $u)
                    ->toArray();
            } catch (\Exception $e) {
            }

            $excludeTables = ['sessions', 'cache', 'cache_locks', 'jobs', 'failed_jobs', 'system_settings'];

            $tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
            $dbName = config('database.connections.mysql.database');
            $tableKey = "Tables_in_{$dbName}";

            foreach ($tables as $table) {
                $tableName = $table->$tableKey ?? array_values((array) $table)[0];
                if (!in_array($tableName, $excludeTables)) {
                    \Illuminate\Support\Facades\DB::statement("DROP TABLE IF EXISTS `{$tableName}`");
                }
            }

            \Illuminate\Support\Facades\DB::unprepared($sql);

            if (!empty($adminUsers)) {
                foreach ($adminUsers as $admin) {
                    $exists = \Illuminate\Support\Facades\DB::table('users')->where('email', $admin['email'])->exists();
                    if (!$exists) {
                        \Illuminate\Support\Facades\DB::table('users')->insert($admin);
                    }
                }
            }

            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1');
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);

            // Truncate jobs tables to prevent "zombie" jobs from the backup
            try {
                \Illuminate\Support\Facades\DB::table('jobs')->truncate();
                \Illuminate\Support\Facades\DB::table('failed_jobs')->truncate();
            } catch (\Exception $e) {
                // Ignore
            }

            return true;

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1');
            throw new \Exception('MySQL restore failed: ' . $e->getMessage());
        }
    }

    protected function restoreDatabaseJSON($filepath)
    {
        $data = json_decode(\Illuminate\Support\Facades\File::get($filepath), true);

        if (!$data) {
            throw new \Exception('Invalid JSON file');
        }

        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ($data as $table => $rows) {
            \Illuminate\Support\Facades\DB::table($table)->truncate();
            $chunks = array_chunk($rows, 100);
            foreach ($chunks as $chunk) {
                \Illuminate\Support\Facades\DB::table($table)->insert($chunk);
            }
        }

        // Truncate jobs tables to prevent "zombie" jobs from the backup
        try {
            \Illuminate\Support\Facades\DB::table('jobs')->truncate();
            \Illuminate\Support\Facades\DB::table('failed_jobs')->truncate();
        } catch (\Exception $e) {
            // Ignore if tables don't exist
        }

        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1');

        return true;
    }
}
