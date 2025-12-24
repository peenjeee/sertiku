/**
 * Storacha IPFS Upload Script
 * Uses Storacha CLI for authentication (simpler approach)
 *
 * Usage: node storacha_upload.js <file_path>
 */

import { execSync } from 'child_process';
import * as fs from 'fs';
import * as path from 'path';

const args = process.argv.slice(2);
const filePath = args[0];

if (!filePath) {
    console.log(JSON.stringify({ success: false, error: 'No file path provided' }));
    process.exit(1);
}

async function uploadToStoracha(filePath) {
    try {
        // Check file exists
        if (!fs.existsSync(filePath)) {
            throw new Error(`File not found: ${filePath}`);
        }

        const fileName = path.basename(filePath);

        // Use Storacha CLI directly (uses stored credentials)
        const output = execSync(`storacha up "${filePath}"`, {
            encoding: 'utf-8',
            timeout: 120000, // 2 minutes
        });

        // Parse CID from output
        // CLI outputs: "🐔 https://storacha.link/ipfs/<CID>"
        const urlMatch = output.match(/ipfs\/(bafy[a-z0-9]+|bafk[a-z0-9]+|Qm[a-zA-Z0-9]+)/);
        
        if (!urlMatch) {
            throw new Error(`Could not parse CID from output: ${output}`);
        }

        const cid = urlMatch[1];

        const result = {
            success: true,
            cid: cid,
            url: `https://w3s.link/ipfs/${cid}`,
            name: fileName,
        };

        console.log(JSON.stringify(result));
        return result;

    } catch (error) {
        const result = {
            success: false,
            error: error.message || 'Upload failed',
        };
        console.log(JSON.stringify(result));
        process.exit(1);
    }
}

uploadToStoracha(filePath);
