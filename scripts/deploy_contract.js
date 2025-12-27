/**
 * Deploy CertificateRegistry Smart Contract to Polygon Amoy Testnet
 * This version properly compiles the Solidity contract
 * 
 * Usage: node scripts/deploy_contract.js
 */

const { ethers } = require('ethers');
const solc = require('solc');
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

// Compile the Solidity contract
function compileContract() {
    console.log('Compiling CertificateRegistry.sol...');

    const contractPath = path.join(__dirname, '..', 'contracts', 'CertificateRegistry.sol');
    const source = fs.readFileSync(contractPath, 'utf8');

    const input = {
        language: 'Solidity',
        sources: {
            'CertificateRegistry.sol': {
                content: source
            }
        },
        settings: {
            optimizer: {
                enabled: true,
                runs: 200
            },
            outputSelection: {
                '*': {
                    '*': ['abi', 'evm.bytecode']
                }
            }
        }
    };

    const output = JSON.parse(solc.compile(JSON.stringify(input)));

    // Check for errors
    if (output.errors) {
        const errors = output.errors.filter(e => e.severity === 'error');
        if (errors.length > 0) {
            console.error('Compilation errors:');
            errors.forEach(e => console.error(e.formattedMessage));
            process.exit(1);
        }
        // Show warnings
        output.errors.filter(e => e.severity === 'warning').forEach(e => {
            console.warn('Warning:', e.message);
        });
    }

    const contract = output.contracts['CertificateRegistry.sol']['CertificateRegistry'];

    console.log('✓ Contract compiled successfully\n');

    return {
        abi: contract.abi,
        bytecode: contract.evm.bytecode.object
    };
}

async function deployContract() {
    console.log('==========================================');
    console.log('SertiKu Certificate Registry Deployment');
    console.log('Network: Polygon Amoy Testnet');
    console.log('==========================================\n');

    // Load environment variables
    const env = loadEnv();

    const privateKey = env.POLYGON_PRIVATE_KEY;
    const rpcUrl = env.POLYGON_RPC_URL || 'https://rpc-amoy.polygon.technology/';

    if (!privateKey) {
        console.error('Error: POLYGON_PRIVATE_KEY not found in .env');
        process.exit(1);
    }

    // Compile contract
    const { abi, bytecode } = compileContract();

    console.log('Connecting to RPC:', rpcUrl);

    // Connect to provider
    const provider = new ethers.JsonRpcProvider(rpcUrl);

    // Create wallet
    const wallet = new ethers.Wallet(privateKey, provider);
    console.log('Deployer address:', wallet.address);

    // Check balance
    const balance = await provider.getBalance(wallet.address);
    console.log('Wallet balance:', ethers.formatEther(balance), 'MATIC\n');

    if (balance < ethers.parseEther('0.01')) {
        console.error('Error: Insufficient balance. Need at least 0.01 MATIC for deployment.');
        process.exit(1);
    }

    console.log('Deploying CertificateRegistry contract...');

    // Create contract factory
    const factory = new ethers.ContractFactory(abi, '0x' + bytecode, wallet);

    // Deploy
    const contract = await factory.deploy();

    console.log('Transaction hash:', contract.deploymentTransaction().hash);
    console.log('Waiting for confirmation...\n');

    // Wait for deployment
    await contract.waitForDeployment();

    const contractAddress = await contract.getAddress();

    console.log('==========================================');
    console.log('CONTRACT DEPLOYED SUCCESSFULLY!');
    console.log('==========================================');
    console.log('Contract Address:', contractAddress);
    console.log('Explorer URL:', `https://amoy.polygonscan.com/address/${contractAddress}`);
    console.log('\nAdd to your .env file:');
    console.log(`POLYGON_CONTRACT_ADDRESS=${contractAddress}`);
    console.log('==========================================\n');

    // Update .env file
    try {
        const envPath = path.join(__dirname, '..', '.env');
        let envContent = fs.readFileSync(envPath, 'utf8');

        // Replace existing or add new
        if (envContent.includes('POLYGON_CONTRACT_ADDRESS=')) {
            envContent = envContent.replace(
                /POLYGON_CONTRACT_ADDRESS=.*/,
                `POLYGON_CONTRACT_ADDRESS=${contractAddress}`
            );
        } else {
            envContent += `\n# Certificate Registry Smart Contract\nPOLYGON_CONTRACT_ADDRESS=${contractAddress}\n`;
        }

        fs.writeFileSync(envPath, envContent);
        console.log('✓ Contract address updated in .env file');
    } catch (err) {
        console.log('Could not update .env file automatically. Please add manually.');
    }

    // Save ABI for future use
    const abiPath = path.join(__dirname, 'CertificateRegistry.abi.json');
    fs.writeFileSync(abiPath, JSON.stringify(abi, null, 2));
    console.log('✓ ABI saved to scripts/CertificateRegistry.abi.json');

    return contractAddress;
}

// Run
deployContract().catch(console.error);
