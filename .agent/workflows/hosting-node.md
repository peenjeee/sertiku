---
description: How to run Node.js commands on HyperCloudHost hosting via SSH
---

# Node.js Path on Hosting Server

The hosting (HyperCloudHost/cPanel) has Node.js installed at:
```
/opt/alt/alt-nodejs20/root/usr/bin/node
/opt/alt/alt-nodejs20/root/usr/bin/npm
```

## Common Commands

// turbo-all

1. SSH into server and go to project directory:
```bash
cd /home/aqnhdqzp/sertiku
```

2. Check Node version:
```bash
/opt/alt/alt-nodejs20/root/usr/bin/node --version
```

3. Install npm dependencies:
```bash
/opt/alt/alt-nodejs20/root/usr/bin/npm install
```

4. Run a Node.js script (e.g., deploy contract):
```bash
/opt/alt/alt-nodejs20/root/usr/bin/node scripts/deploy_contract.js
```

5. Run interact script:
```bash
/opt/alt/alt-nodejs20/root/usr/bin/node scripts/interact_contract.js store <hash>
```

## Troubleshooting

If `node` or `npm` commands don't work directly, always use the full path:
- Node: `/opt/alt/alt-nodejs20/root/usr/bin/node`
- NPM: `/opt/alt/alt-nodejs20/root/usr/bin/npm`

All Node versions available at: `/opt/alt/alt-nodejs*/root/usr/bin/node`
