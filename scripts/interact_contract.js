/**
 * Interact with CertificateRegistry Smart Contract
 * Stores and retrieves full certificate data
 * 
 * Usage:
 *   Store:  node interact_contract.js store <hash> <certNumber> <recipientName> <courseName> <issueDate> <issuerName>
 *   Verify: node interact_contract.js verify <hash>
 *   VerifyByNumber: node interact_contract.js verify-number <certNumber>
 *   Stats:  node interact_contract.js stats
 */

const { ethers } = require('ethers');
const fs = require('fs');
const path = require('path');

// Load environment from .env file
function loadEnv() {
    const envPath = path.join(__dirname, '..', '.env');
    const envContent = fs.readFileSync(envPath, 'utf8');
    const env = {};
    // Handle both Unix (\n) and Windows (\r\n) line endings
    envContent.split(/\r?\n/).forEach(line => {
        // Skip empty lines and comments
        if (!line || line.startsWith('#')) return;
        const match = line.match(/^([^=]+)=(.*)$/);
        if (match) {
            // Trim and remove surrounding quotes, also remove any trailing \r
            env[match[1].trim()] = match[2].trim().replace(/^["']|["']$/g, '').replace(/\r$/, '');
        }
    });
    return env;
}

// Load ABI from file or use inline
function getContractABI() {
    const abiPath = path.join(__dirname, 'CertificateRegistry.abi.json');
    if (fs.existsSync(abiPath)) {
        return JSON.parse(fs.readFileSync(abiPath, 'utf8'));
    }

    // Fallback inline ABI - ULTRA MINIMAL (only hash)
    return [
        "function storeCertificate(bytes32 _dataHash) external returns (bool)",
        "function verifyCertificate(bytes32 _dataHash) external view returns (bool exists, bool revoked, address issuer, uint256 timestamp)",
        "function certificateExists(bytes32 _dataHash) external view returns (bool)",
        "function getTotalCertificates() external view returns (uint256)",
        "function revokeCertificate(bytes32 _dataHash) external returns (bool)",
        "event CertificateStored(bytes32 indexed dataHash, address indexed issuer, uint256 timestamp)"
    ];
}

async function main() {
    const args = process.argv.slice(2);
    const action = args[0];

    if (!action) {
        console.log('Usage:');
        console.log('  node interact_contract.js store <hash> <certNumber> <recipientName> <courseName> <issueDate> <issuerName>');
        console.log('  node interact_contract.js verify <hash>');
        console.log('  node interact_contract.js verify-number <certNumber>');
        console.log('  node interact_contract.js stats');
        process.exit(1);
    }

    // Load environment
    const env = loadEnv();
    const privateKey = env.POLYGON_PRIVATE_KEY;
    const rpcUrl = env.POLYGON_RPC_URL || 'https://rpc-amoy.polygon.technology/';
    const contractAddress = env.POLYGON_CONTRACT_ADDRESS;

    if (!contractAddress) {
        console.log(JSON.stringify({
            success: false,
            error: 'POLYGON_CONTRACT_ADDRESS not found in .env'
        }));
        process.exit(1);
    }

    // Connect to provider
    const provider = new ethers.JsonRpcProvider(rpcUrl);
    const abi = getContractABI();

    if (action === 'store') {
        // Store certificate - ONLY HASH (ultra minimal)
        const hash = args[1];

        if (!hash) {
            console.log(JSON.stringify({ success: false, error: 'Hash required' }));
            process.exit(1);
        }

        if (!privateKey) {
            console.log(JSON.stringify({ success: false, error: 'POLYGON_PRIVATE_KEY not found' }));
            process.exit(1);
        }

        const wallet = new ethers.Wallet(privateKey, provider);
        const contract = new ethers.Contract(contractAddress, abi, wallet);

        // Ensure hash is properly formatted
        let certHash = hash;
        if (!certHash.startsWith('0x')) {
            certHash = '0x' + certHash;
        }
        if (certHash.length < 66) {
            certHash = certHash.padEnd(66, '0');
        }

        try {
            // Check if already exists
            const exists = await contract.certificateExists(certHash);
            if (exists) {
                console.log(JSON.stringify({
                    success: true,
                    alreadyExists: true,
                    hash: certHash,
                    message: 'Certificate already stored on blockchain'
                }));
                return;
            }

            // Store certificate - ONLY HASH!
            const tx = await contract.storeCertificate(certHash);

            console.log(JSON.stringify({
                success: true,
                transactionHash: tx.hash,
                hash: certHash,
                status: 'pending'
            }));

            // Wait for confirmation
            const receipt = await tx.wait();

            console.log(JSON.stringify({
                success: true,
                transactionHash: tx.hash,
                hash: certHash,
                certificateNumber: certNumber,
                recipientName: recipientName,
                status: 'confirmed',
                blockNumber: receipt.blockNumber,
                gasUsed: receipt.gasUsed.toString()
            }));

        } catch (error) {
            console.log(JSON.stringify({
                success: false,
                error: error.message,
                hash: certHash
            }));
        }

    } else if (action === 'verify') {
        // Verify by hash
        const hash = args[1];
        if (!hash) {
            console.log(JSON.stringify({ success: false, error: 'Hash required' }));
            process.exit(1);
        }

        const contract = new ethers.Contract(contractAddress, abi, provider);

        let certHash = hash;
        if (!certHash.startsWith('0x')) {
            certHash = '0x' + certHash;
        }
        if (certHash.length < 66) {
            certHash = certHash.padEnd(66, '0');
        }

        try {
            const result = await contract.verifyCertificate(certHash);

            if (result[0]) { // exists
                console.log(JSON.stringify({
                    success: true,
                    exists: true,
                    revoked: result[1],
                    certificateNumber: result[2],
                    recipientName: result[3],
                    courseName: result[4],
                    issueDate: result[5],
                    issuerName: result[6],
                    issuerAddress: result[7],
                    timestamp: Number(result[8]),
                    date: new Date(Number(result[8]) * 1000).toISOString(),
                    hash: certHash
                }));
            } else {
                console.log(JSON.stringify({
                    success: true,
                    exists: false,
                    hash: certHash
                }));
            }
        } catch (error) {
            console.log(JSON.stringify({
                success: false,
                error: error.message
            }));
        }

    } else if (action === 'verify-number') {
        // Verify by certificate number
        const certNumber = args[1];
        if (!certNumber) {
            console.log(JSON.stringify({ success: false, error: 'Certificate number required' }));
            process.exit(1);
        }

        const contract = new ethers.Contract(contractAddress, abi, provider);

        try {
            const result = await contract.verifyCertificateByNumber(certNumber);

            if (result[0]) { // exists
                console.log(JSON.stringify({
                    success: true,
                    exists: true,
                    revoked: result[1],
                    certificateNumber: certNumber,
                    recipientName: result[2],
                    courseName: result[3],
                    issueDate: result[4],
                    dataHash: result[5],
                    timestamp: Number(result[6]),
                    date: new Date(Number(result[6]) * 1000).toISOString()
                }));
            } else {
                console.log(JSON.stringify({
                    success: true,
                    exists: false,
                    certificateNumber: certNumber
                }));
            }
        } catch (error) {
            console.log(JSON.stringify({
                success: false,
                error: error.message
            }));
        }

    } else if (action === 'stats') {
        const contract = new ethers.Contract(contractAddress, abi, provider);

        try {
            const total = await contract.getTotalCertificates();
            console.log(JSON.stringify({
                success: true,
                totalCertificates: Number(total)
            }));
        } catch (error) {
            console.log(JSON.stringify({
                success: false,
                error: error.message
            }));
        }

    } else {
        console.log(JSON.stringify({
            success: false,
            error: 'Unknown action: ' + action
        }));
        process.exit(1);
    }
}

main().catch(error => {
    console.log(JSON.stringify({
        success: false,
        error: error.message
    }));
    process.exit(1);
});
