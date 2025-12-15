{{-- resources/views/auth/register.blade.php --}}
<x-layouts.app title="SertiKu – Daftar Akun">

    <main class="min-h-screen bg-gradient-to-b from-[#0F172A] via-[#1E293B] to-[#0F172A] px-4 py-10 flex items-center justify-center">

        <div class="relative w-full max-w-6xl">
            {{-- Glow kiri-kanan --}}
            <div class="pointer-events-none absolute -left-32 top-24 h-96 w-96 rounded-full bg-gradient-to-r from-[#2B7FFF4D] to-[#00B8DB4D] blur-3xl opacity-80"></div>
            <div class="pointer-events-none absolute -right-32 bottom-0 h-96 w-96 rounded-full bg-gradient-to-r from-[#AD46FF33] to-[#F6339A33] blur-3xl opacity-80"></div>

            {{-- Kartu utama --}}
            <div class="relative mx-auto max-w-4xl text-white">
                {{-- Heading --}}
                <div class="text-center mb-8">
                    <h1 class="text-3xl md:text-4xl font-normal">Daftar Akun Baru</h1>
                    <p class="mt-2 text-[#BEDBFF] text-base md:text-lg">
                        Bergabunglah dengan ribuan pengguna & lembaga di SertiKu
                    </p>

                    {{-- Switch Pengguna / Lembaga (TAB) --}}
                    <div
                        class="mt-6 inline-flex items-center rounded-lg border border-white/10 bg-white/[0.04] px-1 py-1">
                        {{-- BTN Pengguna --}}
                        <button type="button" id="userTabBtn"
                                onclick="switchRegisterTab('pengguna')"
                                class="inline-flex items-center gap-2 rounded-md bg-gradient-to-b from-[#1E3A8F] to-[#3B82F6] px-4 py-1.5 text-xs md:text-sm shadow-sm text-white">
                            <span class="flex h-4 w-4 items-center justify-center">
                                <span class="h-[1px] w-3 border border-white/90 border-t-2 border-b-0"></span>
                            </span>
                            <span>Pengguna</span>
                        </button>
                        {{-- BTN Lembaga --}}
                        <button type="button" id="orgTabBtn"
                                onclick="switchRegisterTab('lembaga')"
                                class="inline-flex items-center gap-2 rounded-md px-4 py-1.5 text-xs md:text-sm text-white/70">
                            <span class="flex h-4 w-4 items-center justify-center">
                                <span class="h-[1px] w-3 border border-white/60 border-t border-b"></span>
                            </span>
                            <span>Lembaga</span>
                        </button>
                    </div>
                </div>

                {{-- STEP INDICATOR – PENGGUNA / LEMBAGA DIPISAH --}}
                <div class="mb-6">
                    {{-- Stepper Pengguna --}}
                    <div id="stepper-pengguna">
                        <div class="flex justify-between mb-3">
                            {{-- Info pribadi --}}
                            <div class="flex flex-col items-center w-1/4">
                                <div id="userStepIcon-1"
                                     class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-r from-[#00C950] to-[#00BC7D] text-white shadow">
                                    <span class="text-sm font-semibold">1</span>
                                </div>
                                <p class="mt-2 text-xs font-medium" id="userStepLabel-1">Info Pribadi</p>
                            </div>

                            {{-- Kontak --}}
                            <div class="flex flex-col items-center w-1/4">
                                <div id="userStepIcon-2"
                                     class="flex h-12 w-12 items-center justify-center rounded-full border border-white/30 bg-white/5 text-white/60">
                                    <span class="text-sm font-semibold">2</span>
                                </div>
                                <p class="mt-2 text-xs text-white/60" id="userStepLabel-2">Kontak</p>
                            </div>

                            {{-- Keamanan --}}
                            <div class="flex flex-col items-center w-1/4">
                                <div id="userStepIcon-3"
                                     class="flex h-12 w-12 items-center justify-center rounded-full border border-white/30 bg-white/5 text-white/60">
                                    <span class="text-sm font-semibold">3</span>
                                </div>
                                <p class="mt-2 text-xs text-white/60" id="userStepLabel-3">Keamanan</p>
                            </div>

                            {{-- Minat (placeholder) --}}
                            <div class="flex flex-col items-center w-1/4">
                                <div id="userStepIcon-4"
                                     class="flex h-12 w-12 items-center justify-center rounded-full border border-white/30 bg-white/5 text-white/60">
                                    <span class="text-sm font-semibold">4</span>
                                </div>
                                <p class="mt-2 text-xs text-white/60" id="userStepLabel-4">Minat</p>
                            </div>
                        </div>

                        {{-- Progress bar --}}
                        <div class="h-4 rounded-full bg-white/10 overflow-hidden">
                            <div id="userStepProgress"
                                 class="h-4 w-1/4 bg-gradient-to-r from-[#155DFC] to-[#0092B8] transition-all duration-300">
                            </div>
                        </div>
                    </div>

                    {{-- Stepper Lembaga (dinamis 4 step) --}}
                    <div id="stepper-lembaga" class="hidden">
                        <div class="flex justify-between mb-3">
                            {{-- Info Lembaga --}}
                            <div class="flex flex-col items-center w-1/4">
                                <div id="orgStepIcon-1"
                                     class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-r from-[#00C950] to-[#00BC7D] text-white shadow">
                                    <span class="text-sm font-semibold">1</span>
                                </div>
                                <p class="mt-2 text-xs font-medium" id="orgStepLabel-1">Info Lembaga</p>
                            </div>

                            {{-- Alamat --}}
                            <div class="flex flex-col items-center w-1/4">
                                <div id="orgStepIcon-2"
                                     class="flex h-12 w-12 items-center justify-center rounded-full border border-white/30 bg-white/5 text-white/60">
                                    <span class="text-sm font-semibold">2</span>
                                </div>
                                <p class="mt-2 text-xs text-white/60" id="orgStepLabel-2">Alamat</p>
                            </div>

                            {{-- Admin --}}
                            <div class="flex flex-col items-center w-1/4">
                                <div id="orgStepIcon-3"
                                     class="flex h-12 w-12 items-center justify-center rounded-full border border-white/30 bg-white/5 text-white/60">
                                    <span class="text-sm font-semibold">3</span>
                                </div>
                                <p class="mt-2 text-xs text-white/60" id="orgStepLabel-3">Admin</p>
                            </div>

                            {{-- Dokumen --}}
                            <div class="flex flex-col items-center w-1/4">
                                <div id="orgStepIcon-4"
                                     class="flex h-12 w-12 items-center justify-center rounded-full border border-white/30 bg-white/5 text-white/60">
                                    <span class="text-sm font-semibold">4</span>
                                </div>
                                <p class="mt-2 text-xs text-white/60" id="orgStepLabel-4">Dokumen</p>
                            </div>
                        </div>

                        {{-- Progress bar lembaga --}}
                        <div class="h-4 rounded-full bg-white/10 overflow-hidden">
                            <div id="orgStepProgress"
                                 class="h-4 w-1/4 bg-gradient-to-r from-[#155DFC] to-[#0092B8] transition-all duration-300">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- NOTIFIKASI GLOBAL --}}
                @if ($errors->any())
                    <div class="mb-4 rounded-xl border border-red-400/60 bg-red-500/10 px-4 py-3 text-sm text-red-200">
                        <p class="font-semibold mb-1">Oops, ada beberapa data yang belum benar.</p>
                        <ul class="list-disc pl-4 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('status'))
                    <div class="mb-4 rounded-xl border border-emerald-400/60 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                        {{ session('status') }}
                    </div>
                @endif

                {{-- CARD TAB PENGGUNA / LEMBAGA --}}
                <div class="rounded-3xl border border-white/20 bg-white/10 backdrop-blur-xl px-6 py-8 md:px-10 md:py-10">

                    {{-- ==================== TAB PENGGUNA ==================== --}}
                    <div id="tab-pengguna">
                        {{-- FORM WIZARD PENGGUNA --}}
                        <form action="{{ route('register.store') }}" method="POST" class="space-y-6">
                            @csrf
                            {{-- STEP HIDDEN --}}
                            <input type="hidden" name="step" id="userStepInput" value="{{ old('step', 1) }}">

                            {{-- =============== STEP 1: INFO PRIBADI =============== --}}
                            <section id="step-1" class="wizard-step space-y-6">
                                <div>
                                    <h2 class="text-2xl font-normal">Informasi Pribadi</h2>
                                    <p class="mt-1 text-sm text-[#BEDBFF]/80">Lengkapi data diri Anda</p>
                                </div>

                                {{-- Nama lengkap --}}
                                <div class="space-y-2">
                                    <label class="text-sm">Nama Lengkap <span class="text-red-400">*</span></label>
                                    <input
                                        type="text"
                                        name="full_name"
                                        value="{{ old('full_name') }}"
                                        data-step="1" data-required="true"
                                        class="w-full rounded-lg border bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40
                                               focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70
                                               @error('full_name') border-red-400/80 focus:ring-red-400/80 @else border-white/20 @enderror"
                                        placeholder="Masukkan nama lengkap">
                                    <p class="mt-1 text-xs text-red-300 hidden" data-error-for="full_name"></p>
                                    @error('full_name')
                                    <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Pekerjaan --}}
                                <div class="space-y-2">
                                    <label class="text-sm">Pekerjaan <span class="text-red-400">*</span></label>
                                    <input
                                        type="text"
                                        name="occupation"
                                        value="{{ old('occupation') }}"
                                        data-step="1" data-required="true"
                                        class="w-full rounded-lg border bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40
                                               focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70
                                               @error('occupation') border-red-400/80 focus:ring-red-400/80 @else border-white/20 @enderror"
                                        placeholder="Contoh: Mahasiswa, Karyawan, dll">
                                    <p class="mt-1 text-xs text-red-300 hidden" data-error-for="occupation"></p>
                                    @error('occupation')
                                    <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Institusi --}}
                                <div class="space-y-2">
                                    <label class="text-sm">Institusi <span class="text-xs text-white/50">(Opsional)</span></label>
                                    <input
                                        type="text"
                                        name="institution"
                                        value="{{ old('institution') }}"
                                        data-step="1"
                                        class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40
                                               focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70"
                                        placeholder="Nama universitas/perusahaan">
                                </div>

                                {{-- Tombol step 1 --}}
                                <div class="mt-4 flex items-center justify-end border-t border-white/10 pt-4">
                                    <button type="button"
                                            class="inline-flex items-center rounded-lg bg-gradient-to-b from-[#1E3A8F] to-[#3B82F6]
                                                   px-6 py-2.5 text-sm font-medium shadow-[0_10px_15px_-3px_rgba(43,127,255,0.5)]
                                                   hover:brightness-110 transition"
                                            onclick="goToStep(2)">
                                        Lanjut
                                        <span class="ml-2 inline-flex h-3 w-3 items-center justify-center">
                                            <span class="block h-[1px] w-2 bg-white rotate-45 origin-left"></span>
                                            <span class="block h-[1px] w-2 bg-white -rotate-45 origin-left -mt-[1px]"></span>
                                        </span>
                                    </button>
                                </div>
                            </section>

                            {{-- =============== STEP 2: KONTAK =============== --}}
                            <section id="step-2" class="wizard-step space-y-6 hidden">
                                <div>
                                    <h2 class="text-2xl font-normal">Informasi Kontak</h2>
                                    <p class="mt-1 text-sm text-[#BEDBFF]/80">Agar kami dapat menghubungi Anda</p>
                                </div>

                                {{-- Email --}}
                                <div class="space-y-2">
                                    <label class="text-sm">Email <span class="text-red-400">*</span></label>
                                    <input
                                        type="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        data-step="2" data-required="true"
                                        class="w-full rounded-lg border bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40
                                               focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70
                                               @error('email') border-red-400/80 focus:ring-red-400/80 @else border-white/20 @enderror"
                                        placeholder="nama@email.com">
                                    <p class="mt-1 text-xs text-red-300 hidden" data-error-for="email"></p>
                                    @error('email')
                                    <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Nomor telepon --}}
                                <div class="space-y-2">
                                    <label class="text-sm">Nomor Telepon <span class="text-red-400">*</span></label>
                                    <input
                                        type="text"
                                        name="phone"
                                        value="{{ old('phone') }}"
                                        data-step="2" data-required="true"
                                        class="w-full rounded-lg border bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40
                                               focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70
                                               @error('phone') border-red-400/80 focus:ring-red-400/80 @else border-white/20 @enderror"
                                        placeholder="08123456789">
                                    <p class="mt-1 text-xs text-red-300 hidden" data-error-for="phone"></p>
                                    @error('phone')
                                    <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Negara --}}
                                <div class="space-y-2">
                                    <label class="text-sm">Negara <span class="text-red-400">*</span></label>
                                    <select
                                        name="country"
                                        data-step="2" data-required="true"
                                        class="w-full rounded-lg border bg-white/5 px-4 py-3 text-sm text-white
                                               focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70
                                               @error('country') border-red-400/80 focus:ring-red-400/80 @else border-white/20 @enderror">
                                        <option value="" class="bg-[#1E293B]">Pilih negara</option>
                                        <option value="Indonesia" class="bg-[#1E293B]" {{ old('country') == 'Indonesia' ? 'selected' : '' }}>Indonesia</option>
                                        <option value="Malaysia" class="bg-[#1E293B]" {{ old('country') == 'Malaysia' ? 'selected' : '' }}>Malaysia</option>
                                        <option value="Singapore" class="bg-[#1E293B]" {{ old('country') == 'Singapore' ? 'selected' : '' }}>Singapore</option>
                                        <option value="Thailand" class="bg-[#1E293B]" {{ old('country') == 'Thailand' ? 'selected' : '' }}>Thailand</option>
                                        <option value="Vietnam" class="bg-[#1E293B]" {{ old('country') == 'Vietnam' ? 'selected' : '' }}>Vietnam</option>
                                        <option value="Philippines" class="bg-[#1E293B]" {{ old('country') == 'Philippines' ? 'selected' : '' }}>Philippines</option>
                                        <option value="Brunei" class="bg-[#1E293B]" {{ old('country') == 'Brunei' ? 'selected' : '' }}>Brunei</option>
                                        <option value="Other" class="bg-[#1E293B]" {{ old('country') == 'Other' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    <p class="mt-1 text-xs text-red-300 hidden" data-error-for="country"></p>
                                    @error('country')
                                    <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mt-4 flex items-center justify-between border-t border-white/10 pt-4">
                                    <button type="button"
                                            class="inline-flex items-center rounded-lg border border-white/30 bg-white/5 px-5 py-2 text-sm hover:bg-white/10"
                                            onclick="goToStep(1, false)">
                                        <span class="mr-2 inline-flex h-3 w-3 items-center justify-center">
                                            <span class="block h-[1px] w-2 bg-white -rotate-45 origin-right"></span>
                                            <span class="block h-[1px] w-2 bg-white rotate-45 origin-right -mt-[1px]"></span>
                                        </span>
                                        Kembali
                                    </button>

                                    <button type="button"
                                            class="inline-flex items-center rounded-lg bg-gradient-to-b from-[#1E3A8F] to-[#3B82F6]
                                                   px-6 py-2.5 text-sm font-medium shadow-[0_10px_15px_-3px_rgba(43,127,255,0.5)]
                                                   hover:brightness-110 transition"
                                            onclick="goToStep(3)">
                                        Lanjut
                                        <span class="ml-2 inline-flex h-3 w-3 items-center justify-center">
                                            <span class="block h-[1px] w-2 bg-white rotate-45 origin-left"></span>
                                            <span class="block h-[1px] w-2 bg-white -rotate-45 origin-left -mt-[1px]"></span>
                                        </span>
                                    </button>
                                </div>
                            </section>

                            {{-- =============== STEP 3: KEAMANAN =============== --}}
                            <section id="step-3" class="wizard-step space-y-6 hidden">
                                <div>
                                    <h2 class="text-2xl font-normal">Keamanan Akun</h2>
                                    <p class="mt-1 text-sm text-[#BEDBFF]/80">Buat password yang kuat</p>
                                </div>

                                {{-- Password --}}
                                <div class="space-y-2">
                                    <label class="text-sm">Password <span class="text-red-400">*</span></label>
                                    <input
                                        type="password"
                                        name="password"
                                        data-step="3" data-required="true"
                                        oninput="updateStrength(this.value)"
                                        class="w-full rounded-lg border bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40
                                               focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70
                                               @error('password') border-red-400/80 focus:ring-red-400/80 @else border-white/20 @enderror"
                                        placeholder="Minimal 8 karakter">
                                    <p class="mt-1 text-xs text-red-300 hidden" data-error-for="password"></p>
                                    @error('password')
                                    <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                                    @enderror

                                    {{-- Strength bar --}}
                                    <div class="mt-2">
                                        <div class="h-1.5 w-full rounded-full bg-white/10 overflow-hidden">
                                            <div id="passwordStrengthBar"
                                                 class="h-1.5 w-0 rounded-full bg-gradient-to-r from-red-500 via-yellow-400 to-emerald-400 transition-all duration-200"></div>
                                        </div>
                                        <p id="passwordStrengthLabel" class="mt-1 text-xs text-[#BEDBFF]/80">
                                            Password belum dinilai
                                        </p>
                                    </div>
                                </div>

                                {{-- Konfirmasi password --}}
                                <div class="space-y-2">
                                    <label class="text-sm">Konfirmasi Password <span class="text-red-400">*</span></label>
                                    <input
                                        type="password"
                                        name="password_confirmation"
                                        data-step="3" data-required="true"
                                        class="w-full rounded-lg border bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40
                                               focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70
                                               @error('password_confirmation') border-red-400/80 focus:ring-red-400/80 @else border-white/20 @enderror"
                                        placeholder="Masukkan password lagi">
                                    <p class="mt-1 text-xs text-red-300 hidden" data-error-for="password_confirmation"></p>
                                    @error('password_confirmation')
                                    <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div
                                    class="mt-2 flex items-start gap-3 rounded-xl border border-[#2B7FFF33] bg-[#2B7FFF1A] px-4 py-3 text-xs text-[#BEDBFF]">
                                    <span class="mt-0.5 inline-flex h-4 w-4 items-center justify-center rounded-full border border-[#1E293B]">
                                        !
                                    </span>
                                    <span>
                                        Password sebaiknya minimal 8 karakter dan kombinasi huruf besar, huruf kecil, angka, serta simbol.
                                    </span>
                                </div>

                                <div class="mt-4 flex items-center justify-between border-t border-white/10 pt-4">
                                    <button type="button"
                                            class="inline-flex items-center rounded-lg border border-white/30 bg-white/5 px-5 py-2 text-sm hover:bg-white/10"
                                            onclick="goToStep(2, false)">
                                        <span class="mr-2 inline-flex h-3 w-3 items-center justify-center">
                                            <span class="block h-[1px] w-2 bg-white -rotate-45 origin-right"></span>
                                            <span class="block h-[1px] w-2 bg-white rotate-45 origin-right -mt-[1px]"></span>
                                        </span>
                                        Kembali
                                    </button>

                                    <button type="submit"
                                            class="inline-flex items-center rounded-lg bg-gradient-to-b from-[#1E3A8F] to-[#3B82F6]
                                                   px-6 py-2.5 text-sm font-medium shadow-[0_10px_15px_-3px_rgba(43,127,255,0.5)]
                                                   hover:brightness-110 transition">
                                        Daftar
                                    </button>
                                </div>
                            </section>

                            {{-- STEP 4 (Minat) bisa ditambahkan nanti --}}

                        </form>

                        {{-- TOMBOL KEMBALI KE LOGIN (kiri bawah, DI LUAR FORM) --}}
                        <div class="mt-6 flex justify-start">
                            <a href="{{ route('login') }}"
                               class="inline-flex items-center rounded-lg border border-white/30 bg-white/5 px-4 py-2 text-xs md:text-sm
                                      text-white hover:bg-white/10 transition">
                                ← Kembali ke Login
                            </a>
                        </div>
                    </div>

                    {{-- ==================== TAB LEMBAGA (4 STEP) ==================== --}}
                    <div id="tab-lembaga" class="hidden">

                        <div class="rounded-3xl border border-white/10 bg-white/5 px-4 py-6 md:px-6 md:py-8">
                            <form id="orgWizard"
                                  action="{{ route('register.lembaga.store') }}"
                                  method="POST"
                                  enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" name="org_step" id="orgStepInput" value="{{ old('org_step', 1) }}">

                                {{-- ========== STEP 1: INFO LEMBAGA ========== --}}
                                <section id="org-step-1" class="org-step space-y-6">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <h2 class="text-2xl font-normal">Informasi Lembaga</h2>
                                            <p class="mt-1 text-sm text-[#BEDBFF]/80">Lengkapi data dasar lembaga Anda</p>
                                        </div>

                                        {{-- tombol kembali login (kiri atas card untuk lembaga) --}}
                                        <a href="{{ route('login') }}"
                                           class="hidden md:inline-flex items-center rounded-lg border border-white/30 bg-white/5 px-3 py-1.5 text-xs
                                                  text-white hover:bg-white/10 transition">
                                            ← Kembali ke Login
                                        </a>
                                    </div>

                                    {{-- Nama Lembaga --}}
                                    <div class="space-y-2">
                                        <label class="text-sm">Nama Lembaga <span class="text-red-400">*</span></label>
                                        <input type="text" name="institution_name" value="{{ old('institution_name') }}"
                                               data-org-step="1" data-required="true"
                                               class="w-full rounded-lg border bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40
                                                      focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70
                                                      @error('institution_name') border-red-400/80 focus:ring-red-400/80 @else border-white/20 @enderror"
                                               placeholder="Nama resmi lembaga">
                                        <p class="mt-1 text-xs text-red-300 hidden" data-org-error-for="institution_name"></p>
                                        @error('institution_name')
                                        <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Jenis Lembaga --}}
                                    <div class="space-y-2">
                                        <label class="text-sm">Jenis Lembaga <span class="text-red-400">*</span></label>
                                        <input type="text" name="institution_type" value="{{ old('institution_type') }}"
                                               data-org-step="1" data-required="true"
                                               class="w-full rounded-lg border bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40
                                                      focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70
                                                      @error('institution_type') border-red-400/80 focus:ring-red-400/80 @else border-white/20 @enderror"
                                               placeholder="Contoh: Universitas, Sekolah, Perusahaan, Komunitas">
                                        <p class="mt-1 text-xs text-red-300 hidden" data-org-error-for="institution_type"></p>
                                        @error('institution_type')
                                        <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Sektor --}}
                                    <div class="space-y-2">
                                        <label class="text-sm">Sektor / Bidang <span class="text-red-400">*</span></label>
                                        <input type="text" name="sector" value="{{ old('sector') }}"
                                               data-org-step="1" data-required="true"
                                               class="w-full rounded-lg border bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40
                                                      focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70
                                                      @error('sector') border-red-400/80 focus:ring-red-400/80 @else border-white/20 @enderror"
                                               placeholder="Contoh: Pendidikan, Teknologi, Pemerintahan">
                                        <p class="mt-1 text-xs text-red-300 hidden" data-org-error-for="sector"></p>
                                        @error('sector')
                                        <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Website --}}
                                    <div class="space-y-2">
                                        <label class="text-sm">Website Resmi <span class="text-xs text-white/50">(Opsional)</span></label>
                                        <input type="text" name="website" value="{{ old('website') }}"
                                               data-org-step="1"
                                               class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40
                                                      focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70"
                                               placeholder="https://contoh.ac.id">
                                        @error('website')
                                        <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Deskripsi --}}
                                    <div class="space-y-2">
                                        <label class="text-sm">Deskripsi Lembaga <span class="text-xs text-white/50">(Opsional)</span></label>
                                        <textarea name="description" rows="3"
                                                  data-org-step="1"
                                                  class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40
                                                         focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70"
                                                  placeholder="Ceritakan singkat tentang lembaga Anda">{{ old('description') }}</textarea>
                                        @error('description')
                                        <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mt-4 flex items-center justify-between border-t border-white/10 pt-4">
                                        <a href="{{ route('login') }}"
                                           class="inline-flex items-center rounded-lg border border-white/30 bg-white/5 px-4 py-2 text-xs md:text-sm
                                                  text-white hover:bg-white/10 transition md:hidden">
                                            ← Kembali ke Login
                                        </a>

                                        <button type="button"
                                                class="inline-flex items-center rounded-lg bg-gradient-to-b from-[#1E3A8F] to-[#3B82F6]
                                                       px-6 py-2.5 text-sm font-medium shadow-[0_10px_15px_-3px_rgba(43,127,255,0.5)]
                                                       hover:brightness-110 transition ml-auto"
                                                onclick="goToOrgStep(2)">
                                            Lanjut
                                            <span class="ml-2 inline-flex h-3 w-3 items-center justify-center">
                                                <span class="block h-[1px] w-2 bg-white rotate-45 origin-left"></span>
                                                <span class="block h-[1px] w-2 bg-white -rotate-45 origin-left -mt-[1px]"></span>
                                            </span>
                                        </button>
                                    </div>
                                </section>

                                {{-- ========== STEP 2: ALAMAT ========== --}}
                                <section id="org-step-2" class="org-step space-y-6 hidden">
                                    <div>
                                        <h2 class="text-2xl font-normal">Alamat Lembaga</h2>
                                        <p class="mt-1 text-sm text-[#BEDBFF]/80">Alamat resmi yang terdaftar</p>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-sm">Alamat Lengkap <span class="text-red-400">*</span></label>
                                        <input type="text" name="address_line" value="{{ old('address_line') }}"
                                               data-org-step="2" data-required="true"
                                               class="w-full rounded-lg border bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40
                                                      focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70
                                                      @error('address_line') border-red-400/80 focus:ring-red-400/80 @else border-white/20 @enderror"
                                               placeholder="Jalan, nomor, gedung">
                                        <p class="mt-1 text-xs text-red-300 hidden" data-org-error-for="address_line"></p>
                                        @error('address_line')
                                        <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="space-y-2">
                                            <label class="text-sm">Kota / Kabupaten <span class="text-red-400">*</span></label>
                                            <input type="text" name="city" value="{{ old('city') }}"
                                                   data-org-step="2" data-required="true"
                                                   class="w-full rounded-lg border bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40
                                                          focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70
                                                          @error('city') border-red-400/80 focus:ring-red-400/80 @else border-white/20 @enderror"
                                                   placeholder="Kota">
                                            <p class="mt-1 text-xs text-red-300 hidden" data-org-error-for="city"></p>
                                            @error('city')
                                            <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="space-y-2">
                                            <label class="text-sm">Provinsi <span class="text-red-400">*</span></label>
                                            <input type="text" name="province" value="{{ old('province') }}"
                                                   data-org-step="2" data-required="true"
                                                   class="w-full rounded-lg border bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40
                                                          focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70
                                                          @error('province') border-red-400/80 focus:ring-red-400/80 @else border-white/20 @enderror"
                                                   placeholder="Provinsi">
                                            <p class="mt-1 text-xs text-red-300 hidden" data-org-error-for="province"></p>
                                            @error('province')
                                            <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="space-y-2">
                                            <label class="text-sm">Kode Pos <span class="text-red-400">*</span></label>
                                            <input type="text" name="postal_code" value="{{ old('postal_code') }}"
                                                   data-org-step="2" data-required="true"
                                                   class="w-full rounded-lg border bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40
                                                          focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70
                                                          @error('postal_code') border-red-400/80 focus:ring-red-400/80 @else border-white/20 @enderror"
                                                   placeholder="Kode pos">
                                            <p class="mt-1 text-xs text-red-300 hidden" data-org-error-for="postal_code"></p>
                                            @error('postal_code')
                                            <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="space-y-2">
                                            <label class="text-sm">Negara <span class="text-red-400">*</span></label>
                                            <select name="country"
                                                   data-org-step="2" data-required="true"
                                                   class="w-full rounded-lg border bg-white/5 px-4 py-3 text-sm text-white
                                                          focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70
                                                          @error('country') border-red-400/80 focus:ring-red-400/80 @else border-white/20 @enderror">
                                                <option value="" class="bg-[#1E293B]">Pilih negara</option>
                                                <option value="Indonesia" class="bg-[#1E293B]" {{ old('country', 'Indonesia') == 'Indonesia' ? 'selected' : '' }}>Indonesia</option>
                                                <option value="Malaysia" class="bg-[#1E293B]" {{ old('country') == 'Malaysia' ? 'selected' : '' }}>Malaysia</option>
                                                <option value="Singapore" class="bg-[#1E293B]" {{ old('country') == 'Singapore' ? 'selected' : '' }}>Singapore</option>
                                                <option value="Thailand" class="bg-[#1E293B]" {{ old('country') == 'Thailand' ? 'selected' : '' }}>Thailand</option>
                                                <option value="Vietnam" class="bg-[#1E293B]" {{ old('country') == 'Vietnam' ? 'selected' : '' }}>Vietnam</option>
                                                <option value="Philippines" class="bg-[#1E293B]" {{ old('country') == 'Philippines' ? 'selected' : '' }}>Philippines</option>
                                                <option value="Brunei" class="bg-[#1E293B]" {{ old('country') == 'Brunei' ? 'selected' : '' }}>Brunei</option>
                                                <option value="Other" class="bg-[#1E293B]" {{ old('country') == 'Other' ? 'selected' : '' }}>Lainnya</option>
                                            </select>
                                            <p class="mt-1 text-xs text-red-300 hidden" data-org-error-for="country"></p>
                                            @error('country')
                                            <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mt-4 flex items-center justify-between border-t border-white/10 pt-4">
                                        <button type="button"
                                                class="inline-flex items-center rounded-lg border border-white/30 bg-white/5 px-5 py-2 text-sm hover:bg-white/10"
                                                onclick="goToOrgStep(1, false)">
                                            <span class="mr-2 inline-flex h-3 w-3 items-center justify-center">
                                                <span class="block h-[1px] w-2 bg-white -rotate-45 origin-right"></span>
                                                <span class="block h-[1px] w-2 bg-white rotate-45 origin-right -mt-[1px]"></span>
                                            </span>
                                            Kembali
                                        </button>

                                        <button type="button"
                                                class="inline-flex items-center rounded-lg bg-gradient-to-b from-[#1E3A8F] to-[#3B82F6]
                                                       px-6 py-2.5 text-sm font-medium shadow-[0_10px_15px_-3px_rgba(43,127,255,0.5)]
                                                       hover:brightness-110 transition"
                                                onclick="goToOrgStep(3)">
                                            Lanjut
                                            <span class="ml-2 inline-flex h-3 w-3 items-center justify-center">
                                                <span class="block h-[1px] w-2 bg-white rotate-45 origin-left"></span>
                                                <span class="block h-[1px] w-2 bg-white -rotate-45 origin-left -mt-[1px]"></span>
                                            </span>
                                        </button>
                                    </div>
                                </section>

                                {{-- ========== STEP 3: ADMIN / PIC ========== --}}
                                <section id="org-step-3" class="org-step space-y-6 hidden">
                                    <div>
                                        <h2 class="text-2xl font-normal">Admin / PIC Lembaga</h2>
                                        <p class="mt-1 text-sm text-[#BEDBFF]/80">Kontak utama yang akan mengelola SertiKu</p>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-sm">Nama Lengkap Admin <span class="text-red-400">*</span></label>
                                        <input type="text" name="admin_name" value="{{ old('admin_name') }}"
                                               data-org-step="3" data-required="true"
                                               class="w-full rounded-lg border bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40
                                                      focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70
                                                      @error('admin_name') border-red-400/80 focus:ring-red-400/80 @else border-white/20 @enderror">
                                        <p class="mt-1 text-xs text-red-300 hidden" data-org-error-for="admin_name"></p>
                                        @error('admin_name')
                                        <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-sm">Email Admin <span class="text-red-400">*</span></label>
                                        <input type="email" name="admin_email" value="{{ old('admin_email') }}"
                                               data-org-step="3" data-required="true"
                                               class="w-full rounded-lg border bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40
                                                      focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70
                                                      @error('admin_email') border-red-400/80 focus:ring-red-400/80 @else border-white/20 @enderror">
                                        <p class="mt-1 text-xs text-red-300 hidden" data-org-error-for="admin_email"></p>
                                        @error('admin_email')
                                        <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-sm">Nomor Telepon Admin <span class="text-red-400">*</span></label>
                                        <input type="text" name="admin_phone" value="{{ old('admin_phone') }}"
                                               data-org-step="3" data-required="true"
                                               class="w-full rounded-lg border bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40
                                                      focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70
                                                      @error('admin_phone') border-red-400/80 focus:ring-red-400/80 @else border-white/20 @enderror">
                                        <p class="mt-1 text-xs text-red-300 hidden" data-org-error-for="admin_phone"></p>
                                        @error('admin_phone')
                                        <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-sm">Jabatan di Lembaga <span class="text-red-400">*</span></label>
                                        <input type="text" name="admin_position" value="{{ old('admin_position') }}"
                                               data-org-step="3" data-required="true"
                                               class="w-full rounded-lg border bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40
                                                      focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70
                                                      @error('admin_position') border-red-400/80 focus:ring-red-400/80 @else border-white/20 @enderror">
                                        <p class="mt-1 text-xs text-red-300 hidden" data-org-error-for="admin_position"></p>
                                        @error('admin_position')
                                        <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-sm">Password <span class="text-red-400">*</span></label>
                                        <input type="password" name="admin_password"
                                               data-org-step="3" data-required="true"
                                               class="w-full rounded-lg border bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40
                                                      focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70
                                                      @error('admin_password') border-red-400/80 focus:ring-red-400/80 @else border-white/20 @enderror"
                                               placeholder="Minimal 8 karakter">
                                        <p class="mt-1 text-xs text-red-300 hidden" data-org-error-for="admin_password"></p>
                                        @error('admin_password')
                                        <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-sm">Konfirmasi Password <span class="text-red-400">*</span></label>
                                        <input type="password" name="admin_password_confirmation"
                                               data-org-step="3" data-required="true"
                                               class="w-full rounded-lg border bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40
                                                      focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70
                                                      border-white/20"
                                               placeholder="Ulangi password">
                                    </div>

                                    <div class="mt-4 flex items-center justify-between border-t border-white/10 pt-4">
                                        <button type="button"
                                                class="inline-flex items-center rounded-lg border border-white/30 bg-white/5 px-5 py-2 text-sm hover:bg-white/10"
                                                onclick="goToOrgStep(2, false)">
                                            <span class="mr-2 inline-flex h-3 w-3 items-center justify-center">
                                                <span class="block h-[1px] w-2 bg-white -rotate-45 origin-right"></span>
                                                <span class="block h-[1px] w-2 bg-white rotate-45 origin-right -mt-[1px]"></span>
                                            </span>
                                            Kembali
                                        </button>

                                        <button type="button"
                                                class="inline-flex items-center rounded-lg bg-gradient-to-b from-[#1E3A8F] to-[#3B82F6]
                                                       px-6 py-2.5 text-sm font-medium shadow-[0_10px_15px_-3px_rgba(43,127,255,0.5)]
                                                       hover:brightness-110 transition"
                                                onclick="goToOrgStep(4)">
                                            Lanjut
                                            <span class="ml-2 inline-flex h-3 w-3 items-center justify-center">
                                                <span class="block h-[1px] w-2 bg-white rotate-45 origin-left"></span>
                                                <span class="block h-[1px] w-2 bg-white -rotate-45 origin-left -mt-[1px]"></span>
                                            </span>
                                        </button>
                                    </div>
                                </section>

                                {{-- ========== STEP 4: DOKUMEN ========== --}}
                                <section id="org-step-4" class="org-step space-y-6 hidden">
                                    <div>
                                        <h2 class="text-2xl font-normal">Dokumen Pendukung</h2>
                                        <p class="mt-1 text-sm text-[#BEDBFF]/80">
                                            Unggah dokumen legal lembaga untuk mempercepat proses verifikasi.
                                        </p>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-sm">NPWP / NIB (PDF / JPG / PNG)</label>
                                        <input type="file" name="doc_npwp"
                                               class="block w-full text-xs text-[#BEDBFF] file:mr-3 file:rounded-md file:border-0
                                                      file:bg-white/10 file:px-3 file:py-2 file:text-xs file:font-medium
                                                      file:text-white hover:file:bg-white/20">
                                        @error('doc_npwp')
                                        <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-sm">Akta Pendirian / SK Lembaga</label>
                                        <input type="file" name="doc_akta"
                                               class="block w-full text-xs text-[#BEDBFF] file:mr-3 file:rounded-md file:border-0
                                                      file:bg-white/10 file:px-3 file:py-2 file:text-xs file:font-medium
                                                      file:text-white hover:file:bg-white/20">
                                        @error('doc_akta')
                                        <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-sm">SIUP / Izin Operasional (Opsional)</label>
                                        <input type="file" name="doc_siup"
                                               class="block w-full text-xs text-[#BEDBFF] file:mr-3 file:rounded-md file:border-0
                                                      file:bg-white/10 file:px-3 file:py-2 file:text-xs file:font-medium
                                                      file:text-white hover:file:bg-white/20">
                                        @error('doc_siup')
                                        <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mt-2 flex items-start gap-3 rounded-xl border border-[#2B7FFF33] bg-[#2B7FFF1A] px-4 py-3 text-xs text-[#BEDBFF]">
                                        <span class="mt-0.5 inline-flex h-4 w-4 items-center justify-center rounded-full border border-[#1E293B]">
                                            !
                                        </span>
                                        <span>
                                            Dokumen akan digunakan hanya untuk keperluan verifikasi lembaga dan tidak dibagikan ke pihak lain.
                                        </span>
                                    </div>

                                    <label class="mt-4 flex items-start gap-3 text-xs text-[#BEDBFF]">
                                        <input type="checkbox" name="agreement" value="1"
                                               class="mt-0.5 h-4 w-4 rounded border-white/30 bg-white/5 text-[#3B82F6] focus:ring-[#3B82F6]"
                                               {{ old('agreement') ? 'checked' : '' }}>
                                        <span>
                                            Saya menyatakan bahwa data dan dokumen yang dikirimkan adalah benar dan sah.
                                        </span>
                                    </label>
                                    @error('agreement')
                                    <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                                    @enderror

                                    <div class="mt-4 flex items-center justify-between border-t border-white/10 pt-4">
                                        <button type="button"
                                                class="inline-flex items-center rounded-lg border border-white/30 bg-white/5 px-5 py-2 text-sm hover:bg-white/10"
                                                onclick="goToOrgStep(3, false)">
                                            <span class="mr-2 inline-flex h-3 w-3 items-center justify-center">
                                                <span class="block h-[1px] w-2 bg-white -rotate-45 origin-right"></span>
                                                <span class="block h-[1px] w-2 bg-white rotate-45 origin-right -mt-[1px]"></span>
                                            </span>
                                            Kembali
                                        </button>

                                        <button type="submit"
                                                class="inline-flex items-center rounded-lg bg-gradient-to-b from-[#1E3A8F] to-[#3B82F6]
                                                       px-6 py-2.5 text-sm font-medium shadow-[0_10px_15px_-3px_rgba(43,127,255,0.5)]
                                                       hover:brightness-110 transition">
                                            Kirim Pendaftaran
                                        </button>
                                    </div>
                                </section>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </main>

    {{-- SCRIPT TAB + WIZARD & VALIDATION --}}
    <script>
        // ===== TAB PENGGUNA / LEMBAGA =====
        function switchRegisterTab(tab) {
            const userTab = document.getElementById('tab-pengguna');
            const orgTab = document.getElementById('tab-lembaga');
            const userBtn = document.getElementById('userTabBtn');
            const orgBtn = document.getElementById('orgTabBtn');
            const stepperUser = document.getElementById('stepper-pengguna');
            const stepperOrg = document.getElementById('stepper-lembaga');

            if (tab === 'pengguna') {
                userTab.classList.remove('hidden');
                orgTab.classList.add('hidden');
                stepperUser.classList.remove('hidden');
                stepperOrg.classList.add('hidden');

                userBtn.classList.add('bg-gradient-to-b', 'from-[#1E3A8F]', 'to-[#3B82F6]', 'text-white', 'shadow-sm');
                userBtn.classList.remove('text-white/70');

                orgBtn.classList.remove('bg-gradient-to-b', 'from-[#1E3A8F]', 'to-[#3B82F6]', 'text-white', 'shadow-sm');
                orgBtn.classList.add('text-white/70');
            } else {
                userTab.classList.add('hidden');
                orgTab.classList.remove('hidden');
                stepperUser.classList.add('hidden');
                stepperOrg.classList.remove('hidden');

                orgBtn.classList.add('bg-gradient-to-b', 'from-[#1E3A8F]', 'to-[#3B82F6]', 'text-white', 'shadow-sm');
                orgBtn.classList.remove('text-white/70');

                userBtn.classList.remove('bg-gradient-to-b', 'from-[#1E3A8F]', 'to-[#3B82F6]', 'text-white', 'shadow-sm');
                userBtn.classList.add('text-white/70');
            }
        }

        // ===== WIZARD PENGGUNA =====
        let currentStep = parseInt(document.getElementById('userStepInput').value || '1');

        function setStepUI(step) {
            currentStep = step;
            document.getElementById('userStepInput').value = step;

            // tampil/hidden section
            document.querySelectorAll('.wizard-step').forEach((el, idx) => {
                el.classList.toggle('hidden', idx + 1 !== step);
            });

            // progress bar (4 slot, step 4 nanti utk "Minat")
            const progress = document.getElementById('userStepProgress');
            progress.style.width = (step / 4 * 100) + '%';

            // icon & label
            for (let i = 1; i <= 4; i++) {
                const icon = document.getElementById('userStepIcon-' + i);
                const label = document.getElementById('userStepLabel-' + i);

                if (i < step) {
                    icon.className =
                        'flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-r from-[#00C950] to-[#00BC7D] text-white shadow';
                    label.className = 'mt-2 text-xs font-medium';
                } else if (i === step) {
                    icon.className =
                        'flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-b from-[#1E3A8F] to-[#3B82F6] text-white shadow';
                    label.className = 'mt-2 text-xs font-medium';
                } else {
                    icon.className =
                        'flex h-12 w-12 items-center justify-center rounded-full border border-white/30 bg-white/5 text-white/60';
                    label.className = 'mt-2 text-xs text-white/60';
                }
            }
        }

        function validateStep(step) {
            let valid = true;

            // bersihkan pesan error front-end
            document.querySelectorAll('[data-step="' + step + '"][data-required="true"]').forEach((input) => {
                const errEl = document.querySelector('[data-error-for="' + input.name + '"]');
                if (errEl) {
                    errEl.classList.add('hidden');
                    errEl.textContent = '';
                }
                input.classList.remove('border-red-400', 'focus:ring-red-400/80');
            });

            document.querySelectorAll('[data-step="' + step + '"][data-required="true"]').forEach((input) => {
                if (!input.value.trim()) {
                    valid = false;
                    const errEl = document.querySelector('[data-error-for="' + input.name + '"]');
                    if (errEl) {
                        errEl.textContent = 'Field ini wajib diisi.';
                        errEl.classList.remove('hidden');
                    }
                    input.classList.add('border-red-400', 'focus:ring-red-400/80');
                }
            });

            // validasi khusus password konfirmasi
            if (step === 3) {
                const pwd = document.querySelector('input[name="password"]');
                const conf = document.querySelector('input[name="password_confirmation"]');
                if (pwd && conf && pwd.value && conf.value && pwd.value !== conf.value) {
                    valid = false;
                    const errEl = document.querySelector('[data-error-for="password_confirmation"]');
                    if (errEl) {
                        errEl.textContent = 'Konfirmasi password tidak sama.';
                        errEl.classList.remove('hidden');
                    }
                    conf.classList.add('border-red-400', 'focus:ring-red-400/80');
                }
            }

            return valid;
        }

        function goToStep(step, needValidate = true) {
            if (needValidate && step > currentStep) {
                if (!validateStep(currentStep)) return;
            }
            setStepUI(step);
        }

        // Password strength sederhana
        function updateStrength(value) {
            const bar = document.getElementById('passwordStrengthBar');
            const label = document.getElementById('passwordStrengthLabel');
            if (!value) {
                bar.style.width = '0%';
                label.textContent = 'Password belum dinilai';
                return;
            }

            let score = 0;
            if (value.length >= 8) score++;
            if (/[A-Z]/.test(value)) score++;
            if (/[a-z]/.test(value)) score++;
            if (/[0-9]/.test(value)) score++;
            if (/[^A-Za-z0-9]/.test(value)) score++;

            const percent = (score / 5) * 100;
            bar.style.width = percent + '%';

            if (score <= 2) {
                label.textContent = 'Keamanan password: Lemah';
            } else if (score === 3 || score === 4) {
                label.textContent = 'Keamanan password: Cukup';
            } else {
                label.textContent = 'Keamanan password: Kuat';
            }
        }

        // ===== WIZARD LEMBAGA =====
        let orgCurrentStep = parseInt(document.getElementById('orgStepInput')?.value || '1');

        function setOrgStepUI(step) {
            orgCurrentStep = step;
            const input = document.getElementById('orgStepInput');
            if (input) input.value = step;

            // show/hide section
            document.querySelectorAll('.org-step').forEach((el, idx) => {
                el.classList.toggle('hidden', idx + 1 !== step);
            });

            // progress bar (4 step)
            const progress = document.getElementById('orgStepProgress');
            if (progress) progress.style.width = (step / 4 * 100) + '%';

            // icon & label
            for (let i = 1; i <= 4; i++) {
                const icon = document.getElementById('orgStepIcon-' + i);
                const label = document.getElementById('orgStepLabel-' + i);
                if (!icon || !label) continue;

                if (i < step) {
                    icon.className =
                        'flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-r from-[#00C950] to-[#00BC7D] text-white shadow';
                    label.className = 'mt-2 text-xs font-medium';
                } else if (i === step) {
                    icon.className =
                        'flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-b from-[#1E3A8F] to-[#3B82F6] text-white shadow';
                    label.className = 'mt-2 text-xs font-medium';
                } else {
                    icon.className =
                        'flex h-12 w-12 items-center justify-center rounded-full border border-white/30 bg-white/5 text-white/60';
                    label.className = 'mt-2 text-xs text-white/60';
                }
            }
        }

        function validateOrgStep(step) {
            let valid = true;

            document.querySelectorAll('[data-org-step="' + step + '"][data-required="true"]').forEach((input) => {
                const errEl = document.querySelector('[data-org-error-for="' + input.name + '"]');
                if (errEl) {
                    errEl.classList.add('hidden');
                    errEl.textContent = '';
                }
                input.classList.remove('border-red-400', 'focus:ring-red-400/80');
            });

            document.querySelectorAll('[data-org-step="' + step + '"][data-required="true"]').forEach((input) => {
                if (!input.value.trim()) {
                    valid = false;
                    const errEl = document.querySelector('[data-org-error-for="' + input.name + '"]');
                    if (errEl) {
                        errEl.textContent = 'Field ini wajib diisi.';
                        errEl.classList.remove('hidden');
                    }
                    input.classList.add('border-red-400', 'focus:ring-red-400/80');
                }
            });

            return valid;
        }

        function goToOrgStep(step, needValidate = true) {
            if (needValidate && step > orgCurrentStep) {
                if (!validateOrgStep(orgCurrentStep)) return;
                }
            setOrgStepUI(step);
        }

        // init
        document.addEventListener('DOMContentLoaded', () => {
            // default: tab Pengguna
            switchRegisterTab('pengguna');
            setStepUI(currentStep);

            if (document.getElementById('orgStepInput')) {
                setOrgStepUI(orgCurrentStep);
            }
        });
    </script>
</x-layouts.app>
