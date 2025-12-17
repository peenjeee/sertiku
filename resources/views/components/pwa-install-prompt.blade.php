{{-- PWA Install Prompt Component - Bottom Banner Style like Cookie --}}
<div id="pwa-install-prompt" class="fixed bottom-0 left-0 right-0 z-50 hidden">
    <div class="bg-[#0A1628]/95 backdrop-blur-xl border-t border-[#3B82F6]/30 shadow-xl px-4 py-3">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
            {{-- Left: Icon + Text --}}
            <div class="flex items-center gap-3">
                {{-- App Icon --}}
                <div
                    class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-[#3B82F6] to-[#8B5CF6] rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>

                {{-- Content --}}
                <div>
                    <p class="text-sm font-medium text-white">Install SertiKu di perangkat Anda</p>
                    <p class="text-xs text-[#8EC5FF]/70">Akses lebih cepat tanpa perlu browser</p>
                </div>
            </div>

            {{-- Right: Buttons --}}
            <div class="flex items-center gap-3">
                <button onclick="dismissPwaPrompt()"
                    class="px-4 py-2 text-sm font-medium text-[#8EC5FF]/70 hover:text-white transition-colors">
                    Nanti Saja
                </button>
                <button onclick="installPwa()"
                    class="px-5 py-2 text-sm font-semibold text-white bg-gradient-to-r from-[#3B82F6] to-[#8B5CF6] rounded-lg hover:opacity-90 transition-all shadow-lg">
                    Install App
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let deferredPrompt;
    const pwaPrompt = document.getElementById('pwa-install-prompt');

    // Check if already dismissed
    const pwaDismissed = localStorage.getItem('pwa-prompt-dismissed');
    const pwaInstalled = localStorage.getItem('pwa-installed');

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;

        // Show prompt if not dismissed and not installed
        if (!pwaDismissed && !pwaInstalled) {
            setTimeout(() => {
                pwaPrompt.classList.remove('hidden');
                pwaPrompt.style.animation = 'slideUp 0.3s ease-out forwards';
            }, 3000); // Show after 3 seconds
        }
    });

    window.addEventListener('appinstalled', () => {
        localStorage.setItem('pwa-installed', 'true');
        pwaPrompt.classList.add('hidden');
        deferredPrompt = null;

        // Show success message
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'SertiKu Terinstall!',
                text: 'Aplikasi berhasil ditambahkan ke home screen.',
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true
            });
        }
    });

    function installPwa() {
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
        pwaPrompt.style.animation = 'slideDown 0.3s ease-out forwards';
        setTimeout(() => {
            pwaPrompt.classList.add('hidden');
        }, 300);
        localStorage.setItem('pwa-prompt-dismissed', Date.now().toString());
    }
</script>

<style>
    @keyframes slideUp {
        from {
            transform: translateY(100%);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @keyframes slideDown {
        from {
            transform: translateY(0);
            opacity: 1;
        }

        to {
            transform: translateY(100%);
            opacity: 0;
        }
    }
</style>