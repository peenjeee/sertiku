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

    public $timeout = 0; // Unlimited timeout for backup process

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
        // Increase limits for background process (Best Practice for heavy jobs)
        ini_set('memory_limit', '2048M');
        set_time_limit(0); // Unlimited

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
                    \Illuminate\Support\Facades\Log::error("BackupJob: DB Error: " . $e->getMessage());
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
                    \Illuminate\Support\Facades\Log::error("BackupJob: Storage Error: " . $e->getMessage());
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

            // BEST PRACTICE: Try system zip first for compression
            if (PHP_OS_FAMILY !== 'Windows') {
                $zipCheck = shell_exec('which zip');
                if (!empty($zipCheck)) {
                    // -q: quiet, -j: junk paths (store just filename)
                    $cmd = "zip -q -j " . escapeshellarg($zipPath) . " " . escapeshellarg($filepath);
                    exec($cmd, $out, $ret);

                    if ($ret === 0 && file_exists($zipPath)) {
                        unlink($filepath); // Remove raw SQL
                        return $zipFilename;
                    }
                }
            }

            // Fallback to PHP ZipArchive
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

        // Increase limits for this job
        ini_set('memory_limit', '2048M');
        set_time_limit(3600);

        $dirsToBackup = ['public', 'certificates', 'templates', 'invoices'];
        $basePath = storage_path('app');

        \Illuminate\Support\Facades\Log::info("BackupStorage: Starting backup for {$filename}");

        // Try using system zip command on Linux/Unix (Much faster & memory efficient)
        if (PHP_OS_FAMILY !== 'Windows') {
            try {
                // Check if zip command exists
                $zipCheck = shell_exec('which zip');

                if (!empty($zipCheck)) {
                    \Illuminate\Support\Facades\Log::info("BackupStorage: Using system zip command.");

                    // Build command: cd to app root, then zip specific folders
                    // -r: recursive
                    // -q: quiet
                    $foldersToZip = implode(' ', $dirsToBackup);

                    // Verify folders exist before adding to command to avoid warnings
                    $existingFolders = [];
                    foreach ($dirsToBackup as $dir) {
                        if (is_dir($basePath . '/' . $dir)) {
                            $existingFolders[] = $dir;
                        }
                    }

                    if (empty($existingFolders)) {
                        \Illuminate\Support\Facades\Log::warning("BackupStorage: No directories found to backup.");
                        return null;
                    }

                    $foldersStr = implode(' ', array_map('escapeshellarg', $existingFolders));
                    $zipPathEscaped = escapeshellarg($zipPath);
                    $basePathEscaped = escapeshellarg($basePath);

                    $command = "cd {$basePathEscaped} && zip -r -q {$zipPathEscaped} {$foldersStr}";

                    \Illuminate\Support\Facades\Log::info("BackupStorage: Executing: {$command}");

                    exec($command, $output, $returnVar);

                    if ($returnVar === 0 && file_exists($zipPath) && filesize($zipPath) > 0) {
                        \Illuminate\Support\Facades\Log::info("BackupStorage: System zip successful.");
                        return $filename;
                    } else {
                        \Illuminate\Support\Facades\Log::warning("BackupStorage: System zip failed (Code: {$returnVar}). Falling back to PHP ZipArchive.");
                    }
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("BackupStorage: System zip exception: " . $e->getMessage());
            }
        }

        // Fallback to PHP ZipArchive (Standard method) - with Chunking for memory safety
        \Illuminate\Support\Facades\Log::info("BackupStorage: Using PHP ZipArchive with Chunking.");

        // Initialize Zip
        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            \Illuminate\Support\Facades\Log::error("BackupStorage: Failed to create zip at {$zipPath}");
            throw new \Exception('Cannot create zip file at ' . $zipPath);
        }

        $fileCount = 0;
        $chunkSize = 500; // Close and re-open zip every 500 files to flush memory buffer

        foreach ($dirsToBackup as $dir) {
            $fullPath = $basePath . '/' . $dir;
            \Illuminate\Support\Facades\Log::info("BackupStorage: Checking directory {$fullPath}");

            if (\Illuminate\Support\Facades\File::exists($fullPath)) {
                $files = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($fullPath, \RecursiveDirectoryIterator::SKIP_DOTS),
                    \RecursiveIteratorIterator::LEAVES_ONLY
                );

                foreach ($files as $file) {
                    if (!$file->isDir()) {
                        $filePath = $file->getRealPath();

                        // Normalize paths
                        $normalizedBase = str_replace('\\', '/', $basePath);
                        $normalizedFile = str_replace('\\', '/', $filePath);

                        if (strpos($normalizedFile, $normalizedBase) === 0) {
                            $relativePath = substr($normalizedFile, strlen($normalizedBase) + 1);
                        } else {
                            $relativePath = $dir . '/' . $file->getFilename();
                        }

                        $zip->addFile($filePath, $relativePath);
                        $fileCount++;

                        // Chunking Logic
                        if ($fileCount % $chunkSize === 0) {
                            if (!$zip->close()) {
                                throw new \Exception('Failed to close zip during chunking.');
                            }
                            // Re-open
                            if ($zip->open($zipPath, \ZipArchive::CREATE) !== true) {
                                throw new \Exception('Failed to re-open zip during chunking.');
                            }
                            // Force garbage collection
                            gc_collect_cycles();
                        }
                    }
                }
            } else {
                \Illuminate\Support\Facades\Log::warning("BackupStorage: Directory not found: {$fullPath}");
            }
        }

        \Illuminate\Support\Facades\Log::info("BackupStorage: Total files added: {$fileCount}");

        if (!$zip->close()) {
            \Illuminate\Support\Facades\Log::error("BackupStorage: Failed to close/save zip file.");
            throw new \Exception('Failed to save zip file.');
        }

        if ($fileCount === 0) {
            \Illuminate\Support\Facades\Log::warning("BackupStorage: No files found to backup.");
            return null;
        }

        if (!file_exists($zipPath) || filesize($zipPath) < 10) {
            \Illuminate\Support\Facades\Log::error("BackupStorage: Zip file is missing or empty after close.");
            return null;
        }

        return $filename;
    }
}
