<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\SystemSetting;
use App\Services\GoogleDriveService;

class GoogleDriveAuthController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirect()
    {
        $clientId = config('services.google.client_id');

        $params = http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => route('admin.backup.drive.callback'),
            'response_type' => 'code',
            'scope' => 'https://www.googleapis.com/auth/drive.file https://www.googleapis.com/auth/userinfo.email',
            'access_type' => 'offline',
            'prompt' => 'consent',
        ]);

        return redirect('https://accounts.google.com/o/oauth2/v2/auth?' . $params);
    }

    /**
     * Handle OAuth callback
     */
    public function callback(Request $request)
    {
        if ($request->has('error')) {
            return redirect()->route('admin.backup')->with('error', 'Gagal menghubungkan: ' . $request->get('error'));
        }

        $code = $request->get('code');

        // Exchange code for tokens
        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => route('admin.backup.drive.callback'),
        ]);

        if ($response->failed()) {
            return redirect()->route('admin.backup')->with('error', 'Gagal mendapatkan token: ' . $response->body());
        }

        $data = $response->json();
        $refreshToken = $data['refresh_token'] ?? null;
        $accessToken = $data['access_token'] ?? null;

        if (!$refreshToken) {
            return redirect()->route('admin.backup')->with('error', 'Refresh token tidak ditemukan. Coba lagi.');
        }

        // Save refresh token to database
        $driveService = new GoogleDriveService();
        $driveService->saveRefreshToken($refreshToken);

        // Get user email
        $emailResponse = Http::withToken($accessToken)
            ->get('https://www.googleapis.com/oauth2/v2/userinfo');

        if ($emailResponse->successful()) {
            $email = $emailResponse->json('email');
            SystemSetting::set('google_drive_email', $email);
        }

        return redirect()->route('admin.backup')->with('success', 'Google Drive berhasil terhubung!');
    }

    /**
     * Disconnect Google Drive
     */
    public function disconnect()
    {
        $driveService = new GoogleDriveService();
        $driveService->disconnect();

        return redirect()->route('admin.backup')->with('success', 'Google Drive berhasil diputus.');
    }
}
