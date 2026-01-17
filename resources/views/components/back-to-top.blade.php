@props(['hasChat' => false])

<div x-data="{ showBackToTop: false }"
    @scroll.window="showBackToTop = (window.pageYOffset > 300)"
    class="fixed {{ $hasChat ? 'bottom-24' : 'bottom-6' }} right-6 z-[90] pointer-events-none transition-all duration-300">
    <button x-show="showBackToTop" @click="window.scrollTo({top: 0, behavior: 'smooth'})"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-10 scale-90"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-10 scale-90"
        class="pointer-events-auto bg-blue-600 hover:bg-blue-500 text-white rounded-full p-3 shadow-lg hover:shadow-blue-500/50 transition-all duration-300 flex items-center justify-center group border border-white/10 glass-effect"
        aria-label="Back to Top">
        <svg xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6 transform group-hover:-translate-y-1 transition-transform duration-300" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </button>
</div>

<style>
    .glass-effect {
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }
</style>