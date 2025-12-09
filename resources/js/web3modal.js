import { createWeb3Modal, defaultConfig } from '@web3modal/ethers';

// Get project ID from meta tag
const projectIdMeta = document.querySelector('meta[name="walletconnect-project-id"]');
const projectId = projectIdMeta ? projectIdMeta.content : '';

// Check if user just logged out
const justLoggedOut = sessionStorage.getItem('wallet_logged_out') === 'true';

// Initialize Web3Modal
if (projectId) {
    const mainnet = {
        chainId: 1,
        name: 'Ethereum',
        currency: 'ETH',
        explorerUrl: 'https://etherscan.io',
        rpcUrl: 'https://cloudflare-eth.com'
    };

    const metadata = {
        name: 'SertiKu',
        description: 'Platform Verifikasi Sertifikat Digital',
        url: window.location.origin,
        icons: [window.location.origin + '/favicon.ico']
    };

    const ethersConfig = defaultConfig({ metadata });

    window.web3Modal = createWeb3Modal({
        ethersConfig,
        chains: [mainnet],
        projectId,
        enableAnalytics: false,
        themeMode: 'dark',
        themeVariables: {
            '--w3m-accent': '#3B82F6',
        },
        // Disable auto-connect to prevent unwanted reconnection after logout
        enableOnramp: false,
    });

    window.web3ModalReady = true;
    console.log('Web3Modal initialized successfully');

    // Only subscribe to connection events if user hasn't just logged out
    // and only trigger login if user explicitly clicked connect
    window.walletConnectPending = false;

    window.web3Modal.subscribeProvider(({ address, isConnected }) => {
        // Only auto-login if user explicitly initiated the connection
        if (isConnected && address && window.walletConnectPending && window.handleWalletConnected) {
            window.walletConnectPending = false;
            sessionStorage.removeItem('wallet_logged_out');
            window.handleWalletConnected(address);
        }
    });

    // If user just logged out, disconnect any existing wallet connection
    if (justLoggedOut) {
        try {
            if (window.web3Modal.disconnect) {
                window.web3Modal.disconnect();
            }
        } catch (e) {
            console.log('Disconnect on load:', e);
        }
    }
} else {
    console.warn('WalletConnect Project ID not found');
}
