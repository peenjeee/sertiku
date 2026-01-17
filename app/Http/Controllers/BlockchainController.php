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
        $query = $request->input('q');
        $result = null;
        $certificate = null;
        $onChainData = null;
        $error = null;

        if ($query) {
            // Try to find certificate by number, hash, or IPFS CID
            $certificate = Certificate::where('certificate_number', $query)
                ->orWhere('blockchain_hash', $query)
                ->orWhere('blockchain_tx_hash', $query)
                ->orWhere('certificate_sha256', $query)
                ->orWhere('ipfs_cid', $query)
                ->first();

            if ($certificate) {
                // Get the hash to verify
                // IMPORTANT: blockchain_hash is what we actually store on-chain via smart contract
                // certificate_sha256 is just the PDF file hash (no 0x prefix) - NOT what's on blockchain
                $hashToVerify = $certificate->blockchain_hash;

                // Ensure 0x prefix for smart contract
                if ($hashToVerify && !str_starts_with($hashToVerify, '0x')) {
                    $hashToVerify = '0x' . $hashToVerify;
                }

                if ($hashToVerify) {
                    // Get on-chain data using blockchain_hash
                    $onChainData = $this->blockchain->verifyCertificateOnChain($hashToVerify);

                    if ($onChainData && isset($onChainData['exists']) && $onChainData['exists']) {
                        $result = 'found';
                    } else {
                        // Fallback: try with certificate_sha256 (some older certs may use this)
                        if ($certificate->certificate_sha256) {
                            $fallbackHash = '0x' . ltrim($certificate->certificate_sha256, '0x');
                            $onChainData = $this->blockchain->verifyCertificateOnChain($fallbackHash);

                            if ($onChainData && isset($onChainData['exists']) && $onChainData['exists']) {
                                $result = 'found';
                            } else {
                                $result = 'not_on_chain';
                            }
                        } else {
                            $result = 'not_on_chain';
                        }
                    }
                } else if ($certificate->certificate_sha256) {
                    // No blockchain_hash, try certificate_sha256
                    $hashToVerify = '0x' . ltrim($certificate->certificate_sha256, '0x');
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
                } else if (strlen($query) >= 46 && (str_starts_with($query, 'Qm') || str_starts_with($query, 'baf'))) {
                    // 3. Try as IPFS CID (Fetch Metadata -> Get Hash -> Verify)
                    // CIDv0 starts with 'Qm', CIDv1 starts with 'baf' (bafy, bafk, bafyb, etc.)
                    try {
                        // Use a public gateway to fetch metadata
                        $ipfsUrl = "https://gateway.pinata.cloud/ipfs/" . $query;
                        $response = Http::timeout(10)->get($ipfsUrl);

                        if ($response->successful()) {
                            $metadata = $response->json();

                            // Check if it's our metadata format
                            if (isset($metadata['certificate_number']) && isset($metadata['recipient_name'])) {

                                // First, try to find the certificate in our database
                                // This gives us complete data including proper blockchain_hash
                                $certificate = Certificate::where('certificate_number', $metadata['certificate_number'])
                                    ->orWhere('ipfs_cid', $query)
                                    ->first();

                                if ($certificate) {
                                    // Found in database - use blockchain_hash for verification
                                    $hashToVerify = $certificate->blockchain_hash;

                                    if ($hashToVerify) {
                                        if (!str_starts_with($hashToVerify, '0x')) {
                                            $hashToVerify = '0x' . $hashToVerify;
                                        }

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
                                    // Not in database - create temporary Certificate from IPFS metadata
                                    $certificate = new Certificate([
                                        'certificate_number' => $metadata['certificate_number'],
                                        'recipient_name' => $metadata['recipient_name'],
                                        'course_name' => $metadata['course_name'] ?? 'N/A',
                                        'blockchain_hash' => $metadata['blockchain_hash'] ?? null,
                                        'blockchain_tx_hash' => $metadata['blockchain_tx_hash'] ?? null,
                                        'ipfs_cid' => $query,
                                    ]);

                                    if (isset($metadata['issue_date'])) {
                                        $certificate->issue_date = \Carbon\Carbon::parse($metadata['issue_date']);
                                    }

                                    // Verify using blockchain_hash from IPFS metadata
                                    if (isset($metadata['blockchain_hash']) && str_starts_with($metadata['blockchain_hash'], '0x')) {
                                        $certHashFromIpfs = $metadata['blockchain_hash'];

                                        $onChainData = $this->blockchain->verifyCertificateOnChain($certHashFromIpfs);

                                        if ($onChainData && isset($onChainData['exists']) && $onChainData['exists']) {
                                            $result = 'found';
                                        } else {
                                            $result = 'not_on_chain';
                                        }
                                    } else {
                                        // On IPFS but no blockchain hash - valid IPFS-only certificate
                                        $result = 'no_blockchain';
                                    }
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
        $walletInfo = $this->blockchain->getWalletInfo();
        // Fallback to database count if API fails
        $totalBlockchainTransactions = $walletInfo['transaction_count'] ?? Certificate::whereNotNull('blockchain_tx_hash')->count();

        return view('blockchain.verify', compact(
            'query',
            'result',
            'certificate',
            'onChainData',
            'contractStats',
            'walletInfo',
            'totalBlockchainTransactions',
            'error'
        ));
    }

    /**
     * API endpoint for AJAX verification
     */
    public function verifyApi(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return response()->json(['error' => 'Query required'], 400);
        }

        $certificate = Certificate::where('certificate_number', $query)
            ->orWhere('blockchain_hash', $query)
            ->orWhere('ipfs_cid', $query)
            ->first();

        if ($certificate && $certificate->blockchain_hash) {
            $onChainData = $this->blockchain->verifyCertificateOnChain($certificate->blockchain_hash);

            $response = [
                'success' => true,
                'certificate' => [
                    'certificate_number' => $certificate->certificate_number,
                    'recipient_name' => $certificate->recipient_name,
                    'course_name' => $certificate->course_name,
                    'issue_date' => $certificate->issue_date?->format('Y-m-d'),
                    'blockchain_hash' => $certificate->blockchain_hash,
                    'blockchain_tx_hash' => $certificate->blockchain_tx_hash,
                    'blockchain_status' => $certificate->blockchain_status,
                    'status' => $certificate->status,
                ],
                'on_chain' => $onChainData,
                'is_revoked' => $certificate->status === 'revoked',
            ];

            // Add warning message for revoked certificates
            if ($certificate->status === 'revoked') {
                $response['revoked_message'] = 'Sertifikat ini telah DICABUT oleh penerbit dan tidak lagi valid.';
                $response['revoked_at'] = $certificate->revoked_at?->toIso8601String();
                $response['revoked_reason'] = $certificate->revoked_reason;
            }

            return response()->json($response);
        }

        return response()->json([
            'success' => false,
            'message' => 'Certificate not found',
        ], 404);
    }
}
