<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Services\GoogleDriveService;
use App\Models\ActivityLog;
use ZipArchive;

class BackupController extends Controller
{
    protected $backupPath;
    protected $googleDrive;

    public function __construct()
    {
        $this->backupPath = storage_path('app/backups');
        $this->googleDrive = new GoogleDriveService();

        if (!File::exists($this->backupPath)) {
            File::makeDirectory($this->backupPath, 0755, true);
        }
    }

    /**
     * Display backup & restore page
     */
    public function index()
    {
        // Get storage stats
        $storageStats = $this->getStorageStats();

        // Get Google Drive backups
        $driveBackups = [];
        $driveConfigured = $this->googleDrive->isConfigured();
        $driveEmail = $this->googleDrive->getConnectedEmail();
        $driveError = null;

        if ($driveConfigured) {
            try {
                $driveBackups = $this->googleDrive->listBackups();
            } catch (\Exception $e) {
                $driveError = $e->getMessage();
            }
        }

        return view('admin.backup', compact('storageStats', 'driveBackups', 'driveConfigured', 'driveEmail', 'driveError'));
    }

    /**
     * Create full backup (database + storage)
     */
    public function createBackup(Request $request)
    {
        $type = $request->get('type', 'full'); // full, database, storage
        $timestamp = now()->setTimezone('Asia/Jakarta')->format('Y-m-d_His');

        // Check if Google Drive is configured
        if (!$this->googleDrive->isConfigured()) {
            return back()->with('error', 'Google Drive belum dikonfigurasi. Klik tombol "Hubungkan Google Drive" terlebih dahulu.');
        }

        try {
            $uploaded = [];

            if ($type === 'full' || $type === 'database') {
                $dbFile = $this->backupDatabase($timestamp);
                if ($dbFile) {
                    $filePath = $this->backupPath . '/' . $dbFile;
                    $this->googleDrive->uploadFile($filePath, $dbFile, 'application/zip');
                    // Delete local file after upload
                    if (File::exists($filePath)) {
                        File::delete($filePath);
                    }
                    $uploaded[] = $dbFile;
                }
            }

            if ($type === 'full' || $type === 'storage') {
                $storageFile = $this->backupStorage($timestamp);
                if ($storageFile) {
                    $filePath = $this->backupPath . '/' . $storageFile;
                    $this->googleDrive->uploadFile($filePath, $storageFile, 'application/zip');
                    // Delete local file after upload
                    if (File::exists($filePath)) {
                        File::delete($filePath);
                    }
                    $uploaded[] = $storageFile;
                }
            }

            // Clean up any manifest files
            $manifests = File::glob($this->backupPath . '/*_manifest.json');
            foreach ($manifests as $manifest) {
                File::delete($manifest);
            }

            // Log activity
            ActivityLog::log('backup', 'Backup ' . $type . ' berhasil: ' . implode(', ', $uploaded), null, [
                'type' => $type,
                'files' => $uploaded,
            ]);

            return back()->with('success', 'Backup berhasil diupload ke Google Drive! File: ' . implode(', ', $uploaded));

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat backup: ' . $e->getMessage());
        }
    }

    /**
     * Backup database to SQL file
     */
    protected function backupDatabase($timestamp)
    {
        $filename = "database_{$timestamp}.sql";
        $filepath = $this->backupPath . '/' . $filename;

        $connection = config('database.default');
        $config = config("database.connections.{$connection}");

        if ($connection === 'mysql') {
            // Use mysqldump for MySQL - redirect stderr to null to avoid warnings in SQL file
            if (PHP_OS_FAMILY === 'Windows') {
                // Use cmd /c because PowerShell doesn't support > redirect properly
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

            // Check if file was created and has content
            if ($returnVar !== 0 || !file_exists($filepath) || filesize($filepath) < 100) {
                // Fallback: export using PHP
                return $this->backupDatabasePHP($timestamp);
            }

            // Compress the SQL file
            $zipFilename = "database_{$timestamp}.sql.zip";
            $zipPath = $this->backupPath . '/' . $zipFilename;

            $zip = new ZipArchive();
            if ($zip->open($zipPath, ZipArchive::CREATE) === true) {
                $zip->addFile($filepath, $filename);
                $zip->close();
                unlink($filepath); // Remove uncompressed file
                return $zipFilename;
            }

            return $filename;
        }

        // Fallback for SQLite or other databases
        return $this->backupDatabasePHP($timestamp);
    }

    /**
     * Backup database using PHP (fallback method)
     */
    protected function backupDatabasePHP($timestamp)
    {
        $filename = "database_{$timestamp}.json";
        $filepath = $this->backupPath . '/' . $filename;

        $tables = DB::select('SHOW TABLES');
        $dbName = config('database.connections.mysql.database');
        $key = "Tables_in_{$dbName}";

        $data = [];
        foreach ($tables as $table) {
            $tableName = $table->$key;
            $data[$tableName] = DB::table($tableName)->get()->toArray();
        }

        File::put($filepath, json_encode($data, JSON_PRETTY_PRINT));

        // Compress
        $zipFilename = "database_{$timestamp}.json.zip";
        $zipPath = $this->backupPath . '/' . $zipFilename;

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) === true) {
            $zip->addFile($filepath, $filename);
            $zip->close();
            unlink($filepath);
            return $zipFilename;
        }

        return $filename;
    }

    /**
     * Backup storage folder
     */
    protected function backupStorage($timestamp)
    {
        $filename = "storage_{$timestamp}.zip";
        $zipPath = $this->backupPath . '/' . $filename;

        // Use the actual storage path (where files are stored by Laravel)
        $storagePath = storage_path('app/public');

        if (!File::exists($storagePath)) {
            return null;
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \Exception('Cannot create zip file');
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($storagePath),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($storagePath) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();

        return $filename;
    }

    /**
     * Download backup file
     */
    public function download($filename)
    {
        $filepath = $this->backupPath . '/' . $filename;

        if (!File::exists($filepath)) {
            abort(404, 'Backup file not found');
        }

        return response()->download($filepath);
    }

    /**
     * Delete backup file
     */
    public function delete($filename)
    {
        $filepath = $this->backupPath . '/' . $filename;

        if (File::exists($filepath)) {
            File::delete($filepath);

            // Also delete related files (manifest, etc)
            $baseName = preg_replace('/\.(sql|json|zip).*$/', '', $filename);
            $relatedFiles = File::glob($this->backupPath . '/' . $baseName . '*');
            foreach ($relatedFiles as $file) {
                File::delete($file);
            }
        }

        return back()->with('success', 'Backup berhasil dihapus.');
    }

    /**
     * Restore from backup
     */
    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:zip,sql,json',
        ]);

        $file = $request->file('backup_file');
        $extension = $file->getClientOriginalExtension();
        $filename = $file->getClientOriginalName();

        try {
            if ($extension === 'zip') {
                return $this->restoreFromZip($file, $filename);
            } elseif ($extension === 'sql') {
                return $this->restoreDatabase($file->getPathname());
            } elseif ($extension === 'json') {
                return $this->restoreDatabaseJSON($file->getPathname());
            }

            return back()->with('error', 'Format file tidak didukung.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal restore: ' . $e->getMessage());
        }
    }

    /**
     * Restore from ZIP file
     */
    protected function restoreFromZip($file, $filename)
    {
        $zip = new ZipArchive();
        $zipPath = $file->getPathname();

        if ($zip->open($zipPath) !== true) {
            throw new \Exception('Cannot open zip file');
        }

        $restored = [];

        // Check if this is a storage backup (by filename)
        if (str_contains($filename, 'storage_')) {
            // Use the actual storage path (where files are stored by Laravel)
            $storagePath = storage_path('app/public');

            // Create directory if not exists
            if (!File::exists($storagePath)) {
                File::makeDirectory($storagePath, 0755, true);
            }

            // Extract directly to storage/app/public folder
            $zip->extractTo($storagePath);
            $restoredFiles = $zip->numFiles;
            $zip->close();

            $restored[] = "storage ({$restoredFiles} files)";
        } else {
            // For database backup, extract to temp folder first
            $tempPath = storage_path('app/temp_restore');

            if (!File::exists($tempPath)) {
                File::makeDirectory($tempPath, 0755, true);
            }

            $zip->extractTo($tempPath);
            $zip->close();

            // Check for database backup files
            $sqlFiles = File::glob($tempPath . '/*.sql');
            $jsonFiles = File::glob($tempPath . '/*.json');

            if (!empty($sqlFiles)) {
                $this->restoreDatabase($sqlFiles[0]);
                $restored[] = 'database (SQL)';
            } elseif (!empty($jsonFiles)) {
                $this->restoreDatabaseJSON($jsonFiles[0]);
                $restored[] = 'database (JSON)';
            }

            // Clean up temp folder
            File::deleteDirectory($tempPath);
        }

        if (empty($restored)) {
            return back()->with('warning', 'Backup file tidak berisi data yang dapat di-restore.');
        }

        return back()->with('success', 'Restore berhasil: ' . implode(', ', $restored));
    }

    /**
     * Restore database from SQL file
     */
    protected function restoreDatabase($filepath)
    {
        $sql = File::get($filepath);

        if (empty($sql)) {
            throw new \Exception('SQL file is empty');
        }

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            // Backup admin/superadmin users before restore
            $adminUsers = [];
            try {
                $adminUsers = DB::table('users')
                    ->whereIn('role', ['admin', 'superadmin'])
                    ->get()
                    ->map(fn($u) => (array) $u)
                    ->toArray();
            } catch (\Exception $e) {
                // Table might not exist yet
            }

            // Tables to completely exclude (never touch - for Laravel session/cache)
            $excludeTables = ['sessions', 'cache', 'cache_locks', 'jobs', 'failed_jobs', 'system_settings'];

            // Drop all existing tables first (except excluded) 
            $tables = DB::select('SHOW TABLES');
            $dbName = config('database.connections.mysql.database');
            $tableKey = "Tables_in_{$dbName}";

            foreach ($tables as $table) {
                $tableName = $table->$tableKey ?? array_values((array) $table)[0];
                if (!in_array($tableName, $excludeTables)) {
                    DB::statement("DROP TABLE IF EXISTS `{$tableName}`");
                }
            }

            // Execute all SQL statements
            DB::unprepared($sql);

            // Re-insert admin users (they were backed up before restore)
            if (!empty($adminUsers)) {
                foreach ($adminUsers as $admin) {
                    // Check if user with same email already exists
                    $exists = DB::table('users')->where('email', $admin['email'])->exists();
                    if (!$exists) {
                        DB::table('users')->insert($admin);
                    }
                }
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            // Run migrations to ensure all tables exist
            \Artisan::call('migrate', ['--force' => true]);

            return true;

        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            throw new \Exception('MySQL restore failed: ' . $e->getMessage());
        }
    }

    /**
     * Restore database from JSON file
     */
    protected function restoreDatabaseJSON($filepath)
    {
        $data = json_decode(File::get($filepath), true);

        if (!$data) {
            throw new \Exception('Invalid JSON file');
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ($data as $table => $rows) {
            // Truncate table first
            DB::table($table)->truncate();

            // Insert data in chunks
            $chunks = array_chunk($rows, 100);
            foreach ($chunks as $chunk) {
                DB::table($table)->insert($chunk);
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        return true;
    }

    /**
     * Get list of backup files
     */
    protected function getBackupList()
    {
        $backups = [];

        if (!File::exists($this->backupPath)) {
            return $backups;
        }

        $files = File::files($this->backupPath);

        foreach ($files as $file) {
            $filename = $file->getFilename();

            // Skip manifest files in the list
            if (str_contains($filename, '_manifest.json')) {
                continue;
            }

            // Determine type
            $type = 'unknown';
            if (str_contains($filename, 'database_')) {
                $type = 'database';
            } elseif (str_contains($filename, 'storage_')) {
                $type = 'storage';
            }

            $backups[] = [
                'name' => $filename,
                'type' => $type,
                'size' => $this->formatBytes($file->getSize()),
                'size_bytes' => $file->getSize(),
                'date' => \Carbon\Carbon::createFromTimestamp($file->getMTime())->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'timestamp' => $file->getMTime(),
            ];
        }

        // Sort by date descending
        usort($backups, fn($a, $b) => $b['timestamp'] - $a['timestamp']);

        return $backups;
    }

    /**
     * Get storage statistics
     */
    protected function getStorageStats()
    {
        // Use the actual storage path (consistent with backup/restore)
        $storagePath = storage_path('app/public');

        $totalSize = 0;
        $fileCount = 0;

        if (File::exists($storagePath)) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($storagePath)
            );

            foreach ($files as $file) {
                if ($file->isFile()) {
                    $totalSize += $file->getSize();
                    $fileCount++;
                }
            }
        }

        // Get database size
        $dbSize = 0;
        try {
            $result = DB::select("SELECT table_schema AS 'database', 
                SUM(data_length + index_length) AS 'size' 
                FROM information_schema.TABLES 
                WHERE table_schema = ?
                GROUP BY table_schema", [config('database.connections.mysql.database')]);

            if (!empty($result)) {
                $dbSize = $result[0]->size;
            }
        } catch (\Exception $e) {
            // Ignore
        }

        return [
            'storage_size' => $this->formatBytes($totalSize),
            'storage_files' => $fileCount,
            'database_size' => $this->formatBytes($dbSize),
            'total_tables' => count(DB::select('SHOW TABLES')),
        ];
    }

    /**
     * Format bytes to human readable
     */
    protected function formatBytes($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' bytes';
    }

    /**
     * Restore backup from Google Drive
     */
    public function restoreFromDrive(Request $request)
    {
        $request->validate([
            'file_id' => 'required|string',
            'file_name' => 'required|string',
        ]);

        if (!$this->googleDrive->isConfigured()) {
            return back()->with('error', 'Google Drive tidak dikonfigurasi.');
        }

        try {
            $fileId = $request->input('file_id');
            $fileName = $request->input('file_name');

            // Download file from Google Drive
            $tempPath = $this->googleDrive->downloadFile($fileId);

            if (!$tempPath || !file_exists($tempPath)) {
                throw new \Exception('Gagal download file dari Google Drive');
            }

            // Determine file type and restore
            $extension = pathinfo($fileName, PATHINFO_EXTENSION);

            if ($extension === 'zip') {
                $result = $this->restoreFromZipPath($tempPath, $fileName);
            } elseif ($extension === 'sql') {
                $this->restoreDatabase($tempPath);
                $result = 'database (SQL)';
            } elseif ($extension === 'json') {
                $this->restoreDatabaseJSON($tempPath);
                $result = 'database (JSON)';
            } else {
                throw new \Exception('Format file tidak didukung: ' . $extension);
            }

            // Clean up temp file
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }

            // Log activity
            ActivityLog::log('restore', 'Restore dari Google Drive berhasil: ' . $fileName, null, [
                'file_id' => $fileId,
                'file_name' => $fileName,
            ]);

            return back()->with('success', 'Restore dari Google Drive berhasil: ' . $result);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal restore dari Google Drive: ' . $e->getMessage());
        }
    }

    /**
     * Restore from ZIP file path
     */
    protected function restoreFromZipPath($zipPath, $filename)
    {
        $zip = new ZipArchive();

        if ($zip->open($zipPath) !== true) {
            throw new \Exception('Cannot open zip file');
        }

        $restored = [];

        // Check if this is a storage backup (by filename)
        if (str_contains($filename, 'storage_')) {
            // Use the actual storage path (where files are stored by Laravel)
            $storagePath = storage_path('app/public');

            // Create directory if not exists
            if (!File::exists($storagePath)) {
                File::makeDirectory($storagePath, 0755, true);
            }

            // Extract directly to storage/app/public folder
            $zip->extractTo($storagePath);
            $restoredFiles = $zip->numFiles;
            $zip->close();

            $restored[] = "storage ({$restoredFiles} files)";
        } else {
            // For database backup, extract to temp folder first
            $tempPath = storage_path('app/temp_restore');

            if (!File::exists($tempPath)) {
                File::makeDirectory($tempPath, 0755, true);
            }

            $zip->extractTo($tempPath);
            $zip->close();

            // Check for database backup files
            $sqlFiles = File::glob($tempPath . '/*.sql');
            $jsonFiles = File::glob($tempPath . '/*.json');

            if (!empty($sqlFiles)) {
                $this->restoreDatabase($sqlFiles[0]);
                $restored[] = 'database (SQL)';
            } elseif (!empty($jsonFiles)) {
                $this->restoreDatabaseJSON($jsonFiles[0]);
                $restored[] = 'database (JSON)';
            }

            // Clean up temp folder
            File::deleteDirectory($tempPath);
        }

        return implode(', ', $restored);
    }

    /**
     * Delete backup from Google Drive
     */
    public function deleteFromDrive(Request $request)
    {
        $request->validate([
            'file_id' => 'required|string',
        ]);

        if (!$this->googleDrive->isConfigured()) {
            return back()->with('error', 'Google Drive tidak dikonfigurasi.');
        }

        try {
            $fileId = $request->input('file_id');
            $this->googleDrive->deleteFile($fileId);

            return back()->with('success', 'Backup berhasil dihapus dari Google Drive.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus dari Google Drive: ' . $e->getMessage());
        }
    }
}
