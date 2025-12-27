<?php
namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Services\BlockchainService;
use Illuminate\Http\Request;

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

            // Get the hash to verify (prefer certificate_sha256 as it's what we store on-chain now)
            $hashToVerify = $certificate->certificate_sha256 ?? $certificate->blockchain_hash;

            if ($certificate && $hashToVerify) {
                // Get on-chain data
                $onChainData = $this->blockchain->verifyCertificateOnChain($hashToVerify);

                if ($onChainData && isset($onChainData['exists']) && $onChainData['exists']) {
                    $result = 'found';
                } else {
                    $result = 'not_on_chain';
                }
            } elseif ($certificate) {
                $result = 'no_blockchain';
            } else {
                // Try direct hash verification
                if (str_starts_with($query, '0x') && strlen($query) === 66) {
                    $onChainData = $this->blockchain->verifyCertificateOnChain($query);
                    if ($onChainData && isset($onChainData['exists']) && $onChainData['exists']) {
                        $result = 'found';
                    } else {
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
