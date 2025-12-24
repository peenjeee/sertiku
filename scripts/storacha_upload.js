/**
 * Storacha IPFS Upload Script
 * Uses Storacha CLI for authentication (simpler approach)
 *
 * Usage: node storacha_upload.js <file_path>
 */

import { execSync } from 'child_process';
import * as fs from 'fs';
import * as path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const args = process.argv.slice(2);
const filePath = args[0];

if (!filePath) {
    console.log(JSON.stringify({ success: false, error: 'No file path provided' }));
    process.exit(1);
}

async function uploadToStoracha(targetPath) {
    try {
        // Ensure absolute path
        const absPath = path.resolve(targetPath);

        // Check file exists
        if (!fs.existsSync(absPath)) {
            throw new Error(`File not found: ${absPath}`);
        }

        const fileName = path.basename(absPath);

        // Resolve local w3 executable path (Windows specific check could be added, but .cmd is safe for now on Windows)
        // Fallback to 'w3' if local not found, but we prefer local
        const localW3 = path.join(__dirname, 'node_modules', '.bin', 'w3.cmd');
        const w3Exec = fs.existsSync(localW3) ? localW3 : 'w3';

        // Execute w3 up
        // Quote paths to handle spaces
        const command = `"${w3Exec}" up "${absPath}"`;

        const output = execSync(command, {
            encoding: 'utf-8',
            timeout: 120000, // 2 minutes
            stdio: ['ignore', 'pipe', 'pipe'] // Capture stdout/stderr
        });

        // Parse CID from output
        // CLI outputs: "🐔 https://storacha.link/ipfs/<CID>"
        // Or sometimes just the CID line depending on version
        const urlMatch = output.match(/ipfs\/(bafy[a-z0-9]+|bafk[a-z0-9]+|Qm[a-zA-Z0-9]+)/);

        if (!urlMatch) {
            throw new Error(`Could not parse CID from output. Raw output: ${output.substring(0, 200)}...`);
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
        // Capture stderr if available from execSync error
        let errorMsg = error.message;
        if (error.stderr) {
            errorMsg += "\nStderr:\n" + error.stderr.toString();
        }

        // Write to debug file
        fs.writeFileSync('upload_error_details.log', errorMsg);

        const result = {
            success: false,
            error: 'See upload_error_details.log',
        };
        console.log(JSON.stringify(result));
        process.exit(1);
    }
}

uploadToStoracha(filePath);
