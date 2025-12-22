{{-- PWA Install Prompt Component - Enhanced Version --}}
<div id="pwa-install-prompt" class="fixed bottom-4 right-4 z-50 hidden max-w-sm">
    <div class="bg-[#0A1628]/95 backdrop-blur-xl border border-[#3B82F6]/30 rounded-2xl shadow-2xl p-5 animate-bounce-in">
        {{-- Close Button --}}
        <button onclick="dismissPwaPrompt()" class="absolute top-3 right-3 text-white/50 hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        {{-- Content --}}
        <div class="flex items-start gap-4">
            {{-- App Icon --}}
            <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-[#3B82F6] to-[#8B5CF6] rounded-2xl flex items-center justify-center shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>

            {{-- Text --}}
            <div class="flex-1 pr-4">
                <p class="text-white font-semibold text-base mb-1">Install SertiKu</p>
                <p class="text-[#8EC5FF]/70 text-sm mb-4">Tambahkan ke home screen untuk akses lebih cepat tanpa browser!</p>

                {{-- Benefits --}}
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="inline-flex items-center gap-1 text-xs text-[#10B981] bg-[#10B981]/10 px-2 py-1 rounded-full">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Lebih Cepat
                    </span>
                    <span class="inline-flex items-center gap-1 text-xs text-[#3B82F6] bg-[#3B82F6]/10 px-2 py-1 rounded-full">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        Offline Mode
                    </span>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-2">
                    <button onclick="dismissPwaPrompt()" class="px-4 py-2 text-sm text-[#8EC5FF]/70 hover:text-white transition-colors">
                        Nanti
                    </button>
                    <button onclick="installPwa()" class="flex-1 px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-[#3B82F6] to-[#8B5CF6] rounded-xl hover:opacity-90 transition-all shadow-lg flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Install Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- iOS Install Instructions Modal --}}
<div id="ios-install-modal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="bg-[#0A1628] border border-white/10 rounded-2xl p-6 max-w-sm mx-4 shadow-2xl">
        <div class="text-center mb-4">
            <div class="w-16 h-16 bg-gradient-to-br from-[#3B82F6] to-[#8B5CF6] rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                </svg>
            </div>
            <h3 class="text-white font-semibold text-lg mb-2">Install di iPhone/iPad</h3>
            <p class="text-[#8EC5FF]/70 text-sm">Ikuti langkah berikut untuk menambahkan SertiKu ke home screen:</p>
        </div>

        <ol class="space-y-3 mb-6 text-sm text-[#BEDBFF]">
            <li class="flex items-start gap-3">
                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-[#3B82F6]/20 text-[#3B82F6] flex items-center justify-center text-xs font-bold">1</span>
                <span>Tap ikon <strong>Share</strong> di Safari (kotak dengan panah)</span>
            </li>
            <li class="flex items-start gap-3">
                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-[#3B82F6]/20 text-[#3B82F6] flex items-center justify-center text-xs font-bold">2</span>
                <span>Scroll dan pilih <strong>"Add to Home Screen"</strong></span>
            </li>
            <li class="flex items-start gap-3">
                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-[#3B82F6]/20 text-[#3B82F6] flex items-center justify-center text-xs font-bold">3</span>
                <span>Tap <strong>"Add"</strong> di pojok kanan atas</span>
            </li>
        </ol>

        <button onclick="closeIosModal()" class="w-full py-3 rounded-xl bg-gradient-to-r from-[#3B82F6] to-[#8B5CF6] text-white font-medium hover:opacity-90 transition">
            Mengerti
        </button>
    </div>
</div>

<script>
    let deferredPrompt;
    const pwaPrompt = document.getElementById('pwa-install-prompt');
    const iosModal = document.getElementById('ios-install-modal');

    // Check if already dismissed or installed
    const pwaDismissed = localStorage.getItem('pwa-prompt-dismissed');
    const pwaDismissedTime = pwaDismissed ? parseInt(pwaDismissed) : 0;
    const dismissCooldown = 7 * 24 * 60 * 60 * 1000; // 7 days
    const pwaInstalled = localStorage.getItem('pwa-installed');

    // Detect iOS
    const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent);
    const isStandalone = window.navigator.standalone === true;

    // For Android/Desktop - Listen for beforeinstallprompt
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;

        // Show prompt if cooldown passed and not installed
        if ((!pwaDismissed || Date.now() - pwaDismissedTime > dismissCooldown) && !pwaInstalled) {
            setTimeout(() => {
                pwaPrompt.classList.remove('hidden');
            }, 5000); // Show after 5 seconds
        }
    });

    // For iOS - Show instructions after delay
    if (isIOS && !isStandalone && !pwaInstalled) {
        if (!pwaDismissed || Date.now() - pwaDismissedTime > dismissCooldown) {
            setTimeout(() => {
                pwaPrompt.classList.remove('hidden');
            }, 5000);
        }
    }

    window.addEventListener('appinstalled', () => {
        localStorage.setItem('pwa-installed', 'true');
        pwaPrompt.classList.add('hidden');
        deferredPrompt = null;

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: '🎉 SertiKu Terinstall!',
                text: 'Aplikasi berhasil ditambahkan ke home screen.',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }
    });

    function installPwa() {
        if (isIOS) {
            // Show iOS instructions
            iosModal.classList.remove('hidden');
            iosModal.classList.add('flex');
            pwaPrompt.classList.add('hidden');
            return;
        }

        if (!deferredPrompt) return;

        deferredPrompt.prompt();
        deferredPrompt.userChoice.then((choiceResult) => {
            if (choiceResult.outcome === 'accepted') {
                console.log('User accepted PWA install');
            }
            deferredPrompt = null;
            pwaPrompt.classList.add('hidden');
        });
    }

    function dismissPwaPrompt() {
        pwaPrompt.classList.add('hidden');
        localStorage.setItem('pwa-prompt-dismissed', Date.now().toString());
    }

    function closeIosModal() {
        iosModal.classList.add('hidden');
        iosModal.classList.remove('flex');
        localStorage.setItem('pwa-prompt-dismissed', Date.now().toString());
    }
</script>

<style>
    @keyframes bounce-in {
        0% {
            transform: scale(0.9) translateY(20px);
            opacity: 0;
        }
        50% {
            transform: scale(1.02);
        }
        100% {
            transform: scale(1) translateY(0);
            opacity: 1;
        }
    }

    .animate-bounce-in {
        animation: bounce-in 0.4s ease-out forwards;
    }
</style>
