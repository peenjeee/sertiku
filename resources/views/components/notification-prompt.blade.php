{{-- Notification Permission Prompt Component - Minimalist Top Right --}}
<div id="notification-prompt" class="fixed top-20 right-4 z-50 hidden">
    <div class="bg-[#0A1628]/95 backdrop-blur-xl rounded-xl border border-amber-500/30 shadow-xl p-3 max-w-xs">
        <div class="flex items-center gap-3">
            {{-- Bell Icon --}}
            <div
                class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-500 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </div>

            {{-- Content --}}
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">Aktifkan Notifikasi</p>
                <p class="text-xs text-[#8EC5FF]/70">Update sertifikat</p>
            </div>

            {{-- Buttons --}}
            <div class="flex items-center gap-2">
                <button onclick="requestNotificationPermission()"
                    class="px-3 py-1.5 text-xs font-semibold text-white bg-amber-500 rounded-lg hover:bg-amber-600 transition-colors">
                    Izinkan
                </button>
                <button onclick="dismissNotificationPrompt()"
                    class="text-[#8EC5FF]/50 hover:text-white transition-colors p-1">
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
    const notificationPrompt = document.getElementById('notification-prompt');

    // Check if notifications are supported and not already asked
    function checkNotificationSupport() {
        if (!('Notification' in window)) return false;
        if (!('serviceWorker' in navigator)) return false;
        if (Notification.permission !== 'default') return false;
        if (localStorage.getItem('notification-prompt-dismissed')) return false;
        return true;
    }

    // Show notification prompt after delay
    document.addEventListener('DOMContentLoaded', () => {
        if (checkNotificationSupport()) {
            setTimeout(() => {
                // Only show if PWA prompt is not visible
                const pwaPrompt = document.getElementById('pwa-install-prompt');
                if (pwaPrompt && !pwaPrompt.classList.contains('hidden')) {
                    // Wait for PWA prompt to be dismissed
                    return;
                }
                notificationPrompt.classList.remove('hidden');
                notificationPrompt.classList.add('animate-fade-in-up');
            }, 8000); // Show after 8 seconds
        }
    });

    async function requestNotificationPermission() {
        try {
            const permission = await Notification.requestPermission();

            if (permission === 'granted') {
                notificationPrompt.classList.add('hidden');

                // Register for push notifications
                if ('serviceWorker' in navigator) {
                    const registration = await navigator.serviceWorker.ready;

                    // Show success notification
                    new Notification('SertiKu', {
                        body: 'Notifikasi berhasil diaktifkan!',
                        icon: '/favicon.svg',
                        badge: '/favicon.svg'
                    });

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notifikasi Aktif!',
                            text: 'Anda akan menerima update penting dari SertiKu.',
                            showConfirmButton: false,
                            timer: 2500,
                            timerProgressBar: true
                        });
                    }
                }
            } else if (permission === 'denied') {
                notificationPrompt.classList.add('hidden');
                localStorage.setItem('notification-prompt-dismissed', 'denied');
            }
        } catch (error) {
            console.error('Error requesting notification permission:', error);
        }
    }

    function dismissNotificationPrompt() {
        notificationPrompt.classList.add('hidden');
        localStorage.setItem('notification-prompt-dismissed', Date.now().toString());
    }
</script>