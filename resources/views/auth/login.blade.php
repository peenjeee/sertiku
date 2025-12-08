{{-- resources/views/auth/login.blade.php --}}
<x-layouts.app title="SertiKu – Login">

    {{-- Script tab + wallet connect (ethers.js) --}}
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

        // Wallet connect (ethers.js)
        async function connectWallet(event) {
            event.preventDefault();

            if (!window.ethereum) {
                alert('MetaMask tidak terdeteksi. Silakan install MetaMask terlebih dahulu.');
                window.open('https://metamask.io/download/', '_blank');
                return;
            }

            try {
                const provider = new ethers.providers.Web3Provider(window.ethereum);
                await provider.send('eth_requestAccounts', []);
                const signer = provider.getSigner();
                const address = await signer.getAddress();

                document.getElementById('wallet_address').value = address;
                document.getElementById('walletLoginForm').submit();
            } catch (error) {
                console.error(error);
                alert('Gagal menghubungkan wallet.');
            }
        }
    </script>

    {{-- CDN ethers.js --}}
    <script src="https://cdn.jsdelivr.net/npm/ethers@5.7.2/dist/ethers.umd.min.js"></script>

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
                                    <path d="M47.9953 6.39941H15.9984C10.697 6.39941 6.39935 10.6971 6.39935 15.9985V47.9953C6.39935 53.2968 10.697 57.5944 15.9984 57.5944H47.9953C53.2967 57.5944 57.5943 53.2968 57.5943 47.9953V15.9985C57.5943 10.6971 53.2967 6.39941 47.9953 6.39941Z"
                                          stroke="white" stroke-width="3.83962"/>
                                    <path d="M45.4356 14.3987H18.5582C17.1445 14.3987 15.9984 15.5447 15.9984 16.9584V35.8366C15.9984 37.2503 17.1445 38.3963 18.5582 38.3963H45.4356C46.8493 38.3963 47.9953 37.2503 47.9953 35.8366V16.9584C47.9953 15.5447 46.8493 14.3987 45.4356 14.3987Z"
                                          stroke="white" stroke-width="2.55975"/>
                                    <path d="M20.7979 20.7979H33.5967" stroke="white" stroke-width="1.91981"
                                          stroke-linecap="round"/>
                                    <path d="M20.7979 25.5974H30.397" stroke="white" stroke-width="1.91981"
                                          stroke-linecap="round"/>
                                    <path d="M23.9977 52.1549C27.1785 52.1549 29.7571 49.5763 29.7571 46.3954C29.7571 43.2146 27.1785 40.636 23.9977 40.636C20.8168 40.636 18.2382 43.2146 18.2382 46.3954C18.2382 49.5763 20.8168 52.1549 23.9977 52.1549Z"
                                          fill="white"/>
                                    <path d="M23.9977 40.636L21.7579 49.5951L23.9977 47.9953L26.2374 49.5951L23.9977 40.636Z"
                                          fill="white"/>
                                    <path d="M37.4364 27.1973H33.5967V31.0369H37.4364V27.1973Z" fill="white"/>
                                    <path d="M43.1958 27.1973H39.3562V31.0369H43.1958V27.1973Z" fill="white"/>
                                    <path d="M37.4364 32.9568H33.5967V36.7964H37.4364V32.9568Z" fill="white"/>
                                    <path d="M43.1958 32.9568H39.3562V36.7964H43.1958V32.9568Z" fill="white"/>
                                    <path d="M36.7964 29.1172H35.5166V30.3971H36.7964V29.1172Z" fill="#1F3A5F"/>
                                    <path d="M42.5559 29.1172H41.276V30.3971H42.5559V29.1172Z" fill="#1F3A5F"/>
                                    <path d="M36.7964 34.8765H35.5166V36.1563H36.7964V34.8765Z" fill="#1F3A5F"/>
                                    <path d="M42.5559 34.8765H41.276V36.1563H42.5559V34.8765Z" fill="#1F3A5F"/>
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
                                        <path d="M20 13C20 18 16.5 20.5 12.34 21.95C12.1222 22.0238 11.8855 22.0202 11.67 21.94C7.5 20.5 4 18 4 13V5.99996C4 5.73474 4.10536 5.48039 4.29289 5.29285C4.48043 5.10532 4.73478 4.99996 5 4.99996C7 4.99996 9.5 3.79996 11.24 2.27996C11.4519 2.09896 11.7214 1.99951 12 1.99951C12.2786 1.99951 12.5481 2.09896 12.76 2.27996C14.51 3.80996 17 4.99996 19 4.99996C19.2652 4.99996 19.5196 5.10532 19.7071 5.29285C19.8946 5.48039 20 5.73474 20 5.99996V13Z"
                                              stroke="white" stroke-width="2" stroke-linecap="round"
                                              stroke-linejoin="round"/>
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
                                        <path d="M19 7V4C19 3.73478 18.8946 3.48043 18.7071 3.29289C18.5196 3.10536 18.2652 3 18 3H5C4.46957 3 3.96086 3.21071 3.58579 3.58579C3.21071 3.96086 3 4.46957 3 5C3 5.53043 3.21071 6.03914 3.58579 6.41421C3.96086 6.78929 4.46957 7 5 7H20C20.2652 7 20.5196 7.10536 20.7071 7.29289C20.8946 7.48043 21 7.73478 21 8V12M21 12H18C17.4696 12 16.9609 12.2107 16.5858 12.5858C16.2107 12.9609 16 13.4696 16 14C16 14.5304 16.2107 15.0391 16.5858 15.4142C16.9609 15.7893 17.4696 16 18 16H21C21.2652 16 21.5196 15.8946 21.7071 15.7071C21.8946 15.5196 22 15.2652 22 15V13C22 12.7348 21.8946 12.4804 21.7071 12.2929C21.5196 12.1054 21.2652 12 21 12Z"
                                              stroke="white" stroke-width="2" stroke-linecap="round"
                                              stroke-linejoin="round"/>
                                        <path d="M3 5V19C3 19.5304 3.21071 20.0391 3.58579 20.4142C3.96086 20.7893 4.46957 21 5 21H20C20.2652 21 20.5196 20.8946 20.7071 20.7071C20.8946 20.5196 21 20.2652 21 20V16"
                                              stroke="white" stroke-width="2" stroke-linecap="round"
                                              stroke-linejoin="round"/>
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
                    <div class="bg-white/10 border border-white/20 rounded-3xl p-10 shadow-[0_25px_50px_-12px_rgba(0,0,0,0.25)]">

                        <!-- Tab Navigation -->
                        <div class="bg-white/5 border border-white/10 rounded-lg p-1 mb-8 flex">
                            <button onclick="switchTab('email')" id="emailTabBtn"
                                    class="flex-1 flex items-center justify-center gap-3 px-6 py-2 text-sm rounded-md transition-all"
                                    style="background: linear-gradient(180deg, #1E3A8F 0%, #3B82F6 100%); color: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px -1px rgba(0,0,0,0.1);">
                                <svg class="w-4 h-4" viewBox="0 0 16 16" fill="none">
                                    <rect x="2" y="4" width="12" height="9" rx="1" stroke="currentColor"
                                          stroke-width="1.33"/>
                                    <path d="M2 6l6 4 6-4" stroke="currentColor" stroke-width="1.33"/>
                                </svg>
                                <span>Email</span>
                            </button>
                            <button onclick="switchTab('wallet')" id="walletTabBtn"
                                    class="flex-1 flex items-center justify-center gap-3 px-6 py-2 text-sm text-white/70 rounded-md transition-all">
                                <svg class="w-4 h-4" viewBox="0 0 16 16" fill="none">
                                    <path d="M2 2h12v7H2z" stroke="currentColor" stroke-width="1.33"/>
                                    <rect x="2" y="3.33" width="12" height="10.67" rx="1.33"
                                          stroke="currentColor" stroke-width="1.33"/>
                                </svg>
                                <span>Wallet</span>
                            </button>
                        </div>

                        <!-- TAB EMAIL -->
                        <div class="space-y-6" id="emailTab">
                            <!-- Quick Guide Box -->
                            <div class="bg-gradient-to-r from-[#1E3A8F]/30 to-[#3B82F6]/20 border border-[#3B82F6]/30 rounded-2xl p-5">
                                <div class="flex gap-3">
                                    <svg class="w-5 h-5 text-[#8EC5FF] flex-shrink-0 mt-0.5" viewBox="0 0 20 20" fill="none">
                                        <circle cx="10" cy="10" r="8.33" stroke="currentColor" stroke-width="1.67"/>
                                        <line x1="10" y1="10" x2="10" y2="6.67" stroke="currentColor" stroke-width="1.67"/>
                                        <circle cx="10" cy="13.33" r="0.83" fill="currentColor"/>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm text-white font-semibold mb-2 flex items-center gap-2">
                                            Panduan :
                                        </p>
                                        <ul class="text-xs text-[#BEDBFF]/90 space-y-1.5 leading-relaxed">
                                            <li><span class="font-semibold">• Email biasa?</span> Gunakan form email &amp; password di bawah.</li>
                                            <li><span class="font-semibold">• Lembaga / Instansi?</span> Gunakan akun Google lembaga/instansi di bagian <span class="font-semibold">Lainnya</span>.</li>
                                            <li><span class="font-semibold">• Support login Web3</span> tersedia di tab Wallet.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            {{-- FORM EMAIL / PASSWORD --}}
                            <form action="{{ route('login.email') }}" method="POST" class="space-y-5 pt-2">
                                @csrf

                                {{-- Email --}}
                                <div class="space-y-2">
                                    <label for="email"
                                           class="block text-sm font-medium text-[#BEDBFF]">Email</label>
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
                                    <input id="password" name="password" type="password"
                                           autocomplete="current-password" required
                                           class="w-full rounded-xl border border-white/20 bg-white/5 px-4 py-3
                                                  text-sm text-white placeholder:text-[#9CA3AF]
                                                  focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70 focus:border-transparent"
                                           placeholder="Masukkan password">
                                    @error('password')
                                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Tombol Masuk --}}
                                <button type="submit"
                                        class="mt-2 inline-flex w-full items-center justify-center rounded-xl
                                               bg-gradient-to-b from-[#1E3A8F] to-[#3B82F6]
                                               px-4 py-3 text-sm font-semibold text-white
                                               shadow-[0_10px_20px_rgba(37,99,235,0.45)]
                                               hover:brightness-110 transition">
                                    Masuk
                                </button>

                                <div class="flex justify-between text-xs md:text-sm pt-2">
                                    <a href="#"
                                       class="text-[#8EC5FF] hover:text-[#BEDBFF] transition-colors">
                                        Lupa Password?
                                    </a>
                                    <a href="{{ route('register') }}"
                                       class="text-[#8EC5FF] hover:text-[#BEDBFF] transition-colors flex items-center gap-1">
                                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path d="M8 10.5V9.5C8 8.96957 7.78929 8.46086 7.41421 8.08579C7.03914 7.71071 6.53043 7.5 6 7.5H3C2.46957 7.5 1.96086 7.71071 1.58579 8.08579C1.21071 8.46086 1 8.96957 1 9.5V10.5"
                                                  stroke="#8EC5FF" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M4.5 5.5C5.60457 5.5 6.5 4.60457 6.5 3.5C6.5 2.39543 5.60457 1.5 4.5 1.5C3.39543 1.5 2.5 2.39543 2.5 3.5C2.5 4.60457 3.39543 5.5 4.5 5.5Z"
                                                  stroke="#8EC5FF" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M9.5 4V7" stroke="#8EC5FF" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M11 5.5H8" stroke="#8EC5FF" stroke-linecap="round" stroke-linejoin="round"/>
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
                                              d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                        <path fill="#34A853"
                                              d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                        <path fill="#FBBC05"
                                              d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                        <path fill="#EA4335"
                                              d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                    </svg>
                                    <span class="text-gray-700 font-medium text-base">Login dengan Google</span>
                                </a>

                                <div class="mt-1 text-center text-xs text-gray-400">
                                    <p>Dengan login, Anda menyetujui</p>
                                    <p>
                                        <a href="#" class="text-white hover:text-blue-600 hover:underline">
                                            Syarat &amp; Ketentuan
                                        </a>
                                        dan
                                        <a href="#" class="text-white hover:text-blue-600 hover:underline">
                                            Kebijakan Privasi
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- TAB WALLET -->
                        <div class="space-y-6" id="walletTab" style="display:none;">
                            <div class="text-center space-y-2">
                                <h3 class="text-xl text-white">Connect Your Wallet</h3>
                                <p class="text-base text-[#BEDBFF]/70">Connect untuk login dengan Web3</p>
                            </div>

                            <div class="space-y-3">
                                <!-- MetaMask -->
                                <button
                                    class="w-full bg-white/5 border border-[#FF6900]/30 rounded-lg p-4 hover:bg-white/10 transition-all group"
                                    onclick="connectWallet(event)">
                                    <div class="flex items-center gap-4">
                                        <div class="flex-shrink-0 w-12 h-12 rounded-[14px] flex items-center justify-center"
                                             style="background: linear-gradient(135deg, #FF6900 0%, #FB2C36 100%);">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path d="M19 7V4C19 3.73478 18.8946 3.48043 18.7071 3.29289C18.5196 3.10536 18.2652 3 18 3H5C4.46957 3 3.96086 3.21071 3.58579 3.58579C3.21071 3.96086 3 4.46957 3 5C3 5.53043 3.21071 6.03914 3.58579 6.41421C3.96086 6.78929 4.46957 7 5 7H20C20.2652 7 20.5196 7.10536 20.7071 7.29289C20.8946 7.48043 21 7.73478 21 8V12M21 12H18C17.4696 12 16.9609 12.2107 16.5858 12.5858C16.2107 12.9609 16 13.4696 16 14C16 14.5304 16.2107 15.0391 16.5858 15.4142C16.9609 15.7893 17.4696 16 18 16H21C21.2652 16 21.5196 15.8946 21.7071 15.7071C21.8946 15.5196 22 15.2652 22 15V13C22 12.7348 21.8946 12.4804 21.7071 12.2929C21.5196 12.1054 21.2652 12 21 12Z"
                                                      stroke="white" stroke-width="2" stroke-linecap="round"
                                                      stroke-linejoin="round"/>
                                                <path d="M3 5V19C3 19.5304 3.21071 20.0391 3.58579 20.4142C3.96086 20.7893 4.46957 21 5 21H20C20.2652 21 20.5196 20.8946 20.7071 20.7071C20.8946 20.5196 21 20.2652 21 20V16"
                                                      stroke="white" stroke-width="2" stroke-linecap="round"
                                                      stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 text-left">
                                            <p class="text-sm text-white font-semibold">Connect MetaMask</p>
                                            <p class="text-sm text-[#BEDBFF]/70">
                                                Klik untuk menghubungkan wallet Anda
                                            </p>
                                        </div>
                                    </div>
                                </button>
                            </div>

                            <!-- Info Box Bottom -->
                            <div class="bg-[#1E3A8F]/30 border border-[#3B82F6]/30 rounded-[14px] p-4">
                                <div class="flex gap-3">
                                    <svg class="w-5 h-5 text-[#8EC5FF] flex-shrink-0 mt-0.5" viewBox="0 0 20 20" fill="none">
                                        <circle cx="10" cy="10" r="8.33" stroke="currentColor" stroke-width="1.67"/>
                                        <line x1="10" y1="10" x2="10" y2="6.67" stroke="currentColor" stroke-width="1.67"/>
                                        <circle cx="10" cy="13.33" r="0.83" fill="currentColor"/>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm text-white mb-1">Tentang Web3 Login</p>
                                        <p class="text-xs text-[#BEDBFF]/70 leading-relaxed">
                                            Login dengan wallet memberikan keamanan ekstra dan kontrol penuh
                                            atas identitas digital Anda.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Links -->
                    <div class="mt-6 flex flex-col items-center gap-3">
                        <button class="flex items-center gap-3 px-6 py-2 text-sm text-white hover:opacity-80 transition-opacity font-bold"
                                onclick="window.location.href='{{ url('/') }}'">
                            <svg class="w-4 h-4" viewBox="0 0 16 16" fill="none">
                                <path d="M10 3.33L5 8l5 4.67" stroke="currentColor" stroke-width="1.33"/>
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

</x-layouts.app>
