{{-- Cookie Consent Component - Enhanced Version --}}
<div x-data="cookieConsent()" x-show="showBanner" x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 translate-y-full" x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-full" x-cloak class="fixed bottom-0 left-0 right-0 z-50 p-4">
    <div class="max-w-4xl mx-auto">
        <div class="rounded-2xl p-5 shadow-2xl border border-blue-500/20 bg-[#0A1628]/95">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">

                {{-- Cookie Icon & Text --}}
                <div class="flex items-start gap-3 flex-1">
                    <span class="text-3xl">🍪</span>
                    <div>
                        <p class="text-white font-medium text-sm mb-1">Kami Menggunakan Cookie</p>
                        <p class="text-[#94A3B8] text-sm">
                            Website ini menggunakan cookie untuk meningkatkan pengalaman, menyimpan preferensi, dan
                            menganalisis penggunaan.
                            <a href="{{ route('cookie') }}" class="text-[#3B82F6] hover:underline">Pelajari lebih
                                lanjut</a>
                        </p>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <button @click="declineCookies()"
                        class="flex-1 sm:flex-none px-4 py-2.5 rounded-xl border border-white/20 text-white/70 hover:text-white hover:border-white/40 transition-all duration-300 text-sm">
                        Tolak
                    </button>
                    <button @click="acceptCookies()"
                        class="flex-1 sm:flex-none px-6 py-2.5 rounded-xl bg-[#3B82F6] text-white font-medium hover:bg-[#2563EB] transition-all duration-300 text-sm whitespace-nowrap">
                        Terima Semua
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function cookieConsent() {
        return {
            showBanner: false,

            init() {
                // Check if consent was already given
                const consent = localStorage.getItem('cookie_consent');
                if (!consent) {
                    setTimeout(() => { this.showBanner = true; }, 1500);
                }
            },

            acceptCookies() {
                localStorage.setItem('cookie_consent', JSON.stringify({
                    accepted: true,
                    essential: true,
                    analytics: true,
                    marketing: true,
                    acceptedAt: new Date().toISOString()
                }));
                this.showBanner = false;

                // Show confirmation
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Cookie Diterima',
                        text: 'Preferensi cookie Anda telah disimpan.',
                        showConfirmButton: false,
                        timer: 1500,
                        toast: true,
                        position: 'top-end'
                    });
                }
            },

            declineCookies() {
                localStorage.setItem('cookie_consent', JSON.stringify({
                    accepted: false,
                    essential: true,
                    analytics: false,
                    marketing: false,
                    acceptedAt: new Date().toISOString()
                }));
                this.showBanner = false;
            }
        }
    }
</script>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>