<?php
namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Services\BlockchainService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BlockchainController extends Controller
{
    protected BlockchainService $blockchain;

    public function __construct(BlockchainService $blockchain)
    {
        $this->blockchain = $blockchain;
    }

    /**
     * Show blockchain verification page
     */
    public function verify(Request $request)
    {
        $query       = $request->input('q');
        $result      = null;
        $certificate = null;
        $onChainData = null;
        $error       = null;

        if ($query) {
            // Try to find certificate by number, hash, or IPFS CID
            $certificate = Certificate::where('certificate_number', $query)
                ->orWhere('blockchain_hash', $query)
                ->orWhere('blockchain_tx_hash', $query)
                ->orWhere('certificate_sha256', $query)
                ->orWhere('ipfs_cid', $query)
                ->first();

            if ($certificate) {
                // Get the hash to verify (prefer certificate_sha256 as it's what we store on-chain now)
                $hashToVerify = $certificate->certificate_sha256 ?? $certificate->blockchain_hash;

                if ($hashToVerify) {
                    // Get on-chain data
                    $onChainData = $this->blockchain->verifyCertificateOnChain($hashToVerify);

                    if ($onChainData && isset($onChainData['exists']) && $onChainData['exists']) {
                        $result = 'found';
                    } else {
                        $result = 'not_on_chain';
                    }
                } else {
                    $result = 'no_blockchain';
                }
            } else {
                // Try direct hash verification
                if (str_starts_with($query, '0x') && strlen($query) === 66) {
                    // 1. Try as Certificate Hash
                    $onChainData = $this->blockchain->verifyCertificateOnChain($query);

                    if ($onChainData && isset($onChainData['exists']) && $onChainData['exists']) {
                        $result = 'found';
                        // Also try to load certificate from database for complete info
                        $certificate = Certificate::where('certificate_sha256', $query)
                            ->orWhere('blockchain_hash', $query)
                            ->first();
                    } else {
                        // Check if this hash exists in database but not on blockchain
                        $certificate = Certificate::where('certificate_sha256', $query)
                            ->orWhere('blockchain_hash', $query)
                            ->first();

                        if ($certificate) {
                            $result = 'not_on_chain';
                        } else {
                            // 2. Try as Transaction Hash
                            $txData = $this->blockchain->verifyTransactionOnChain($query);

                            if ($txData && isset($txData['data'])) {
                                // Extract Certificate Hash from Transaction Input Data
                                // Method ID: 0x...(4 bytes)
                                // First Parameter: bytes32 _dataHash (32 bytes = 64 hex chars)
                                // Input data usually looks like: 0x[MethodID][Param1][Param2]...
                                // If Param1 is fixed size (bytes32), it starts at index 10 (0x + 8 chars)

                                $inputData = $txData['data'];
                                if (strlen($inputData) >= 74) { // 0x + 8 chars method ID + 64 chars data
                                                                    // Extract the potential certificate hash
                                    $potentialCertHash = '0x' . substr($inputData, 10, 64);

                                    // Verify this extracted hash
                                    $onChainData = $this->blockchain->verifyCertificateOnChain($potentialCertHash);

                                    if ($onChainData && isset($onChainData['exists']) && $onChainData['exists']) {
                                        $result = 'found';
                                        // Update query to show the actual certificate hash in UI if needed
                                        // $query = $potentialCertHash; // Optional: switch context to cert hash
                                    } else {
                                        $result = 'not_found';
                                    }
                                } else {
                                    $result = 'not_found';
                                }
                            } else {
                                $result = 'not_found';
                            }
                        }
                    }
                } else if (strlen($query) >= 46 && (str_starts_with($query, 'Qm') || str_starts_with($query, 'bafy'))) {
                    // 3. Try as IPFS CID (Fetch Metadata -> Get Hash -> Verify)
                    try {
                        // Use a public gateway to fetch metadata
                        $ipfsUrl  = "https://gateway.pinata.cloud/ipfs/" . $query;
                        $response = Http::timeout(5)->get($ipfsUrl);

                        if ($response->successful()) {
                            $metadata = $response->json();

                            // Check if it's our metadata format
                            if (isset($metadata['certificate_number']) && isset($metadata['recipient_name'])) {
                                // Create a temporary Certificate object for display
                                $certificate = new Certificate([
                                    'certificate_number' => $metadata['certificate_number'],
                                    'recipient_name'     => $metadata['recipient_name'],
                                    'course_name'        => $metadata['course_name'] ?? 'N/A',
                                    'blockchain_hash'    => $metadata['blockchain_hash'] ?? null,
                                    'blockchain_tx_hash' => $metadata['blockchain_tx_hash'] ?? null,
                                    'ipfs_cid'           => $query, // The CID we searched for
                                ]);

                                // Manually set issue_date since it's casted
                                if (isset($metadata['issue_date'])) {
                                    $certificate->issue_date = \Carbon\Carbon::parse($metadata['issue_date']);
                                }

                                // Mock the User relationship for Issuer Name if possible, or handle in view
                                // Since we can't easily mock the relation on a non-saved model without key,
                                // we will attach the issuer name dynamically if needed, or the view should handle optional user.

                                // Check blockchain hash
                                if (isset($metadata['blockchain_hash']) && str_starts_with($metadata['blockchain_hash'], '0x')) {
                                    $certHashFromIpfs = $metadata['blockchain_hash'];

                                    // Verify this hash on chain
                                    $onChainData = $this->blockchain->verifyCertificateOnChain($certHashFromIpfs);

                                    if ($onChainData && isset($onChainData['exists']) && $onChainData['exists']) {
                                        $result = 'found';
                                    } else {
                                        // On IPFS, has hash, but NOT on chain?
                                        // Means it claims to be on chain but isn't.
                                        // Could be 'not_on_chain' (Found in DB/IPFS, but verification failed)
                                        $result = 'not_on_chain';
                                    }
                                } else {
                                    // Found on IPFS, but NO blockchain hash in metadata
                                    // This means it's a valid certificate stored on IPFS only
                                    $result = 'no_blockchain';
                                }
                            } else {
                                $result = 'not_found';
                            }
                        } else {
                            $result = 'not_found';
                        }
                    } catch (\Exception $e) {
                        $result = 'not_found';
                    }
                } else {
                    $result = 'not_found';
                }
            }
        }

        // Get contract stats
        $contractStats = $this->blockchain->getContractStats();
        $walletInfo    = $this->blockchain->getWalletInfo();

        return view('blockchain.verify', compact(
            'query',
            'result',
            'certificate',
            'onChainData',
            'contractStats',
            'walletInfo',
            'error'
        ));
    }

/**
 * API endpoint for AJAX verification
 */
    public function verifyApi(Request $request)
    {
        $query = $request->input('q');

        if (! $query) {
            return response()->json(['error' => 'Query required'], 400);
        }

        $certificate = Certificate::where('certificate_number', $query)
            ->orWhere('blockchain_hash', $query)
            ->orWhere('ipfs_cid', $query)
            ->first();

        if ($certificate && $certificate->blockchain_hash) {
            $onChainData = $this->blockchain->verifyCertificateOnChain($certificate->blockchain_hash);

            $response = [
                'success'     => true,
                'certificate' => [
                    'certificate_number' => $certificate->certificate_number,
                    'recipient_name'     => $certificate->recipient_name,
                    'course_name'        => $certificate->course_name,
                    'issue_date'         => $certificate->issue_date?->format('Y-m-d'),
                    'blockchain_hash'    => $certificate->blockchain_hash,
                    'blockchain_tx_hash' => $certificate->blockchain_tx_hash,
                    'blockchain_status'  => $certificate->blockchain_status,
                    'status'             => $certificate->status,
                ],
                'on_chain'    => $onChainData,
                'is_revoked'  => $certificate->status === 'revoked',
            ];

            // Add warning message for revoked certificates
            if ($certificate->status === 'revoked') {
                $response['revoked_message'] = 'Sertifikat ini telah DICABUT oleh penerbit dan tidak lagi valid.';
                $response['revoked_at']      = $certificate->revoked_at?->toIso8601String();
                $response['revoked_reason']  = $certificate->revoked_reason;
            }

            return response()->json($response);
        }

        return response()->json([
            'success' => false,
            'message' => 'Certificate not found',
        ], 404);
    }
}
