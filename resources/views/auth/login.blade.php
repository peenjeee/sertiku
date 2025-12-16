{{-- resources/views/auth/login.blade.php --}}
<x-layouts.app title="SertiKu – Login">

    {{-- WalletConnect Project ID meta tag (read by web3modal.js) --}}
    <meta name="walletconnect-project-id" content="{{ config('services.walletconnect.project_id', '') }}">

    {{-- Google reCAPTCHA Script --}}
    @if(config('recaptcha.enabled') && config('recaptcha.site_key'))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <style>
            /* reCAPTCHA Styling */
            .g-recaptcha {
                transform: scale(0.95);
                transform-origin: center;
            }

            .g-recaptcha>div {
                border-radius: 12px !important;
                overflow: hidden;
            }

            @media (max-width: 400px) {
                .g-recaptcha {
                    transform: scale(0.85);
                }
            }
        </style>
    @endif

    {{-- Set global variables --}}
    <script>
        window.walletConnectProjectId = '{{ config("services.walletconnect.project_id", "") }}';
        window.web3ModalReady = false;
        window.walletConnectPending = false;

        // Set flag if user came from logout
        @if(session('success') && str_contains(session('success'), 'logout'))
            sessionStorage.setItem('wallet_logged_out', 'true');
        @endif
    </script>

    {{-- Script tab + wallet connect --}}
    <script>
            function switchTab(tab) {
                const emailTab = document.getElementById('emailTab');
                const walletTab = document.getElementById('walletTab');
                const emailBtn = document.getElementById('emailTabBtn');
                const walletBtn = document.getElementById('walletTabBtn');

                if (tab === 'email') {
                    emailTab.style.display = 'block';
                    walletTab.style.display = 'none';

                    emailBtn.style.background = 'linear-gradient(180deg, #1E3A8F 0%, #3B82F6 100%)';
                    emailBtn.style.color = 'white';
                    emailBtn.style.boxShadow = '0 1px 3px rgba(0,0,0,0.1), 0 1px 2px -1px rgba(0,0,0,0.1)';

                    walletBtn.style.background = 'transparent';
                    walletBtn.style.color = 'rgba(255, 255, 255, 0.7)';
                    walletBtn.style.boxShadow = 'none';
                } else {
                    emailTab.style.display = 'none';
                    walletTab.style.display = 'block';

                    walletBtn.style.background = 'linear-gradient(180deg, #1E3A8F 0%, #3B82F6 100%)';
                    walletBtn.style.color = 'white';
                    walletBtn.style.boxShadow = '0 1px 3px rgba(0,0,0,0.1), 0 1px 2px -1px rgba(0,0,0,0.1)';

                    emailBtn.style.background = 'transparent';
                    emailBtn.style.color = 'rgba(255, 255, 255, 0.7)';
                    emailBtn.style.boxShadow = 'none';
                }
            }

        // Wallet connect status
        let connectedAddress = null;

        // Connect wallet using specific provider
        async function connectWallet(walletType, event) {
            if (event) event.preventDefault();

            const statusEl = document.getElementById('wallet-status');
            const errorEl = document.getElementById('wallet-error');

            // Reset status
            if (statusEl) statusEl.classList.add('hidden');
            if (errorEl) errorEl.classList.add('hidden');

            try {
                let provider = null;
                let accounts = null;

                if (walletType === 'metamask') {
                    if (!window.ethereum) {
                        throw new Error('MetaMask tidak terdeteksi. Silakan install MetaMask terlebih dahulu.');
                    }
                    // Check if MetaMask is the provider
                    if (window.ethereum.isMetaMask) {
                        provider = window.ethereum;
                    } else if (window.ethereum.providers) {
                        provider = window.ethereum.providers.find(p => p.isMetaMask);
                    }
                    if (!provider) {
                        throw new Error('MetaMask tidak ditemukan. Pastikan extension MetaMask sudah terinstall.');
                    }
                    accounts = await provider.request({ method: 'eth_requestAccounts' });
                } else if (walletType === 'coinbase') {
                    if (window.ethereum && window.ethereum.isCoinbaseWallet) {
                        provider = window.ethereum;
                    } else if (window.ethereum && window.ethereum.providers) {
                        provider = window.ethereum.providers.find(p => p.isCoinbaseWallet);
                    }
                    if (!provider) {
                        window.open('https://www.coinbase.com/wallet', '_blank');
                        throw new Error('Coinbase Wallet tidak terdeteksi. Silakan install Coinbase Wallet.');
                    }
                    accounts = await provider.request({ method: 'eth_requestAccounts' });
                } else if (walletType === 'trust') {
                    if (window.ethereum && window.ethereum.isTrust) {
                        provider = window.ethereum;
                    } else if (window.trustwallet) {
                        provider = window.trustwallet;
                    }
                    if (!provider) {
                        window.open('https://trustwallet.com/download', '_blank');
                        throw new Error('Trust Wallet tidak terdeteksi. Buka di Trust Wallet browser atau install aplikasi.');
                    }
                    accounts = await provider.request({ method: 'eth_requestAccounts' });
                } else if (walletType === 'walletconnect') {
                    // Use Web3Modal for WalletConnect
                    await connectWithWeb3Modal();
                    return;
                } else {
                    // Generic - try window.ethereum
                    if (!window.ethereum) {
                        throw new Error('Wallet tidak terdeteksi. Silakan install crypto wallet terlebih dahulu.');
                    }
                    provider = window.ethereum;
                    accounts = await provider.request({ method: 'eth_requestAccounts' });
                }

                if (accounts && accounts.length > 0) {
                    handleWalletConnected(accounts[0]);
                }
            } catch (error) {
                console.error('Wallet connection error:', error);
                if (errorEl) {
                    errorEl.textContent = error.message || 'Gagal menghubungkan wallet.';
                    errorEl.classList.remove('hidden');
                }
            }
        }

        // Connect using Web3Modal (for WalletConnect)
        async function connectWithWeb3Modal() {
            const errorEl = document.getElementById('wallet-error');
            const statusEl = document.getElementById('wallet-status');

            if (!window.walletConnectProjectId) {
                if (errorEl) {
                    errorEl.innerHTML = 'WalletConnect belum dikonfigurasi. Tambahkan <code>WALLETCONNECT_PROJECT_ID</code> di file .env';
                    errorEl.classList.remove('hidden');
                }
                return;
            }

            // Show loading while waiting for Web3Modal
            if (statusEl) {
                statusEl.innerHTML = '<span class="text-[#8EC5FF]">⏳ Memuat WalletConnect...</span>';
                statusEl.classList.remove('hidden');
            }

            // Wait for Web3Modal to be ready (max 10 seconds)
            let attempts = 0;
            while (!window.web3ModalReady && !window.web3ModalError && attempts < 20) {
                await new Promise(resolve => setTimeout(resolve, 500));
                attempts++;
            }

            if (window.web3ModalError) {
                if (statusEl) statusEl.classList.add('hidden');
                if (errorEl) {
                    errorEl.textContent = 'Gagal memuat Web3Modal: ' + window.web3ModalError;
                    errorEl.classList.remove('hidden');
                }
                return;
            }

            if (!window.web3Modal) {
                if (statusEl) statusEl.classList.add('hidden');
                if (errorEl) {
                    errorEl.textContent = 'Web3Modal masih dimuat. Silakan coba lagi dalam beberapa detik.';
                    errorEl.classList.remove('hidden');
                }
                return;
            }

            try {
                // Show loading
                if (statusEl) {
                    statusEl.innerHTML = '<span class="text-[#8EC5FF]">⏳ Membuka WalletConnect...</span>';
                    statusEl.classList.remove('hidden');
                }

                // Set flag to indicate user explicitly initiated connection
                window.walletConnectPending = true;

                // Clear logged out flag
                sessionStorage.removeItem('wallet_logged_out');

                // Open modal - the subscription in web3modal.js will handle the connection
                await window.web3Modal.open();

                // Hide loading after modal opens (connection will be handled by subscribeProvider in web3modal.js)
                if (statusEl) {
                    statusEl.innerHTML = '<span class="text-[#8EC5FF]">Pilih wallet Anda...</span>';
                }
            } catch (error) {
                console.error('Web3Modal error:', error);
                window.walletConnectPending = false;
                if (statusEl) statusEl.classList.add('hidden');
                if (errorEl) {
                    errorEl.textContent = error.message || 'Gagal membuka WalletConnect.';
                    errorEl.classList.remove('hidden');
                }
            }
        }

        // Handle successful wallet connection
        function handleWalletConnected(address) {
            const statusEl = document.getElementById('wallet-status');

            connectedAddress = address;

            // Show success status
            if (statusEl) {
                statusEl.innerHTML = `<span class="text-[#05DF72]">✓ Connected:</span> ${address.slice(0, 6)}...${address.slice(-4)}`;
                statusEl.classList.remove('hidden');
            }

            // Submit form
            document.getElementById('wallet_address').value = address;
            document.getElementById('walletLoginForm').submit();
        }
    </script>

    <!-- MAIN CONTENT – pakai tema/background seperti landing/verifikasi -->
    <div class="mx-auto flex max-w-6xl flex-col gap-16 px-4 pb-20 pt-16 lg:flex-row lg:px-0 lg:pt-20">
        <div class="w-full max-w-5xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">

                <!-- LEFT SECTION – Logo + fitur -->
                <div class="space-y-12">
                    <!-- Logo & Title -->
                    <div class="space-y-6 text-center lg:text-center">
                        <div class="flex items-center justify-center gap-4">
                            <!-- Icon -->
                            <div class="relative w-16 h-16">
                                {{-- ICON SERTIKU --}}
                                <svg width="64" height="64" viewBox="0 0 64 64" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M47.9953 6.39941H15.9984C10.697 6.39941 6.39935 10.6971 6.39935 15.9985V47.9953C6.39935 53.2968 10.697 57.5944 15.9984 57.5944H47.9953C53.2967 57.5944 57.5943 53.2968 57.5943 47.9953V15.9985C57.5943 10.6971 53.2967 6.39941 47.9953 6.39941Z"
                                        stroke="white" stroke-width="3.83962" />
                                    <path
                                        d="M45.4356 14.3987H18.5582C17.1445 14.3987 15.9984 15.5447 15.9984 16.9584V35.8366C15.9984 37.2503 17.1445 38.3963 18.5582 38.3963H45.4356C46.8493 38.3963 47.9953 37.2503 47.9953 35.8366V16.9584C47.9953 15.5447 46.8493 14.3987 45.4356 14.3987Z"
                                        stroke="white" stroke-width="2.55975" />
                                    <path d="M20.7979 20.7979H33.5967" stroke="white" stroke-width="1.91981"
                                        stroke-linecap="round" />
                                    <path d="M20.7979 25.5974H30.397" stroke="white" stroke-width="1.91981"
                                        stroke-linecap="round" />
                                    <path
                                        d="M23.9977 52.1549C27.1785 52.1549 29.7571 49.5763 29.7571 46.3954C29.7571 43.2146 27.1785 40.636 23.9977 40.636C20.8168 40.636 18.2382 43.2146 18.2382 46.3954C18.2382 49.5763 20.8168 52.1549 23.9977 52.1549Z"
                                        fill="white" />
                                    <path
                                        d="M23.9977 40.636L21.7579 49.5951L23.9977 47.9953L26.2374 49.5951L23.9977 40.636Z"
                                        fill="white" />
                                    <path d="M37.4364 27.1973H33.5967V31.0369H37.4364V27.1973Z" fill="white" />
                                    <path d="M43.1958 27.1973H39.3562V31.0369H43.1958V27.1973Z" fill="white" />
                                    <path d="M37.4364 32.9568H33.5967V36.7964H37.4364V32.9568Z" fill="white" />
                                    <path d="M43.1958 32.9568H39.3562V36.7964H43.1958V32.9568Z" fill="white" />
                                    <path d="M36.7964 29.1172H35.5166V30.3971H36.7964V29.1172Z" fill="#1F3A5F" />
                                    <path d="M42.5559 29.1172H41.276V30.3971H42.5559V29.1172Z" fill="#1F3A5F" />
                                    <path d="M36.7964 34.8765H35.5166V36.1563H36.7964V34.8765Z" fill="#1F3A5F" />
                                    <path d="M42.5559 34.8765H41.276V36.1563H42.5559V34.8765Z" fill="#1F3A5F" />
                                </svg>
                            </div>
                            <!-- Text Logo -->
                            <div class="filter drop-shadow-[0_2px_16px_rgba(0,0,0,0.3)]">
                                <h1 class="text-4xl font-bold text-white tracking-tight">SertiKu</h1>
                            </div>
                        </div>

                        <h2 class="text-4xl font-normal text-white">Selamat Datang</h2>
                        <p class="text-xl text-[#BEDBFF]">
                            Platform Verifikasi Sertifikat Digital Terpercaya
                        </p>
                    </div>

                    <!-- Features -->
                    <div class="space-y-4">
                        <!-- Feature 1 -->
                        <div class="bg-white/5 border border-white/10 rounded-2xl p-6">
                            <div class="flex items-center gap-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-[14px] flex items-center justify-center"
                                    style="background: linear-gradient(135deg, #00C950 0%, #00BC7D 100%);">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M20 13C20 18 16.5 20.5 12.34 21.95C12.1222 22.0238 11.8855 22.0202 11.67 21.94C7.5 20.5 4 18 4 13V5.99996C4 5.73474 4.10536 5.48039 4.29289 5.29285C4.48043 5.10532 4.73478 4.99996 5 4.99996C7 4.99996 9.5 3.79996 11.24 2.27996C11.4519 2.09896 11.7214 1.99951 12 1.99951C12.2786 1.99951 12.5481 2.09896 12.76 2.27996C14.51 3.80996 17 4.99996 19 4.99996C19.2652 4.99996 19.5196 5.10532 19.7071 5.29285C19.8946 5.48039 20 5.73474 20 5.99996V13Z"
                                            stroke="white" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg text-white mb-1">Keamanan Blockchain</h3>
                                    <p class="text-base text-[#BEDBFF]/70">Enkripsi tingkat enterprise</p>
                                </div>
                            </div>
                        </div>

                        <!-- Feature 2 -->
                        <div class="bg-white/5 border border-white/10 rounded-2xl p-6">
                            <div class="flex items-center gap-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-[14px] flex items-center justify-center"
                                    style="background: linear-gradient(135deg, #2B7FFF 0%, #00B8DB 100%);">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M19 7V4C19 3.73478 18.8946 3.48043 18.7071 3.29289C18.5196 3.10536 18.2652 3 18 3H5C4.46957 3 3.96086 3.21071 3.58579 3.58579C3.21071 3.96086 3 4.46957 3 5C3 5.53043 3.21071 6.03914 3.58579 6.41421C3.96086 6.78929 4.46957 7 5 7H20C20.2652 7 20.5196 7.10536 20.7071 7.29289C20.8946 7.48043 21 7.73478 21 8V12M21 12H18C17.4696 12 16.9609 12.2107 16.5858 12.5858C16.2107 12.9609 16 13.4696 16 14C16 14.5304 16.2107 15.0391 16.5858 15.4142C16.9609 15.7893 17.4696 16 18 16H21C21.2652 16 21.5196 15.8946 21.7071 15.7071C21.8946 15.5196 22 15.2652 22 15V13C22 12.7348 21.8946 12.4804 21.7071 12.2929C21.5196 12.1054 21.2652 12 21 12Z"
                                            stroke="white" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M3 5V19C3 19.5304 3.21071 20.0391 3.58579 20.4142C3.96086 20.7893 4.46957 21 5 21H20C20.2652 21 20.5196 20.8946 20.7071 20.7071C20.8946 20.5196 21 20.2652 21 20V16"
                                            stroke="white" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg text-white mb-1">Web3 Ready</h3>
                                    <p class="text-base text-[#BEDBFF]/70">Login dengan crypto wallet</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT SECTION – LOGIN CARD -->
                <div class="w-full max-w-[552px] mx-auto">
                    <div
                        class="bg-white/10 border border-white/20 rounded-3xl p-10 shadow-[0_25px_50px_-12px_rgba(0,0,0,0.25)]">

                        <!-- Tab Navigation -->
                        <div class="bg-white/5 border border-white/10 rounded-lg p-1 mb-8 flex">
                            <button onclick="switchTab('email')" id="emailTabBtn"
                                class="flex-1 flex items-center justify-center gap-3 px-6 py-2 text-sm rounded-md transition-all"
                                style="background: linear-gradient(180deg, #1E3A8F 0%, #3B82F6 100%); color: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px -1px rgba(0,0,0,0.1);">
                                <svg class="w-4 h-4" viewBox="0 0 16 16" fill="none">
                                    <rect x="2" y="4" width="12" height="9" rx="1" stroke="currentColor"
                                        stroke-width="1.33" />
                                    <path d="M2 6l6 4 6-4" stroke="currentColor" stroke-width="1.33" />
                                </svg>
                                <span>Email</span>
                            </button>
                            <button onclick="switchTab('wallet')" id="walletTabBtn"
                                class="flex-1 flex items-center justify-center gap-3 px-6 py-2 text-sm text-white/70 rounded-md transition-all">
                                <svg class="w-4 h-4" viewBox="0 0 16 16" fill="none">
                                    <path d="M2 2h12v7H2z" stroke="currentColor" stroke-width="1.33" />
                                    <rect x="2" y="3.33" width="12" height="10.67" rx="1.33" stroke="currentColor"
                                        stroke-width="1.33" />
                                </svg>
                                <span>Wallet</span>
                            </button>
                        </div>

                        <!-- TAB EMAIL -->
                        <div class="space-y-6" id="emailTab">
                            <!-- Quick Guide Box -->
                            <div
                                class="bg-gradient-to-r from-[#1E3A8F]/30 to-[#3B82F6]/20 border border-[#3B82F6]/30 rounded-2xl p-5">
                                <div class="flex gap-3">
                                    <svg class="w-5 h-5 text-[#8EC5FF] flex-shrink-0 mt-0.5" viewBox="0 0 20 20"
                                        fill="none">
                                        <circle cx="10" cy="10" r="8.33" stroke="currentColor" stroke-width="1.67" />
                                        <line x1="10" y1="10" x2="10" y2="6.67" stroke="currentColor"
                                            stroke-width="1.67" />
                                        <circle cx="10" cy="13.33" r="0.83" fill="currentColor" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm text-white font-semibold mb-2 flex items-center gap-2">
                                            Panduan :
                                        </p>
                                        <ul class="text-xs text-[#BEDBFF]/90 space-y-1.5 leading-relaxed">
                                            <li><span class="font-semibold">• Email biasa?</span> Gunakan form email
                                                &amp; password di bawah.</li>
                                            <li><span class="font-semibold">• Lembaga / Instansi?</span> Gunakan akun
                                                Google lembaga/instansi di bagian <span
                                                    class="font-semibold">Lainnya</span>.</li>
                                            <li><span class="font-semibold">• Support login Web3</span> tersedia di tab
                                                Wallet.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            {{-- FORM EMAIL / PASSWORD --}}
                            <form action="{{ route('login.email') }}" method="POST" class="space-y-5 pt-2">
                                @csrf

                                {{-- Email --}}
                                <div class="space-y-2">
                                    <label for="email" class="block text-sm font-medium text-[#BEDBFF]">Email</label>
                                    <input id="email" name="email" type="email" autocomplete="email" required
                                        value="{{ old('email') }}"
                                        class="w-full rounded-xl border border-white/20 bg-white/5 px-4 py-3
                                                  text-sm text-white placeholder:text-[#9CA3AF]
                                                  focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70 focus:border-transparent"
                                        placeholder="Masukkan email">
                                    @error('email')
                                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Password --}}
                                <div class="space-y-2">
                                    <label for="password"
                                        class="block text-sm font-medium text-[#BEDBFF]">Password</label>
                                    <input id="password" name="password" type="password" autocomplete="current-password"
                                        required
                                        class="w-full rounded-xl border border-white/20 bg-white/5 px-4 py-3
                                                  text-sm text-white placeholder:text-[#9CA3AF]
                                                  focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70 focus:border-transparent"
                                        placeholder="Masukkan password">
                                    @error('password')
                                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Google reCAPTCHA --}}
                                @if(config('recaptcha.enabled') && config('recaptcha.site_key'))
                                    <div class="space-y-2">
                                        <div class="flex justify-center">
                                            <div class="g-recaptcha rounded-xl overflow-hidden"
                                                data-sitekey="{{ config('recaptcha.site_key') }}" data-theme="dark"></div>
                                        </div>
                                        @error('g-recaptcha-response')
                                            <p class="mt-1 text-xs text-red-400 text-center">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif

                                {{-- Tombol Masuk --}}
                                <button type="submit" class="mt-2 inline-flex w-full items-center justify-center rounded-xl
                                               bg-gradient-to-b from-[#1E3A8F] to-[#3B82F6]
                                               px-4 py-3 text-sm font-semibold text-white
                                               shadow-[0_10px_20px_rgba(37,99,235,0.45)]
                                               hover:brightness-110 transition">
                                    Masuk
                                </button>

                                <div class="flex justify-between text-xs md:text-sm pt-2">
                                    <a href="{{ route('password.request') }}"
                                        class="text-[#8EC5FF] hover:text-[#BEDBFF] transition-colors">
                                        Lupa Password?
                                    </a>
                                    <a href="{{ route('register') }}"
                                        class="text-[#8EC5FF] hover:text-[#BEDBFF] transition-colors flex items-center gap-1">
                                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M8 10.5V9.5C8 8.96957 7.78929 8.46086 7.41421 8.08579C7.03914 7.71071 6.53043 7.5 6 7.5H3C2.46957 7.5 1.96086 7.71071 1.58579 8.08579C1.21071 8.46086 1 8.96957 1 9.5V10.5"
                                                stroke="#8EC5FF" stroke-linecap="round" stroke-linejoin="round" />
                                            <path
                                                d="M4.5 5.5C5.60457 5.5 6.5 4.60457 6.5 3.5C6.5 2.39543 5.60457 1.5 4.5 1.5C3.39543 1.5 2.5 2.39543 2.5 3.5C2.5 4.60457 3.39543 5.5 4.5 5.5Z"
                                                stroke="#8EC5FF" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M9.5 4V7" stroke="#8EC5FF" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M11 5.5H8" stroke="#8EC5FF" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        Daftar Akun Baru
                                    </a>
                                </div>
                            </form>

                            {{-- LAINNYA: GOOGLE LOGIN --}}
                            <div class="space-y-2 pt-6 border-t border-white/10">
                                <p class="text-xs font-semibold text-[#BEDBFF] tracking-wide uppercase">
                                    Lainnya
                                </p>
                                <a href="{{ route('google.redirect') }}"
                                    class="w-full bg-white hover:bg-gray-100 rounded-xl p-4 transition-all flex items-center justify-center gap-3 shadow-lg">
                                    <svg class="w-6 h-6" viewBox="0 0 24 24">
                                        <path fill="#4285F4"
                                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                        <path fill="#34A853"
                                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                        <path fill="#FBBC05"
                                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                                        <path fill="#EA4335"
                                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                                    </svg>
                                    <span class="text-gray-700 font-medium text-base">Login dengan Google</span>
                                </a>

                                {{-- Legal Consent Text --}}
                                <p class="mt-3 text-xs text-[#BEDBFF]/70 text-center">
                                    Dengan login, Anda menyetujui<br>
                                    <button type="button" onclick="showLegalPopup('syarat')"
                                        class="text-white hover:underline font-medium">Syarat & Ketentuan</button>
                                    dan
                                    <button type="button" onclick="showLegalPopup('privasi')"
                                        class="text-white hover:underline font-medium">Kebijakan Privasi</button>
                                </p>
                            </div>
                        </div>

                        <!-- TAB WALLET -->
                        <div class="space-y-6" id="walletTab" style="display:none;">
                            <div class="text-center space-y-2">
                                <h3 class="text-xl text-white">Connect Your Wallet</h3>
                                <p class="text-base text-[#BEDBFF]/70">Pilih wallet untuk login dengan Web3</p>
                            </div>

                            {{-- Status & Error messages --}}
                            <div id="wallet-status"
                                class="hidden text-center text-sm text-white bg-[#05DF72]/20 border border-[#05DF72]/30 rounded-lg p-3">
                            </div>
                            <div id="wallet-error"
                                class="hidden text-center text-sm text-red-400 bg-red-500/20 border border-red-500/30 rounded-lg p-3">
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <!-- MetaMask -->
                                <button
                                    class="bg-white/5 border border-[#F6851B]/30 rounded-xl p-4 hover:bg-[#F6851B]/10 hover:border-[#F6851B]/50 transition-all group"
                                    onclick="connectWallet('metamask', event)">
                                    <div class="flex flex-col items-center gap-3">
                                        <div
                                            class="w-14 h-14 rounded-2xl flex items-center justify-center bg-gradient-to-br from-[#F6851B] to-[#E2761B]">
                                            <svg width="32" height="32" viewBox="0 0 318 318" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M274.1 35.5L174.6 109.4L193 65.8L274.1 35.5Z" fill="#E2761B"
                                                    stroke="#E2761B" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M44.4 35.5L143.1 110.1L125.6 65.8L44.4 35.5Z" fill="#E4761B"
                                                    stroke="#E4761B" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M238.3 206.8L211.8 247.4L268.5 262.9L284.8 207.7L238.3 206.8Z"
                                                    fill="#E4761B" stroke="#E4761B" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M33.9 207.7L50.1 262.9L106.8 247.4L80.3 206.8L33.9 207.7Z"
                                                    fill="#E4761B" stroke="#E4761B" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M103.6 138.2L87.8 162.1L143.8 164.6L141.7 104.2L103.6 138.2Z"
                                                    fill="#E4761B" stroke="#E4761B" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M214.9 138.2L176.3 103.4L174.6 164.6L230.5 162.1L214.9 138.2Z"
                                                    fill="#E4761B" stroke="#E4761B" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M106.8 247.4L140.1 230.9L111.4 208.1L106.8 247.4Z"
                                                    fill="#E4761B" stroke="#E4761B" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M178.4 230.9L211.8 247.4L207.1 208.1L178.4 230.9Z"
                                                    fill="#E4761B" stroke="#E4761B" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M211.8 247.4L178.4 230.9L181.1 253.3L180.8 262.1L211.8 247.4Z"
                                                    fill="#D7C1B3" stroke="#D7C1B3" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M106.8 247.4L137.8 262.1L137.6 253.3L140.1 230.9L106.8 247.4Z"
                                                    fill="#D7C1B3" stroke="#D7C1B3" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M138.3 193.5L110.6 185.3L130.5 176.1L138.3 193.5Z"
                                                    fill="#233447" stroke="#233447" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M180.2 193.5L188 176.1L208 185.3L180.2 193.5Z" fill="#233447"
                                                    stroke="#233447" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M106.8 247.4L111.6 206.8L80.3 207.7L106.8 247.4Z"
                                                    fill="#CD6116" stroke="#CD6116" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M207 206.8L211.8 247.4L238.3 207.7L207 206.8Z" fill="#CD6116"
                                                    stroke="#CD6116" stroke-linecap="round" stroke-linejoin="round" />
                                                <path
                                                    d="M230.5 162.1L174.6 164.6L180.3 193.5L188.1 176.1L208.1 185.3L230.5 162.1Z"
                                                    fill="#CD6116" stroke="#CD6116" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M110.6 185.3L130.6 176.1L138.3 193.5L144 164.6L87.8 162.1L110.6 185.3Z"
                                                    fill="#CD6116" stroke="#CD6116" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M87.8 162.1L111.4 208.1L110.6 185.3L87.8 162.1Z" fill="#E4751F"
                                                    stroke="#E4751F" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M208.1 185.3L207.1 208.1L230.5 162.1L208.1 185.3Z"
                                                    fill="#E4751F" stroke="#E4751F" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M144 164.6L138.3 193.5L145.4 229.6L147 182.6L144 164.6Z"
                                                    fill="#E4751F" stroke="#E4751F" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M174.6 164.6L171.7 182.4L173.1 229.6L180.3 193.5L174.6 164.6Z"
                                                    fill="#E4751F" stroke="#E4751F" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M180.3 193.5L173.1 229.6L178.4 230.9L207.1 208.1L208.1 185.3L180.3 193.5Z"
                                                    fill="#F6851B" stroke="#F6851B" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M110.6 185.3L111.4 208.1L140.1 230.9L145.4 229.6L138.3 193.5L110.6 185.3Z"
                                                    fill="#F6851B" stroke="#F6851B" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M180.8 262.1L181.1 253.3L178.6 251.1H139.9L137.6 253.3L137.8 262.1L106.8 247.4L117.8 256.4L139.5 271.6H178.9L200.8 256.4L211.8 247.4L180.8 262.1Z"
                                                    fill="#C0AD9E" stroke="#C0AD9E" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M178.4 230.9L173.1 229.6H145.4L140.1 230.9L137.6 253.3L139.9 251.1H178.6L181.1 253.3L178.4 230.9Z"
                                                    fill="#161616" stroke="#161616" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M278.3 114.2L287.9 67L274.1 35.5L178.4 106.8L214.9 138.2L267.2 152.5L278.8 139L273.8 135.4L281.8 128.1L275.6 123.3L283.6 117.2L278.3 114.2Z"
                                                    fill="#763D16" stroke="#763D16" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M30.8 67L40.4 114.2L34.9 117.2L42.9 123.3L36.8 128.1L44.8 135.4L39.8 139L51.3 152.5L103.6 138.2L140.1 106.8L44.4 35.5L30.8 67Z"
                                                    fill="#763D16" stroke="#763D16" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M267.2 152.5L214.9 138.2L230.5 162.1L207.1 208.1L238.3 207.7H284.8L267.2 152.5Z"
                                                    fill="#F6851B" stroke="#F6851B" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M103.6 138.2L51.3 152.5L33.9 207.7H80.3L111.4 208.1L87.8 162.1L103.6 138.2Z"
                                                    fill="#F6851B" stroke="#F6851B" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M174.6 164.6L178.4 106.8L193.1 65.8H125.6L140.1 106.8L144 164.6L145.3 182.8L145.4 229.6H173.1L173.3 182.8L174.6 164.6Z"
                                                    fill="#F6851B" stroke="#F6851B" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-sm text-white font-semibold">MetaMask</p>
                                            <p class="text-xs text-[#BEDBFF]/60 mt-0.5">Browser Extension</p>
                                        </div>
                                    </div>
                                </button>

                                <!-- Coinbase Wallet -->
                                <button
                                    class="bg-white/5 border border-[#0052FF]/30 rounded-xl p-4 hover:bg-[#0052FF]/10 hover:border-[#0052FF]/50 transition-all group"
                                    onclick="connectWallet('coinbase', event)">
                                    <div class="flex flex-col items-center gap-3">
                                        <div
                                            class="w-14 h-14 rounded-2xl flex items-center justify-center bg-gradient-to-br from-[#0052FF] to-[#0040CC]">
                                            <svg width="28" height="28" viewBox="0 0 40 40" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="20" cy="20" r="16" fill="white" />
                                                <rect x="14" y="14" width="12" height="12" rx="2" fill="#0052FF" />
                                            </svg>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-sm text-white font-semibold">Coinbase</p>
                                            <p class="text-xs text-[#BEDBFF]/60 mt-0.5">Coinbase Wallet</p>
                                        </div>
                                    </div>
                                </button>

                                <!-- Trust Wallet -->
                                <button
                                    class="bg-white/5 border border-[#3375BB]/30 rounded-xl p-4 hover:bg-[#3375BB]/10 hover:border-[#3375BB]/50 transition-all group"
                                    onclick="connectWallet('trust', event)">
                                    <div class="flex flex-col items-center gap-3">
                                        <div
                                            class="w-14 h-14 rounded-2xl flex items-center justify-center bg-gradient-to-br from-[#3375BB] to-[#1A5C9E]">
                                            <svg width="28" height="28" viewBox="0 0 40 40" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M20 6C20 6 8 10 8 20C8 30 20 34 20 34C20 34 32 30 32 20C32 10 20 6 20 6Z"
                                                    fill="white" />
                                                <path
                                                    d="M20 10C20 10 12 13 12 20C12 27 20 30 20 30C20 30 28 27 28 20C28 13 20 10 20 10Z"
                                                    fill="#3375BB" />
                                            </svg>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-sm text-white font-semibold">Trust Wallet</p>
                                            <p class="text-xs text-[#BEDBFF]/60 mt-0.5">Mobile Wallet</p>
                                        </div>
                                    </div>
                                </button>

                                <!-- WalletConnect -->
                                <button
                                    class="bg-white/5 border border-[#3B99FC]/30 rounded-xl p-4 hover:bg-[#3B99FC]/10 hover:border-[#3B99FC]/50 transition-all group"
                                    onclick="connectWallet('walletconnect', event)">
                                    <div class="flex flex-col items-center gap-3">
                                        <div
                                            class="w-14 h-14 rounded-2xl flex items-center justify-center bg-gradient-to-br from-[#3B99FC] to-[#2B7FE0]">
                                            <svg width="28" height="28" viewBox="0 0 40 40" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M12 16C16.5 12 23.5 12 28 16L29.5 17.5C29.7 17.7 29.7 18 29.5 18.2L27.5 20C27.4 20.1 27.2 20.1 27.1 20L26 19C22.5 15.5 17.5 15.5 14 19L12.8 20.2C12.7 20.3 12.5 20.3 12.4 20.2L10.4 18.4C10.2 18.2 10.2 17.9 10.4 17.7L12 16Z"
                                                    fill="white" />
                                                <path
                                                    d="M32 20L34 22C34.2 22.2 34.2 22.5 34 22.7L26 30.5C25.8 30.7 25.4 30.7 25.2 30.5L20 25.5C19.95 25.45 19.85 25.45 19.8 25.5L14.6 30.5C14.4 30.7 14 30.7 13.8 30.5L6 22.7C5.8 22.5 5.8 22.2 6 22L8 20C8.2 19.8 8.5 19.8 8.7 20L14 25C14.05 25.05 14.15 25.05 14.2 25L19.4 20C19.6 19.8 20 19.8 20.2 20L25.4 25C25.45 25.05 25.55 25.05 25.6 25L30.9 20C31.1 19.8 31.4 19.8 31.6 20L32 20Z"
                                                    fill="white" />
                                            </svg>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-sm text-white font-semibold">WalletConnect</p>
                                            <p class="text-xs text-[#BEDBFF]/60 mt-0.5">Scan QR Code</p>
                                        </div>
                                    </div>
                                </button>
                            </div>

                            <!-- Info Box Bottom -->
                            <div class="bg-[#1E3A8F]/30 border border-[#3B82F6]/30 rounded-[14px] p-4">
                                <div class="flex gap-3">
                                    <svg class="w-5 h-5 text-[#8EC5FF] flex-shrink-0 mt-0.5" viewBox="0 0 20 20"
                                        fill="none">
                                        <circle cx="10" cy="10" r="8.33" stroke="currentColor" stroke-width="1.67" />
                                        <line x1="10" y1="10" x2="10" y2="6.67" stroke="currentColor"
                                            stroke-width="1.67" />
                                        <circle cx="10" cy="13.33" r="0.83" fill="currentColor" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm text-white mb-1">Tentang Web3 Login</p>
                                        <p class="text-xs text-[#BEDBFF]/70 leading-relaxed">
                                            Login dengan wallet memberikan keamanan ekstra. Pilih wallet yang sudah
                                            terinstall di browser atau device Anda.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Links -->
                    <div class="mt-6 flex flex-col items-center gap-3">
                        <button
                            class="flex items-center gap-3 px-6 py-2 text-sm text-white hover:opacity-80 transition-opacity font-bold"
                            onclick="window.location.href='{{ url('/') }}'">
                            <svg class="w-4 h-4" viewBox="0 0 16 16" fill="none">
                                <path d="M10 3.33L5 8l5 4.67" stroke="currentColor" stroke-width="1.33" />
                            </svg>
                            <span>Kembali ke Halaman Utama</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Hidden form untuk wallet login --}}
    <form id="walletLoginForm" method="POST" action="{{ route('login.wallet') }}" class="hidden">
        @csrf
        <input type="hidden" id="wallet_address" name="wallet_address">
    </form>

    {{-- Legal Popup Script --}}
    <script>
        function showLegalPopup(type) {
            let title, content;

            if (type === 'privasi') {
                title = 'Kebijakan Privasi';
                content = `
                    <div class="text-left text-sm space-y-4 max-h-96 overflow-y-auto">
                        <p><strong>1. Informasi yang Kami Kumpulkan</strong><br>
                        Nama, email, Google ID, alamat wallet blockchain, dan data sertifikat.</p>
                        
                        <p><strong>2. Penggunaan Informasi</strong><br>
                        Untuk menyediakan layanan verifikasi sertifikat dan mengelola akun Anda.</p>
                        
                        <p><strong>3. Keamanan Data</strong><br>
                        Kami menggunakan enkripsi dan teknologi blockchain untuk melindungi data.</p>
                        
                        <p><strong>4. Berbagi Informasi</strong><br>
                        Kami tidak menjual data Anda. Berbagi hanya dengan persetujuan atau kewajiban hukum.</p>
                        
                        <p><strong>5. Hak Anda</strong><br>
                        Anda dapat mengakses, memperbarui, atau menghapus data pribadi kapan saja.</p>
                        
                        <p class="text-xs text-gray-400 mt-4">Lihat selengkapnya di <a href="${'{{ route("privasi") }}'}" class="text-blue-400 hover:underline">halaman Privasi</a>.</p>
                    </div>
                `;
            } else if (type === 'syarat') {
                title = 'Syarat & Ketentuan';
                content = `
                    <div class="text-left text-sm space-y-4 max-h-96 overflow-y-auto">
                        <p><strong>1. Penerimaan Syarat</strong><br>
                        Dengan menggunakan SertiKu, Anda menyetujui syarat dan ketentuan ini.</p>
                        
                        <p><strong>2. Deskripsi Layanan</strong><br>
                        Platform penerbitan dan verifikasi sertifikat digital berbasis blockchain.</p>
                        
                        <p><strong>3. Akun Pengguna</strong><br>
                        Anda bertanggung jawab menjaga kerahasiaan kredensial akun.</p>
                        
                        <p><strong>4. Penggunaan yang Dilarang</strong><br>
                        Dilarang membuat sertifikat palsu atau menggunakan layanan untuk aktivitas ilegal.</p>
                        
                        <p><strong>5. Hak Kekayaan Intelektual</strong><br>
                        Semua konten platform dilindungi hak cipta SertiKu.</p>
                        
                        <p class="text-xs text-gray-400 mt-4">Lihat selengkapnya di <a href="${'{{ route("syarat") }}'}" class="text-blue-400 hover:underline">halaman Syarat & Ketentuan</a>.</p>
                    </div>
                `;
            } else if (type === 'cookie') {
                title = 'Kebijakan Cookie';
                content = `
                    <div class="text-left text-sm space-y-4 max-h-96 overflow-y-auto">
                        <p><strong>Apa itu Cookie?</strong><br>
                        File teks kecil yang disimpan di perangkat untuk mengingat preferensi Anda.</p>
                        
                        <p><strong>Cookie yang Kami Gunakan:</strong></p>
                        <ul class="list-disc list-inside ml-2 space-y-1">
                            <li><strong>Esensial</strong> - Untuk login dan keamanan</li>
                            <li><strong>Analitik</strong> - Untuk memahami penggunaan website</li>
                            <li><strong>Fungsional</strong> - Untuk mengingat preferensi</li>
                        </ul>
                        
                        <p><strong>Mengelola Cookie</strong><br>
                        Anda dapat mengontrol cookie melalui pengaturan browser.</p>
                        
                        <p class="text-xs text-gray-400 mt-4">Lihat selengkapnya di <a href="${'{{ route("cookie") }}'}" class="text-blue-400 hover:underline">halaman Kebijakan Cookie</a>.</p>
                    </div>
                `;
            }

            Swal.fire({
                title: title,
                html: content,
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#3B82F6',
                width: '500px',
                customClass: {
                    popup: 'rounded-2xl'
                }
            });
        }
    </script>

</x-layouts.app>