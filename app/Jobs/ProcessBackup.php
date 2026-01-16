<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessBackup implements ShouldQueue
{
    use Queueable;

    protected $type;
    protected $user;
    protected $backupPath;

    /**
     * Create a new job instance.
     */
    public function __construct($type, $user)
    {
        $this->type = $type;
        $this->user = $user;
        $this->backupPath = storage_path('app/backups');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Ensure backup directory exists
        if (!\Illuminate\Support\Facades\File::exists($this->backupPath)) {
            \Illuminate\Support\Facades\File::makeDirectory($this->backupPath, 0755, true);
        }

        $timestamp = now()->setTimezone('Asia/Jakarta')->format('Y-m-d_His');
        $googleDrive = new \App\Services\GoogleDriveService();
        $uploaded = [];
        $errors = [];

        try {
            if ($this->type === 'full' || $this->type === 'database') {
                try {
                    $dbFile = $this->backupDatabase($timestamp);
                    if ($dbFile) {
                        $filePath = $this->backupPath . '/' . $dbFile;
                        if ($googleDrive->isConfigured()) {
                            $googleDrive->uploadFile($filePath, $dbFile, 'application/zip');
                            // Delete local file after upload to save space
                            if (\Illuminate\Support\Facades\File::exists($filePath)) {
                                \Illuminate\Support\Facades\File::delete($filePath);
                            }
                            $uploaded[] = $dbFile;
                        }
                    }
                } catch (\Exception $e) {
                    $errors[] = "Database: " . $e->getMessage();
                }
            }

            if ($this->type === 'full' || $this->type === 'storage') {
                try {
                    $storageFile = $this->backupStorage($timestamp);
                    if ($storageFile) {
                        $filePath = $this->backupPath . '/' . $storageFile;
                        if ($googleDrive->isConfigured()) {
                            $googleDrive->uploadFile($filePath, $storageFile, 'application/zip');
                            // Delete local file after upload
                            if (\Illuminate\Support\Facades\File::exists($filePath)) {
                                \Illuminate\Support\Facades\File::delete($filePath);
                            }
                            $uploaded[] = $storageFile;
                        }
                    }
                } catch (\Exception $e) {
                    $errors[] = "Storage: " . $e->getMessage();
                }
            }

            // Clean up any manifest files
            $manifests = \Illuminate\Support\Facades\File::glob($this->backupPath . '/*_manifest.json');
            foreach ($manifests as $manifest) {
                \Illuminate\Support\Facades\File::delete($manifest);
            }

            // Log activity
            if (!empty($uploaded)) {
                // Log success
                \App\Models\ActivityLog::create([
                    'user_id' => $this->user->id,
                    'action' => 'backup',
                    'description' => 'Backup ' . $this->type . ' berhasil via Queue: ' . implode(', ', $uploaded),
                    'ip_address' => 'System',
                    'user_agent' => 'System/Queue',
                    'meta_data' => json_encode(['type' => $this->type, 'files' => $uploaded]),
                ]);

                // Optional: Send notification to user
                // $this->user->notify(new \App\Notifications\BackupCompleted($uploaded));
            }

            if (!empty($errors)) {
                \App\Models\ActivityLog::create([
                    'user_id' => $this->user->id,
                    'action' => 'backup_failed',
                    'description' => 'Backup partially failed: ' . implode(', ', $errors),
                    'ip_address' => 'System',
                    'user_agent' => 'System/Queue',
                ]);
            }

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Backup Job Failed: ' . $e->getMessage());
        }
    }

    protected function backupDatabase($timestamp)
    {
        $filename = "database_{$timestamp}.sql";
        $filepath = $this->backupPath . '/' . $filename;

        $connection = config('database.default');
        $config = config("database.connections.{$connection}");

        if ($connection === 'mysql') {
            if (PHP_OS_FAMILY === 'Windows') {
                $command = sprintf(
                    'cmd /c "mysqldump --user=%s --password=%s --host=%s --port=%s %s 2>NUL > %s"',
                    $config['username'],
                    $config['password'],
                    $config['host'],
                    $config['port'] ?? 3306,
                    $config['database'],
                    str_replace('/', '\\', $filepath)
                );
            } else {
                $command = sprintf(
                    'mysqldump --user=%s --password=%s --host=%s --port=%s %s 2>/dev/null > %s',
                    escapeshellarg($config['username']),
                    escapeshellarg($config['password']),
                    escapeshellarg($config['host']),
                    escapeshellarg($config['port'] ?? 3306),
                    escapeshellarg($config['database']),
                    escapeshellarg($filepath)
                );
            }

            exec($command, $output, $returnVar);

            if ($returnVar !== 0 || !file_exists($filepath) || filesize($filepath) < 100) {
                return $this->backupDatabasePHP($timestamp);
            }

            $zipFilename = "database_{$timestamp}.sql.zip";
            $zipPath = $this->backupPath . '/' . $zipFilename;

            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE) === true) {
                $zip->addFile($filepath, $filename);
                $zip->close();
                unlink($filepath);
                return $zipFilename;
            }

            return $filename;
        }

        return $this->backupDatabasePHP($timestamp);
    }

    protected function backupDatabasePHP($timestamp)
    {
        $filename = "database_{$timestamp}.json";
        $filepath = $this->backupPath . '/' . $filename;

        $tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
        $dbName = config('database.connections.mysql.database');
        $key = "Tables_in_{$dbName}";

        $data = [];
        foreach ($tables as $table) {
            $tableName = $table->$key;
            $data[$tableName] = \Illuminate\Support\Facades\DB::table($tableName)->get()->toArray();
        }

        \Illuminate\Support\Facades\File::put($filepath, json_encode($data, JSON_PRETTY_PRINT));

        $zipFilename = "database_{$timestamp}.json.zip";
        $zipPath = $this->backupPath . '/' . $zipFilename;

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE) === true) {
            $zip->addFile($filepath, $filename);
            $zip->close();
            unlink($filepath);
            return $zipFilename;
        }

        return $filename;
    }

    protected function backupStorage($timestamp)
    {
        $filename = "storage_{$timestamp}.zip";
        $zipPath = $this->backupPath . '/' . $filename;

        $dirsToBackup = ['public', 'certificates', 'templates', 'invoices'];
        $basePath = storage_path('app');

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            throw new \Exception('Cannot create zip file');
        }

        $fileCount = 0;

        foreach ($dirsToBackup as $dir) {
            $fullPath = $basePath . '/' . $dir;

            if (\Illuminate\Support\Facades\File::exists($fullPath)) {
                $files = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($fullPath),
                    \RecursiveIteratorIterator::LEAVES_ONLY
                );

                foreach ($files as $file) {
                    if (!$file->isDir()) {
                        $filePath = $file->getRealPath();
                        $relativePath = substr($filePath, strlen($basePath) + 1);
                        $relativePath = str_replace('\\', '/', $relativePath);
                        $zip->addFile($filePath, $relativePath);
                        $fileCount++;
                    }
                }
            }
        }

        $zip->close();

        if ($fileCount === 0) {
            return null;
        }

        return $filename;
    }
}
