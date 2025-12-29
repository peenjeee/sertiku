/**
 * Node.js Transaction Signing Script for SertiKu Blockchain Integration
 * 
 * Usage: node sign_transaction.js <privateKey> <walletAddress> <dataHash> <rpcUrl>
 * 
 * Requirements: npm install ethers
 */

import { ethers } from 'ethers';
import process from 'process';

async function signAndSendTransaction() {
    const args = process.argv.slice(2);

    if (args.length < 4) {
        console.error('Usage: node sign_transaction.js <privateKey> <walletAddress> <dataHash> <rpcUrl>');
        process.exit(1);
    }

    const [privateKey, walletAddress, dataHash, rpcUrl] = args;

    try {
        // Connect to provider
        const provider = new ethers.JsonRpcProvider(rpcUrl);

        // Create wallet from private key
        const wallet = new ethers.Wallet(privateKey, provider);

        // Get nonce
        const nonce = await provider.getTransactionCount(walletAddress);

        // Get fee data
        const feeData = await provider.getFeeData();

        // Build transaction
        const tx = {
            to: walletAddress, // Send to self with data
            value: 0,
            data: dataHash,
            nonce: nonce,
            gasLimit: 25200, // Enough for data storage
            gasPrice: feeData.gasPrice,
        };

        // Sign and send transaction
        const txResponse = await wallet.sendTransaction(tx);

        // Wait for confirmation with 60 second timeout
        const timeoutMs = 60000;
        const timeoutPromise = new Promise((_, reject) =>
            setTimeout(() => reject(new Error('Transaction confirmation timeout')), timeoutMs)
        );

        try {
            const receipt = await Promise.race([txResponse.wait(1), timeoutPromise]);
            // Output the transaction hash
            console.log(receipt.hash);
        } catch (waitError) {
            // Transaction sent but confirmation timed out - output tx hash anyway
            console.log(txResponse.hash);
        }

    } catch (error) {
        console.error('Error:', error.message);
        process.exit(1);
    }
}

signAndSendTransaction();
