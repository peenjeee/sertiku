import './bootstrap';

// Web3Modal for WalletConnect (only imported if needed)
if (document.querySelector('meta[name="walletconnect-project-id"]')) {
    import('./web3modal.js');
}

import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();
