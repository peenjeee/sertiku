<nav x-data="{ open: false }" class="fixed inset-x-0 top-0 z-40">
    {{-- Bar terang paling atas --}}
    <div class="border-b border-[#CBD5E1] bg-[#F9FAFB]">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3 lg:px-0">
            {{-- Logo kiri --}}
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M35.9912 4.79883H11.9971C8.02159 4.79883 4.79883 8.02159 4.79883 11.9971V35.9912C4.79883 39.9667 8.02159 43.1894 11.9971 43.1894H35.9912C39.9667 43.1894 43.1894 39.9667 43.1894 35.9912V11.9971C43.1894 8.02159 39.9667 4.79883 35.9912 4.79883Z" stroke="#1F3A5F" stroke-width="2.8793" />
                    <path d="M34.0717 10.7974H13.9166C12.8565 10.7974 11.9971 11.6568 11.9971 12.7169V26.8734C11.9971 27.9336 12.8565 28.793 13.9166 28.793H34.0717C35.1318 28.793 35.9912 27.9336 35.9912 26.8734V12.7169C35.9912 11.6568 35.1318 10.7974 34.0717 10.7974Z" stroke="#1F3A5F" stroke-width="1.91953" />
                    <path d="M15.5962 15.5962H25.1938" stroke="#1F3A5F" stroke-width="1.43965" stroke-linecap="round" />
                    <path d="M15.5962 19.1953H22.7944" stroke="#1F3A5F" stroke-width="1.43965" stroke-linecap="round" />
                    <path d="M17.9957 39.1105C20.381 39.1105 22.3146 37.1768 22.3146 34.7915C22.3146 32.4062 20.381 30.4726 17.9957 30.4726C15.6104 30.4726 13.6768 32.4062 13.6768 34.7915C13.6768 37.1768 15.6104 39.1105 17.9957 39.1105Z" fill="#1F3A5F" />
                    <path d="M17.9955 30.4726L16.3159 37.1909L17.9955 35.9912L19.6751 37.1909L17.9955 30.4726Z" fill="#1F3A5F" />
                    <path d="M28.0738 20.395H25.1938V23.275H28.0738V20.395Z" fill="#1F3A5F" />
                    <path d="M32.3927 20.395H29.5127V23.275H32.3927V20.395Z" fill="#1F3A5F" />
                    <path d="M28.0738 24.714H25.1938V27.594H28.0738V24.714Z" fill="#1F3A5F" />
                    <path d="M32.3927 24.714H29.5127V27.594H32.3927V24.714Z" fill="#1F3A5F" />
                    <path d="M27.5933 21.8347H26.6333V22.7947H27.5933V21.8347Z" fill="white" />
                    <path d="M31.9126 21.8347H30.9526V22.7947H31.9126V21.8347Z" fill="white" />
                    <path d="M27.5933 26.1536H26.6333V27.1136H27.5933V26.1536Z" fill="white" />
                    <path d="M31.9126 26.1536H30.9526V27.1136H31.9126V26.1536Z" fill="white" />
                </svg>
                <span class="text-lg font-semibold tracking-tight text-[#020617]">
                    SertiKu
                </span>
            </a>

            {{-- Menu desktop --}}
            <div class="hidden items-center gap-8 text-sm font-medium text-[#0F172A] lg:flex">
                <a href="#beranda" class="hover:text-[#2563EB] transition">Beranda</a>
                <a href="#fitur" class="hover:text-[#2563EB] transition">Fitur</a>
                <a href="#harga" class="hover:text-[#2563EB] transition">Harga</a>
                <a href="#faq" class="hover:text-[#2563EB] transition">FAQ</a>
            </div>

            {{-- Kanan desktop (dengan icon) --}}
            <div class="hidden items-center gap-3 lg:flex">
                {{-- Tombol Verifikasi --}}
                <a href="{{ route('verifikasi') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-[#0F172A] hover:bg-[#E5E7EB] transition">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.99951 4.66553V3.33252C1.99951 2.97898 2.13995 2.63993 2.38994 2.38994C2.63993 2.13995 2.97898 1.99951 3.33252 1.99951H4.66553" stroke="#364153" stroke-width="1.33301" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M11.3306 1.99951H12.6636C13.0171 1.99951 13.3562 2.13995 13.6062 2.38994C13.8561 2.63993 13.9966 2.97898 13.9966 3.33252V4.66553" stroke="#364153" stroke-width="1.33301" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M13.9966 11.3306V12.6636C13.9966 13.0171 13.8561 13.3562 13.6062 13.6062C13.3562 13.8561 13.0171 13.9966 12.6636 13.9966H11.3306" stroke="#364153" stroke-width="1.33301" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M4.66553 13.9966H3.33252C2.97898 13.9966 2.63993 13.8561 2.38994 13.6062C2.13995 13.3562 1.99951 13.0171 1.99951 12.6636V11.3306" stroke="#364153" stroke-width="1.33301" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>Verifikasi
                </a>

                {{-- Tombol Masuk --}}
                <a href="{{ route('login') }}"
                    class="inline-flex items-center gap-2 rounded-[10px] bg-gradient-to-b from-[#1E3A8F] to-[#3B82F6] px-5 py-2 text-sm font-semibold text-white shadow-[0_15px_30px_-15px_rgba(37,99,235,0.75)] hover:brightness-110 transition">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.99756 1.99951H12.6636C13.0171 1.99951 13.3562 2.13995 13.6062 2.38994C13.8561 2.63993 13.9966 2.97898 13.9966 3.33252V12.6636C13.9966 13.0171 13.8561 13.3562 13.6062 13.6062C13.3562 13.8561 13.0171 13.9966 12.6636 13.9966H9.99756" stroke="white" stroke-width="1.33301" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M6.66504 11.3306L9.99756 7.99805L6.66504 4.66553" stroke="white" stroke-width="1.33301" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M9.99756 7.99805H1.99951" stroke="white" stroke-width="1.33301" stroke-linecap="round" stroke-linejoin="round" />
                    </svg> Masuk
                </a>
            </div>

            {{-- Toggle mobile --}}
            <button @click="open = !open"
                class="inline-flex items-center justify-center rounded-md border border-[#CBD5E1] bg-white p-2 text-[#0F172A] lg:hidden"
                aria-label="Toggle navigation">
                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="open" xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile dropdown --}}
    <div x-show="open"
        x-transition.origin.top
        class="border-b border-[#CBD5E1] bg-[#F9FAFB] lg:hidden">
        <div class="mx-auto flex max-w-6xl flex-col gap-2 px-4 py-3 text-sm font-medium text-[#0F172A]">
            <a href="#beranda" @click="open = false" class="py-1">Beranda</a>
            <a href="#fitur" @click="open = false" class="py-1">Fitur</a>
            <a href="#harga" @click="open = false" class="py-1">Harga</a>
            <a href="#faq" @click="open = false" class="py-1">FAQ</a>

            <div class="mt-3 flex flex-col gap-2">
                {{-- Verifikasi mobile --}}
                <a href="{{ route('verifikasi') }}"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-[10px] border border-[#CBD5E1] bg-white px-4 py-2 text-center text-sm font-medium text-[#0F172A]">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.99951 4.66553V3.33252C1.99951 2.97898 2.13995 2.63993 2.38994 2.38994C2.63993 2.13995 2.97898 1.99951 3.33252 1.99951H4.66553" stroke="#364153" stroke-width="1.33301" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M11.3306 1.99951H12.6636C13.0171 1.99951 13.3562 2.13995 13.6062 2.38994C13.8561 2.63993 13.9966 2.97898 13.9966 3.33252V4.66553" stroke="#364153" stroke-width="1.33301" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M13.9966 11.3306V12.6636C13.9966 13.0171 13.8561 13.3562 13.6062 13.6062C13.3562 13.8561 13.0171 13.9966 12.6636 13.9966H11.3306" stroke="#364153" stroke-width="1.33301" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M4.66553 13.9966H3.33252C2.97898 13.9966 2.63993 13.8561 2.38994 13.6062C2.13995 13.3562 1.99951 13.0171 1.99951 12.6636V11.3306" stroke="#364153" stroke-width="1.33301" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>Verifikasi
                </a>

                {{-- Masuk mobile --}}
                <a href="{{ route('login') }}"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-[10px] bg-gradient-to-b from-[#1E3A8F] to-[#3B82F6] px-4 py-2 text-center text-sm font-semibold text-white shadow-[0_15px_30px_-15px_rgba(37,99,235,0.75)]">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.99756 1.99951H12.6636C13.0171 1.99951 13.3562 2.13995 13.6062 2.38994C13.8561 2.63993 13.9966 2.97898 13.9966 3.33252V12.6636C13.9966 13.0171 13.8561 13.3562 13.6062 13.6062C13.3562 13.8561 13.0171 13.9966 12.6636 13.9966H9.99756" stroke="white" stroke-width="1.33301" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M6.66504 11.3306L9.99756 7.99805L6.66504 4.66553" stroke="white" stroke-width="1.33301" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M9.99756 7.99805H1.99951" stroke="white" stroke-width="1.33301" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>Masuk
                </a>
            </div>
        </div>
    </div>
</nav>