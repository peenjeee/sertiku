<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\SystemSetting;

class GoogleDriveService
{
    protected $clientId;
    protected $clientSecret;
    protected $refreshToken;
    protected $folderId;
    protected $folderName = 'Backup SertiKu';

    public function __construct()
    {
        $this->clientId = config('services.google.client_id');
        $this->clientSecret = config('services.google.client_secret');
        // Get refresh token from database ONLY (not from env)
        $this->refreshToken = SystemSetting::get('google_drive_refresh_token');
        $this->folderId = SystemSetting::get('google_drive_folder_id');
    }

    /**
     * Check if Google Drive is configured
     */
    public function isConfigured(): bool
    {
        // Must have database-stored refresh token
        $dbToken = SystemSetting::get('google_drive_refresh_token');
        return !empty($this->clientId)
            && !empty($this->clientSecret)
            && !empty($dbToken);
    }

    /**
     * Save refresh token to database
     */
    public function saveRefreshToken(string $token): void
    {
        SystemSetting::set('google_drive_refresh_token', $token);
        $this->refreshToken = $token;
        // Clear access token cache
        Cache::forget('google_drive_access_token');
    }

    /**
     * Get connected email
     */
    public function getConnectedEmail(): ?string
    {
        return SystemSetting::get('google_drive_email');
    }

    /**
     * Disconnect Google Drive
     */
    public function disconnect(): void
    {
        SystemSetting::remove('google_drive_refresh_token');
        SystemSetting::remove('google_drive_folder_id');
        SystemSetting::remove('google_drive_email');
        Cache::forget('google_drive_access_token');
        $this->refreshToken = null;
        $this->folderId = null;
    }

    /**
     * Get access token (cached)
     */
    protected function getAccessToken(): string
    {
        return Cache::remember('google_drive_access_token', 3500, function () {
            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'refresh_token' => $this->refreshToken,
                'grant_type' => 'refresh_token',
            ]);

            if ($response->failed()) {
                throw new \Exception('Failed to get Google Drive access token: ' . $response->body());
            }

            return $response->json('access_token');
        });
    }

    /**
     * Get or create backup folder
     */
    protected function getBackupFolderId(): string
    {
        // Return cached folder ID
        if ($this->folderId) {
            return $this->folderId;
        }

        $accessToken = $this->getAccessToken();

        // Search for existing folder
        $response = Http::withToken($accessToken)
            ->get('https://www.googleapis.com/drive/v3/files', [
                'q' => "name='{$this->folderName}' and mimeType='application/vnd.google-apps.folder' and trashed=false",
                'fields' => 'files(id,name)',
            ]);

        if ($response->successful()) {
            $files = $response->json('files', []);
            if (!empty($files)) {
                $this->folderId = $files[0]['id'];
                SystemSetting::set('google_drive_folder_id', $this->folderId);
                return $this->folderId;
            }
        }

        // Create new folder
        $response = Http::withToken($accessToken)
            ->post('https://www.googleapis.com/drive/v3/files', [
                'name' => $this->folderName,
                'mimeType' => 'application/vnd.google-apps.folder',
            ]);

        if ($response->failed()) {
            throw new \Exception('Failed to create backup folder: ' . $response->body());
        }

        $this->folderId = $response->json('id');
        SystemSetting::set('google_drive_folder_id', $this->folderId);

        return $this->folderId;
    }

    /**
     * Upload file to Google Drive
     */
    public function uploadFile(string $filePath, string $fileName, string $mimeType = 'application/octet-stream'): ?array
    {
        if (!$this->isConfigured()) {
            return null;
        }

        $accessToken = $this->getAccessToken();
        $folderId = $this->getBackupFolderId();
        $fileContent = file_get_contents($filePath);

        // Create file metadata
        $metadata = [
            'name' => $fileName,
            'parents' => [$folderId],
        ];

        // Multipart upload
        $boundary = 'backup_boundary_' . time();

        $body = "--{$boundary}\r\n";
        $body .= "Content-Type: application/json; charset=UTF-8\r\n\r\n";
        $body .= json_encode($metadata) . "\r\n";
        $body .= "--{$boundary}\r\n";
        $body .= "Content-Type: {$mimeType}\r\n\r\n";
        $body .= $fileContent . "\r\n";
        $body .= "--{$boundary}--";

        $response = Http::withToken($accessToken)
            ->withHeaders([
                'Content-Type' => "multipart/related; boundary={$boundary}",
            ])
            ->withBody($body, "multipart/related; boundary={$boundary}")
            ->post('https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart');

        if ($response->failed()) {
            throw new \Exception('Failed to upload to Google Drive: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * List backup files from Google Drive
     */
    public function listBackups(): array
    {
        if (!$this->isConfigured()) {
            return [];
        }

        $accessToken = $this->getAccessToken();
        $folderId = $this->getBackupFolderId();

        $response = Http::withToken($accessToken)
            ->get('https://www.googleapis.com/drive/v3/files', [
                'q' => "'{$folderId}' in parents and trashed = false",
                'fields' => 'files(id,name,size,createdTime,mimeType)',
                'orderBy' => 'createdTime desc',
                'pageSize' => 50,
            ]);

        if ($response->failed()) {
            throw new \Exception('Failed to list Google Drive files: ' . $response->body());
        }

        return $response->json('files', []);
    }

    /**
     * Download file from Google Drive
     */
    public function downloadFile(string $fileId): ?string
    {
        if (!$this->isConfigured()) {
            return null;
        }

        $accessToken = $this->getAccessToken();

        $response = Http::withToken($accessToken)
            ->get("https://www.googleapis.com/drive/v3/files/{$fileId}?alt=media");

        if ($response->failed()) {
            throw new \Exception('Failed to download from Google Drive: ' . $response->body());
        }

        // Save to temp file
        $tempPath = storage_path('app/temp_drive_download_' . time());
        file_put_contents($tempPath, $response->body());

        return $tempPath;
    }

    /**
     * Delete file from Google Drive
     */
    public function deleteFile(string $fileId): bool
    {
        if (!$this->isConfigured()) {
            return false;
        }

        $accessToken = $this->getAccessToken();

        $response = Http::withToken($accessToken)
            ->delete("https://www.googleapis.com/drive/v3/files/{$fileId}");

        return $response->successful();
    }

    /**
     * Get file info
     */
    public function getFileInfo(string $fileId): ?array
    {
        if (!$this->isConfigured()) {
            return null;
        }

        $accessToken = $this->getAccessToken();

        $response = Http::withToken($accessToken)
            ->get("https://www.googleapis.com/drive/v3/files/{$fileId}", [
                'fields' => 'id,name,size,createdTime,mimeType',
            ]);

        if ($response->failed()) {
            return null;
        }

        return $response->json();
    }
}
