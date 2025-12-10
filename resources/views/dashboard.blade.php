<x-layouts.app title="Dashboard – SertiKu">
    <main class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <!-- Welcome Card -->
        <div class="rounded-3xl border border-white/10 bg-gradient-to-br from-[#0F172A] to-[#1E293B] p-8 shadow-2xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">
                    Selamat Datang, {{ auth()->user()->name }}! 👋
                </h1>
                <p class="text-[#BEDBFF]/70 text-lg">
                    Lengkapi profil Anda untuk mulai menggunakan SertiKu
                </p>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-500/10 border border-blue-500/30 rounded-2xl p-6 mb-8">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-blue-500/20 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-lg mb-2">Mengapa perlu melengkapi profil?</h3>
                        <ul class="text-[#BEDBFF]/80 space-y-2 text-sm">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Akses fitur sesuai jenis akun Anda
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Terbitkan atau terima sertifikat digital
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Kelola dan verifikasi sertifikat dengan mudah
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Account Type Selection -->
            <div class="mb-8">
                <h2 class="text-white font-bold text-xl mb-4 text-center">Pilih Jenis Akun Anda</h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <!-- Personal Account -->
                    <div class="bg-gradient-to-br from-emerald-500/10 to-green-600/5 border border-emerald-500/30 rounded-2xl p-6 hover:border-emerald-400/50 transition cursor-pointer group">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-green-600 rounded-full flex items-center justify-center group-hover:scale-110 transition">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-emerald-400 font-bold text-lg">Akun Personal</h3>
                                <p class="text-emerald-300/60 text-sm">Untuk individu</p>
                            </div>
                        </div>
                        <p class="text-[#BEDBFF]/70 text-sm mb-4">
                            Cocok untuk Anda yang ingin menerima, menyimpan, dan membagikan sertifikat digital dari berbagai lembaga.
                        </p>
                        <ul class="text-[#BEDBFF]/60 text-xs space-y-1">
                            <li>✓ Simpan sertifikat di portofolio</li>
                            <li>✓ Verifikasi sertifikat kapan saja</li>
                            <li>✓ Bagikan ke LinkedIn & sosial media</li>
                        </ul>
                    </div>

                    <!-- Institution Account -->
                    <div class="bg-gradient-to-br from-blue-500/10 to-indigo-600/5 border border-blue-500/30 rounded-2xl p-6 hover:border-blue-400/50 transition cursor-pointer group">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center group-hover:scale-110 transition">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-blue-400 font-bold text-lg">Akun Lembaga</h3>
                                <p class="text-blue-300/60 text-sm">Untuk organisasi</p>
                            </div>
                        </div>
                        <p class="text-[#BEDBFF]/70 text-sm mb-4">
                            Cocok untuk universitas, perusahaan, atau lembaga yang ingin menerbitkan sertifikat digital.
                        </p>
                        <ul class="text-[#BEDBFF]/60 text-xs space-y-1">
                            <li>✓ Terbitkan sertifikat tanpa batas</li>
                            <li>✓ Kelola template sertifikat</li>
                            <li>✓ Pantau statistik verifikasi</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Current Account Info -->
            <div class="bg-white/5 border border-white/10 rounded-xl p-4 mb-6">
                <h3 class="text-white/60 text-sm font-medium mb-2">Informasi Akun Saat Ini:</h3>
                <div class="flex items-center gap-3">
                    @if(auth()->user()->avatar)
                        <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="w-10 h-10 rounded-full">
                    @else
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <p class="text-white font-medium">{{ auth()->user()->name }}</p>
                        <p class="text-white/50 text-sm">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>

            <!-- CTA Button -->
            <a href="{{ route('onboarding') }}"
               class="block w-full py-4 px-6 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold text-center rounded-xl shadow-lg shadow-blue-500/25 transition">
                <span class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Lengkapi Profil Sekarang
                </span>
            </a>

            <p class="text-center text-white/40 text-sm mt-4">
                Proses ini hanya membutuhkan 2-3 menit
            </p>
        </div>
    </main>
</x-layouts.app>
