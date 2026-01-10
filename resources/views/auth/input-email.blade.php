{{-- resources/views/auth/input-email.blade.php --}}
<x-layouts.app title="SertiKu – Input Email Verifikasi"
    description="Masukkan alamat email aktif untuk menerima kode verifikasi OTP. Langkah penting menjaga keamanan akun SertiKu Anda.">

    <div class="min-h-screen flex items-center justify-center px-4 py-16">
        <div class="w-full max-w-md">

            {{-- Card --}}
            <div class="bg-white/10 border border-white/20 rounded-3xl p-8 shadow-2xl">

                {{-- Header --}}
                <div class="text-center mb-8">
                    <div
                        class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-[#10B981] flex items-center justify-center">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-white mb-2">Verifikasi Email</h1>
                    <p class="text-[#94A3B8] text-sm">
                        Untuk keamanan akun, silakan masukkan alamat email Anda untuk menerima kode verifikasi.
                    </p>
                </div>

                {{-- Wallet Info --}}
                <div class="mb-6 p-4 rounded-xl bg-blue-500/10 border border-blue-500/20">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-white text-sm font-medium">Login via Wallet</p>
                            <p class="text-blue-400 text-xs font-mono">
                                {{ substr(auth()->user()->wallet_address ?? '', 0, 6) }}...{{ substr(auth()->user()->wallet_address ?? '', -4) }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Error Messages --}}
                @if($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-red-500/20 border border-red-500/30 text-red-400 text-sm">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                {{-- Email Form --}}
                <form action="{{ route('verification.email.store') }}" method="POST">
                    @csrf

                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-[#BEDBFF] mb-2">
                            Alamat Email
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 rounded-xl border border-white/20 bg-white/5
                                      text-white placeholder:text-[#9CA3AF]
                                      focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500
                                      transition-all duration-200" placeholder="contoh@email.com">
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="w-full py-3 rounded-xl bg-[#10B981]
                                   text-white font-semibold hover:bg-[#059669]
                                   transition-all duration-300 shadow-md shadow-emerald-500/20">
                        Kirim Kode Verifikasi
                    </button>
                </form>

                {{-- Info --}}
                <div class="mt-6 p-4 rounded-xl bg-yellow-500/10 border border-yellow-500/20">
                    <p class="text-yellow-400 text-xs flex items-start gap-2">
                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Email ini akan digunakan untuk notifikasi penting dan pemulihan akun.</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>
