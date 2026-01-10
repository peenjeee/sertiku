<x-layouts.app title="SertiKu – Reset Password Akun Anda dengan Aman"
    description="Reset password akun SertiKu Anda dengan mudah dan aman. Masukkan email untuk mendapatkan link pemulihan.">
    <main class="min-h-screen bg-[#0F172A] px-4 py-10 flex items-center justify-center">
        <div class="relative w-full max-w-md">


            <div class="relative rounded-2xl bg-white/5 border border-white/10 p-6 md:p-8">
                {{-- Header --}}
                <div class="text-center mb-8">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-2 mb-6">
                        <svg width="40" height="40" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M35.9912 4.79883H11.9971C8.02159 4.79883 4.79883 8.02159 4.79883 11.9971V35.9912C4.79883 39.9667 8.02159 43.1894 11.9971 43.1894H35.9912C39.9667 43.1894 43.1894 39.9667 43.1894 35.9912V11.9971C43.1894 8.02159 39.9667 4.79883 35.9912 4.79883Z"
                                stroke="white" stroke-width="2" />
                            <path
                                d="M34.0717 10.7974H13.9166C12.8565 10.7974 11.9971 11.6568 11.9971 12.7169V26.8734C11.9971 27.9336 12.8565 28.793 13.9166 28.793H34.0717C35.1318 28.793 35.9912 27.9336 35.9912 26.8734V12.7169C35.9912 11.6568 35.1318 10.7974 34.0717 10.7974Z"
                                stroke="white" stroke-width="1.5" />
                            <circle cx="18" cy="35" r="4" fill="white" />
                        </svg>
                        <span class="text-xl font-semibold text-white">SertiKu</span>
                    </a>
                    <h1 class="text-2xl font-bold text-white">Lupa Password?</h1>
                    <p class="text-[#BEDBFF]/70 text-sm mt-2">
                        Masukkan email Anda dan kami akan mengirimkan link untuk reset password
                    </p>
                </div>

                {{-- Success Message --}}
                @if(session('status'))
                    <div
                        class="mb-6 rounded-lg bg-emerald-500/20 border border-emerald-500/30 p-4 text-emerald-400 text-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ session('status') }}
                        </div>
                    </div>
                @endif

                {{-- Form --}}
                <form action="{{ route('password.email') }}" method="POST" class="space-y-5">
                    @csrf

                    <div class="space-y-2">
                        <label class="text-sm text-white">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full rounded-lg bg-white/5 border border-white/20 px-4 py-3 text-white text-sm placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] @error('email') border-red-400 @enderror"
                            placeholder="nama@email.com">
                        @error('email')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full rounded-lg bg-[#2563EB] px-6 py-3 text-sm font-medium text-white shadow-md shadow-blue-500/20 hover:bg-[#3B82F6] transition">
                        Kirim Link Reset Password
                    </button>
                </form>

                {{-- Back to Login --}}
                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center gap-2 text-[#BEDBFF]/70 hover:text-white text-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Login
                    </a>
                </div>
            </div>
        </div>
    </main>
</x-layouts.app>