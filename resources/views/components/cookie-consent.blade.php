{{-- Cookie Consent Component - Simple Version --}}
<div x-data="cookieConsent()" x-show="showBanner" x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 translate-y-full" x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-full" x-cloak class="fixed bottom-0 left-0 right-0 z-50 p-4">
    <div class="max-w-4xl mx-auto">
        <div class="rounded-2xl p-5 shadow-2xl border border-blue-500/20 bg-[#0A1628]/95 backdrop-blur-xl">
            <div class="flex flex-col sm:flex-row items-center gap-4">

                {{-- Cookie Icon & Text --}}
                <div class="flex items-center gap-3 flex-1">
                    <span class="text-2xl">🍪</span>
                    <p class="text-[#94A3B8] text-sm">
                        Website ini menggunakan cookie untuk menjaga sesi dan keamanan Anda.
                    </p>
                </div>

                {{-- Accept Button --}}
                <button @click="acceptCookies()" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-blue-500 to-purple-600 text-white font-medium
                           hover:from-blue-600 hover:to-purple-700 
                           transition-all duration-300 text-sm whitespace-nowrap">
                    Saya Mengerti
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function cookieConsent() {
        return {
            showBanner: false,

            init() {
                if (!localStorage.getItem('cookie_consent')) {
                    setTimeout(() => { this.showBanner = true; }, 1000);
                }
            },

            acceptCookies() {
                localStorage.setItem('cookie_consent', JSON.stringify({
                    accepted: true,
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