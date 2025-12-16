{{-- PWA Install Prompt Component - Minimalist Top Right --}}
<div id="pwa-install-prompt" class="fixed top-20 right-4 z-50 hidden">
    <div class="bg-[#0A1628]/95 backdrop-blur-xl rounded-xl border border-[#3B82F6]/30 shadow-xl p-3 max-w-xs">
        <div class="flex items-center gap-3">
            {{-- App Icon --}}
            <div class="flex-shrink-0 w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                <img src="{{ asset('favicon.svg') }}" alt="SertiKu" class="w-7 h-7">
            </div>

            {{-- Content --}}
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">Install SertiKu</p>
                <p class="text-xs text-[#8EC5FF]/70">Akses lebih cepat</p>
            </div>

            {{-- Buttons --}}
            <div class="flex items-center gap-2">
                <button onclick="installPwa()"
                    class="px-3 py-1.5 text-xs font-semibold text-white bg-[#3B82F6] rounded-lg hover:bg-[#2563EB] transition-colors">
                    Install
                </button>
                <button onclick="dismissPwaPrompt()" class="text-[#8EC5FF]/50 hover:text-white transition-colors p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
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
                pwaPrompt.classList.add('animate-fade-in-up');
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
        pwaPrompt.classList.add('hidden');
        localStorage.setItem('pwa-prompt-dismissed', Date.now().toString());
    }
</script>