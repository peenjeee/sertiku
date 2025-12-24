<?php
namespace App\Services;

use App\Models\Certificate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IpfsService
{
    protected bool $enabled;
    protected string $pinataJwt;
    protected string $gatewayUrl;

    public function __construct()
    {
        $this->enabled = config('ipfs.enabled', false);
        $this->pinataJwt = config('ipfs.pinata_jwt', '');
        $this->gatewayUrl = config('ipfs.gateway_url', 'https://gateway.pinata.cloud/ipfs');
    }

    /**
     * Check if IPFS is enabled
     */
    public function isEnabled(): bool
    {
        return $this->enabled && !empty($this->pinataJwt);
    }

    /**
     * Get the gateway URL for a CID
     */
    public function getGatewayUrl(string $cid): string
    {
        return rtrim($this->gatewayUrl, '/') . '/' . $cid;
    }

    /**
     * Upload JSON metadata to IPFS via Pinata
     */
    public function uploadJson(array $data, string $name): ?array
    {
        if (!$this->isEnabled()) {
            Log::warning('IpfsService: IPFS not enabled or Pinata JWT not configured');
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->pinataJwt,
                'Content-Type' => 'application/json',
            ])->timeout(60)->post('https://api.pinata.cloud/pinning/pinJSONToIPFS', [
                        'pinataContent' => $data,
                        'pinataMetadata' => [
                            'name' => $name . '.json',
                        ],
                        'pinataOptions' => [
                            'cidVersion' => 1,
                        ],
                    ]);

            if ($response->successful()) {
                $result = $response->json();
                $cid = $result['IpfsHash'];

                Log::info("IpfsService: JSON uploaded to Pinata. CID: {$cid}");

                return [
                    'success' => true,
                    'cid' => $cid,
                    'url' => $this->getGatewayUrl($cid),
                    'name' => $name,
                ];
            }

            Log::error('IpfsService: Pinata upload failed. Status: ' . $response->status() . ' Body: ' . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error('IpfsService: Upload error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Upload a file to IPFS via Pinata
     */
    public function uploadFile(string $filePath, string $fileName): ?array
    {
        if (!$this->isEnabled()) {
            Log::warning('IpfsService: IPFS not enabled');
            return null;
        }

        if (!file_exists($filePath)) {
            Log::error('IpfsService: File not found: ' . $filePath);
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->pinataJwt,
            ])->timeout(120)->attach(
                    'file',
                    file_get_contents($filePath),
                    $fileName
                )->post('https://api.pinata.cloud/pinning/pinFileToIPFS', [
                        'pinataMetadata' => json_encode(['name' => $fileName]),
                        'pinataOptions' => json_encode(['cidVersion' => 1]),
                    ]);

            if ($response->successful()) {
                $result = $response->json();
                $cid = $result['IpfsHash'];

                Log::info("IpfsService: File uploaded to Pinata. CID: {$cid}");

                return [
                    'success' => true,
                    'cid' => $cid,
                    'url' => $this->getGatewayUrl($cid),
                    'name' => $fileName,
                ];
            }

            Log::error('IpfsService: File upload failed. Status: ' . $response->status());
            return null;

        } catch (\Exception $e) {
            Log::error('IpfsService: File upload error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Upload certificate metadata to IPFS
     */
    public function uploadCertificateMetadata(Certificate $certificate): ?array
    {
        $metadata = [
            'name' => 'Certificate: ' . $certificate->certificate_number,
            'description' => 'Digital certificate issued by SertiKu - Stored on IPFS via Pinata',
            'certificate_number' => $certificate->certificate_number,
            'recipient_name' => $certificate->recipient_name,
            'course_name' => $certificate->course_name,
            'issue_date' => $certificate->issue_date?->format('Y-m-d'),
            'expire_date' => $certificate->expire_date?->format('Y-m-d'),
            'issuer' => $certificate->user?->institution_name ?? $certificate->user?->name,
            'verification_url' => $certificate->verification_url,
            'blockchain_hash' => $certificate->blockchain_hash,
            'blockchain_tx_hash' => $certificate->blockchain_tx_hash,
            'issued_at' => $certificate->created_at?->toIso8601String(),
            'storage' => [
                'provider' => 'Pinata',
                'network' => 'IPFS',
            ],
        ];

        return $this->uploadJson($metadata, $certificate->certificate_number);
    }

    /**
     * Get IPFS service info for dashboard
     */
    public function getInfo(): array
    {
        return [
            'enabled' => $this->isEnabled(),
            'provider' => 'Pinata (IPFS)',
            'gateway_url' => $this->gatewayUrl,
        ];
    }
}
