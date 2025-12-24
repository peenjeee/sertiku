<?php
namespace App\Services;

use App\Models\Certificate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

class IpfsService
{
    protected bool $enabled;
    protected string $scriptsPath;
    protected string $gatewayUrl;

    public function __construct()
    {
        $this->enabled     = config('ipfs.enabled', false);
        $this->scriptsPath = base_path('scripts');
        $this->gatewayUrl  = config('ipfs.gateway_url', 'https://w3s.link/ipfs');
    }

    /**
     * Check if IPFS/Storacha is enabled
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * Get the gateway URL for a CID
     */
    public function getGatewayUrl(string $cid): string
    {
        return rtrim($this->gatewayUrl, '/') . '/' . $cid;
    }

    /**
     * Upload a file to IPFS via Storacha Node script
     */
    public function uploadFile(string $filePath, string $fileName): ?array
    {
        if (! $this->isEnabled()) {
            Log::warning('IpfsService: IPFS not enabled');
            return null;
        }

        if (! file_exists($filePath)) {
            Log::error('IpfsService: File not found: ' . $filePath);
            return null;
        }

        try {
            $result = Process::path($this->scriptsPath)
                ->timeout(120)
                ->run(['node', 'storacha_upload.js', $filePath]);

            if ($result->successful()) {
                $output = json_decode(trim($result->output()), true);

                if ($output && $output['success']) {
                    Log::info("IpfsService: File uploaded to Storacha. CID: {$output['cid']}");
                    return [
                        'success' => true,
                        'cid'     => $output['cid'],
                        'url'     => $output['url'],
                        'name'    => $fileName,
                    ];
                }
            }

            Log::error('IpfsService: Upload failed. Output: ' . $result->output() . ' Error: ' . $result->errorOutput());
            return null;

        } catch (\Exception $e) {
            Log::error('IpfsService: Upload error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Upload JSON metadata to IPFS via Storacha
     */
    public function uploadJson(array $data, string $name): ?array
    {
        if (! $this->isEnabled()) {
            return null;
        }

        try {
            $jsonString = json_encode($data, JSON_UNESCAPED_UNICODE);

            // Create temp file with JSON data
            $tempFile = tempnam(sys_get_temp_dir(), 'storacha_');
            $jsonFile = $tempFile . '.json';
            rename($tempFile, $jsonFile);
            file_put_contents($jsonFile, $jsonString);

            try {
                $result = Process::path($this->scriptsPath)
                    ->timeout(60)
                    ->run(['node', 'storacha_upload.js', $jsonFile]);

                if ($result->successful()) {
                    $output = json_decode(trim($result->output()), true);

                    if ($output && $output['success']) {
                        Log::info("IpfsService: JSON uploaded to Storacha. CID: {$output['cid']}");
                        return [
                            'success' => true,
                            'cid'     => $output['cid'],
                            'url'     => $output['url'],
                            'name'    => $name,
                        ];
                    }
                }

                Log::error('IpfsService: JSON upload failed. Output: ' . $result->output());
                return null;

            } finally {
                // Clean up temp file
                if (file_exists($jsonFile)) {
                    unlink($jsonFile);
                }
            }

        } catch (\Exception $e) {
            Log::error('IpfsService: JSON upload error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Upload certificate metadata to IPFS
     */
    public function uploadCertificateMetadata(Certificate $certificate): ?array
    {
        $metadata = [
            'name'               => 'Certificate: ' . $certificate->certificate_number,
            'description'        => 'Digital certificate issued by SertiKu - Stored on IPFS via Storacha',
            'certificate_number' => $certificate->certificate_number,
            'recipient_name'     => $certificate->recipient_name,
            'course_name'        => $certificate->course_name,
            'issue_date'         => $certificate->issue_date?->format('Y-m-d'),
            'expire_date'        => $certificate->expire_date?->format('Y-m-d'),
            'issuer'             => $certificate->user?->institution_name ?? $certificate->user?->name,
            'verification_url'   => $certificate->verification_url,
            'blockchain_hash'    => $certificate->blockchain_hash,
            'blockchain_tx_hash' => $certificate->blockchain_tx_hash,
            'issued_at'          => $certificate->created_at?->toIso8601String(),
            'storage'            => [
                'provider' => 'Storacha Network',
                'network'  => 'IPFS + Filecoin',
            ],
        ];

        return $this->uploadJson($metadata, 'cert-' . $certificate->certificate_number);
    }

    /**
     * Get IPFS service info for dashboard
     */
    public function getInfo(): array
    {
        return [
            'enabled'     => $this->isEnabled(),
            'provider'    => 'Storacha (IPFS + Filecoin)',
            'gateway_url' => $this->gatewayUrl,
        ];
    }
}
