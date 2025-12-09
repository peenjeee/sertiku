import { createWeb3Modal, defaultConfig } from '@web3modal/ethers';

// Get project ID from meta tag
const projectIdMeta = document.querySelector('meta[name="walletconnect-project-id"]');
const projectId = projectIdMeta ? projectIdMeta.content : '';

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
        }
    });
    
    window.web3ModalReady = true;
    console.log('Web3Modal initialized successfully');

    // Subscribe to connection events
    window.web3Modal.subscribeProvider(({ address, isConnected }) => {
        if (isConnected && address && window.handleWalletConnected) {
            window.handleWalletConnected(address);
        }
    });
} else {
    console.warn('WalletConnect Project ID not found');
}
