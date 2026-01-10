{{-- resources/views/onboarding/index.blade.php --}}
<x-layouts.app title="SertiKu – Lengkapi Profil">

    <div class="min-h-[calc(100vh-80px)] bg-[#0F172A] px-4 py-10 flex items-center justify-center overflow-x-hidden">
        <div class="relative w-full max-w-4xl">


            <div class="relative mx-auto max-w-3xl text-white">
                {{-- Header --}}
                <div class="text-center mb-8">
                    <h1 class="text-3xl md:text-4xl font-normal">Selamat Datang!</h1>
                    <p class="mt-2 text-[#BEDBFF] text-base md:text-lg">
                        Lengkapi profil Anda untuk mulai menggunakan SertiKu
                    </p>

                    {{-- Account type switch --}}
                    <div
                        class="mt-6 inline-flex items-center rounded-lg border border-white/10 bg-white/[0.04] px-1 py-1">
                        <button type="button" id="personalTabBtn" onclick="switchAccountType('personal')"
                            class="inline-flex items-center gap-2 rounded-md bg-[#3B82F6] px-4 py-1.5 text-xs md:text-sm shadow-sm text-white transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Pengguna</span>
                        </button>
                        <button type="button" id="institutionTabBtn" onclick="switchAccountType('institution')"
                            class="inline-flex items-center gap-2 rounded-md px-4 py-1.5 text-xs md:text-sm text-white/70 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span>Lembaga</span>
                        </button>
                    </div>
                </div>

                {{-- Auth info badge --}}
                <div class="mb-6 flex justify-center">
                    @if($user->hasGoogleLogin())
                        <div
                            class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-sm text-[#BEDBFF]">
                            <svg class="w-4 h-4" viewBox="0 0 24 24">
                                <path fill="#4285F4"
                                    d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                <path fill="#34A853"
                                    d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                <path fill="#FBBC05"
                                    d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                                <path fill="#EA4335"
                                    d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                            </svg>
                            <span>Terhubung dengan Google: {{ $user->email }}</span>
                        </div>
                    @elseif($user->hasWalletLogin())
                        <div
                            class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-sm text-[#BEDBFF]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            <span>Wallet: {{ Str::limit($user->wallet_address, 12, '...') }}</span>
                        </div>
                    @endif
                </div>

                {{-- Error messages --}}
                @if ($errors->any())
                    <div class="mb-4 rounded-xl border border-red-400/60 bg-red-500/10 px-4 py-3 text-sm text-red-200">
                        <ul class="list-disc pl-4 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Main card --}}
                <div class="rounded-3xl border border-white/20 bg-white/10 px-4 py-6 md:px-10 md:py-10">
                    <form action="{{ route('onboarding.store') }}" method="POST" id="onboardingForm">
                        @csrf
                        <input type="hidden" name="account_type" id="accountTypeInput" value="personal">

                        {{-- ===== PERSONAL FORM ===== --}}
                        <div id="personalForm" class="space-y-6">
                            <div>
                                <h2 class="text-2xl font-normal">Informasi Pribadi</h2>
                                <p class="mt-1 text-sm text-[#BEDBFF]/80">Lengkapi data diri Anda</p>
                            </div>

                            {{-- Nama --}}
                            <div class="space-y-2">
                                <label class="text-sm">Nama Lengkap <span class="text-red-400">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                    class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70"
                                    placeholder="Masukkan nama lengkap">
                            </div>

                            {{-- Email (readonly if from Google) --}}
                            @if(!$user->hasGoogleLogin())
                                <div class="space-y-2">
                                    <label class="text-sm">Email <span class="text-red-400">*</span></label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                        class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70"
                                        placeholder="nama@email.com">
                                </div>
                            @endif

                            {{-- Telepon --}}
                            <div class="space-y-2">
                                <label class="text-sm">Nomor Telepon <span class="text-red-400">*</span></label>
                                <input type="text" name="phone" value="{{ old('phone') }}"
                                    class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70"
                                    placeholder="08123456789">
                            </div>

                            {{-- Pekerjaan --}}
                            <div class="space-y-2">
                                <label class="text-sm">Pekerjaan <span class="text-red-400">*</span></label>
                                <input type="text" name="occupation" value="{{ old('occupation') }}"
                                    class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70"
                                    placeholder="Contoh: Mahasiswa, Karyawan, dll">
                            </div>

                            {{-- Institusi --}}
                            <div class="space-y-2">
                                <label class="text-sm">Institusi <span
                                        class="text-xs text-white/50">(Opsional)</span></label>
                                <input type="text" name="user_institution" value="{{ old('user_institution') }}"
                                    class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70"
                                    placeholder="Nama universitas/perusahaan">
                            </div>

                            {{-- Optional Password --}}
                            @if(!$user->hasGoogleLogin() && !$user->hasWalletLogin())
                                <div class="space-y-4 pt-4 border-t border-white/10">
                                    <p class="text-sm text-[#BEDBFF]">Buat password untuk login dengan email</p>
                                    <div class="space-y-2">
                                        <label class="text-sm">Password</label>
                                        <input type="password" name="password"
                                            class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70"
                                            placeholder="Minimal 8 karakter">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm">Konfirmasi Password</label>
                                        <input type="password" name="password_confirmation"
                                            class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70"
                                            placeholder="Masukkan password lagi">
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- ===== INSTITUTION FORM ===== --}}
                        <div id="institutionForm" class="hidden space-y-6">
                            {{-- Step 1: Info Lembaga --}}
                            <div id="instStep1" class="space-y-6">
                                <div>
                                    <h2 class="text-2xl font-normal">Informasi Lembaga</h2>
                                    <p class="mt-1 text-sm text-[#BEDBFF]/80">Langkah 1 dari 3</p>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm">Nama Lembaga <span class="text-red-400">*</span></label>
                                    <input type="text" name="institution_name" value="{{ old('institution_name') }}"
                                        class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70"
                                        placeholder="Nama resmi lembaga">
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm">Jenis Lembaga <span class="text-red-400">*</span></label>
                                    <input type="text" name="institution_type" value="{{ old('institution_type') }}"
                                        class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70"
                                        placeholder="Contoh: Universitas, Sekolah, Perusahaan">
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm">Sektor / Bidang <span class="text-red-400">*</span></label>
                                    <input type="text" name="sector" value="{{ old('sector') }}"
                                        class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70"
                                        placeholder="Contoh: Pendidikan, Teknologi">
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm">Website <span
                                            class="text-xs text-white/50">(Opsional)</span></label>
                                    <input type="text" name="website" value="{{ old('website') }}"
                                        class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70"
                                        placeholder="https://contoh.ac.id">
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm">Deskripsi <span
                                            class="text-xs text-white/50">(Opsional)</span></label>
                                    <textarea name="description" rows="3"
                                        class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70"
                                        placeholder="Ceritakan singkat tentang lembaga Anda">{{ old('description') }}</textarea>
                                </div>

                                <div class="flex justify-end pt-4 border-t border-white/10">
                                    <button type="button" onclick="goToInstStep(2)"
                                        class="inline-flex items-center rounded-lg bg-[#3B82F6] px-6 py-2.5 text-sm font-medium shadow-[0_10px_15px_-3px_rgba(59,130,246,0.4)] hover:bg-[#2563EB] transition">
                                        Lanjut →
                                    </button>
                                </div>
                            </div>

                            {{-- Step 2: Alamat --}}
                            <div id="instStep2" class="hidden space-y-6">
                                <div>
                                    <h2 class="text-2xl font-normal">Alamat Lembaga</h2>
                                    <p class="mt-1 text-sm text-[#BEDBFF]/80">Langkah 2 dari 3</p>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm">Alamat Lengkap <span class="text-red-400">*</span></label>
                                    <input type="text" name="address_line" value="{{ old('address_line') }}"
                                        class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70"
                                        placeholder="Jalan, nomor, gedung">
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <label class="text-sm">Kota <span class="text-red-400">*</span></label>
                                        <input type="text" name="city" value="{{ old('city') }}"
                                            class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm">Provinsi <span class="text-red-400">*</span></label>
                                        <input type="text" name="province" value="{{ old('province') }}"
                                            class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <label class="text-sm">Kode Pos <span class="text-red-400">*</span></label>
                                        <input type="text" name="postal_code" value="{{ old('postal_code') }}"
                                            class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm">Negara <span class="text-red-400">*</span></label>
                                        <input type="text" name="country" value="{{ old('country', 'Indonesia') }}"
                                            class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70">
                                    </div>
                                </div>

                                <div class="flex justify-between pt-4 border-t border-white/10">
                                    <button type="button" onclick="goToInstStep(1)"
                                        class="inline-flex items-center rounded-lg border border-white/30 bg-white/5 px-5 py-2 text-sm hover:bg-white/10">
                                        ← Kembali
                                    </button>
                                    <button type="button" onclick="goToInstStep(3)"
                                        class="inline-flex items-center rounded-lg bg-[#3B82F6] px-6 py-2.5 text-sm font-medium shadow-[0_10px_15px_-3px_rgba(59,130,246,0.4)] hover:bg-[#2563EB] transition">
                                        Lanjut →
                                    </button>
                                </div>
                            </div>

                            {{-- Step 3: Admin --}}
                            <div id="instStep3" class="hidden space-y-6">
                                <div>
                                    <h2 class="text-2xl font-normal">Admin / PIC Lembaga</h2>
                                    <p class="mt-1 text-sm text-[#BEDBFF]/80">Langkah 3 dari 3</p>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm">Nama Lengkap Admin <span
                                            class="text-red-400">*</span></label>
                                    <input type="text" name="admin_name" value="{{ old('admin_name', $user->name) }}"
                                        class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70">
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm">Nomor Telepon Admin <span
                                            class="text-red-400">*</span></label>
                                    <input type="text" name="admin_phone" value="{{ old('admin_phone') }}"
                                        class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70"
                                        placeholder="08123456789">
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm">Jabatan di Lembaga <span
                                            class="text-red-400">*</span></label>
                                    <input type="text" name="admin_position" value="{{ old('admin_position') }}"
                                        class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70"
                                        placeholder="Contoh: Kepala Divisi, Direktur">
                                </div>

                                <div class="flex justify-between pt-4 border-t border-white/10">
                                    <button type="button" onclick="goToInstStep(2)"
                                        class="inline-flex items-center rounded-lg border border-white/30 bg-white/5 px-5 py-2 text-sm hover:bg-white/10">
                                        ← Kembali
                                    </button>
                                    <button type="submit"
                                        class="inline-flex items-center rounded-lg bg-[#3B82F6] px-6 py-2.5 text-sm font-medium shadow-[0_10px_15px_-3px_rgba(59,130,246,0.4)] hover:bg-[#2563EB] transition">
                                        Simpan & Lanjutkan
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Submit button for personal form --}}
                        <div id="personalSubmit"
                            class="mt-6 flex items-center justify-between border-t border-white/10 pt-6">
                            <a href="{{ route('onboarding.skip', ['type' => 'personal']) }}"
                                class="text-sm text-[#BEDBFF]/70 hover:text-white transition">
                                Lewati untuk sekarang →
                            </a>
                            <button type="submit"
                                class="inline-flex items-center rounded-lg bg-[#3B82F6] px-6 py-2.5 text-sm font-medium shadow-[0_10px_15px_-3px_rgba(43,127,255,0.5)] hover:bg-[#2563EB] transition">
                                Simpan & Lanjutkan
                            </button>
                        </div>
                    </form>

                    {{-- Skip link for institution --}}
                    <div id="institutionSkip" class="hidden mt-6 text-center border-t border-white/10 pt-6">
                        <a href="{{ route('onboarding.skip', ['type' => 'institution']) }}"
                            class="text-sm text-[#BEDBFF]/70 hover:text-white transition">
                            Lewati untuk sekarang →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchAccountType(type) {
            const personalBtn = document.getElementById('personalTabBtn');
            const institutionBtn = document.getElementById('institutionTabBtn');
            const personalForm = document.getElementById('personalForm');
            const institutionForm = document.getElementById('institutionForm');
            const personalSubmit = document.getElementById('personalSubmit');
            const institutionSkip = document.getElementById('institutionSkip');
            const accountTypeInput = document.getElementById('accountTypeInput');

            if (type === 'personal') {
                personalBtn.className = 'inline-flex items-center gap-2 rounded-md bg-[#3B82F6] px-4 py-1.5 text-xs md:text-sm shadow-sm text-white transition-all';
                institutionBtn.className = 'inline-flex items-center gap-2 rounded-md px-4 py-1.5 text-xs md:text-sm text-white/70 transition-all';
                personalForm.classList.remove('hidden');
                institutionForm.classList.add('hidden');
                personalSubmit.classList.remove('hidden');
                institutionSkip.classList.add('hidden');
                accountTypeInput.value = 'personal';
            } else {
                institutionBtn.className = 'inline-flex items-center gap-2 rounded-md bg-[#3B82F6] px-4 py-1.5 text-xs md:text-sm shadow-sm text-white transition-all';
                personalBtn.className = 'inline-flex items-center gap-2 rounded-md px-4 py-1.5 text-xs md:text-sm text-white/70 transition-all';
                institutionForm.classList.remove('hidden');
                personalForm.classList.add('hidden');
                personalSubmit.classList.add('hidden');
                institutionSkip.classList.remove('hidden');
                accountTypeInput.value = 'institution';
                // Reset to step 1
                goToInstStep(1);
            }
        }

        function goToInstStep(step) {
            document.getElementById('instStep1').classList.add('hidden');
            document.getElementById('instStep2').classList.add('hidden');
            document.getElementById('instStep3').classList.add('hidden');
            document.getElementById('instStep' + step).classList.remove('hidden');
        }
    </script>

</x-layouts.app>