{{-- resources/views/onboarding/index.blade.php --}}
<x-layouts.app title="SertiKu – Lengkapi Profil">

    <main class="min-h-screen bg-[#0F172A] px-4 py-10 flex items-center justify-center overflow-x-hidden">

        <div class="relative w-full max-w-6xl">

            {{-- Kartu utama --}}
            <div class="relative mx-auto max-w-4xl text-white">
                {{-- Heading --}}
                <div class="text-center mb-8">
                    <h1 class="text-3xl md:text-4xl font-normal">Lengkapi Profil</h1>
                    <p class="mt-2 text-[#BEDBFF] text-base md:text-lg">
                        Lengkapi data diri Anda untuk melanjutkan
                    </p>

                    {{-- Switch Pengguna / Lembaga (TAB) --}}
                    <div
                        class="mt-6 inline-flex items-center rounded-lg border border-white/10 bg-white/[0.04] px-1 py-1">
                        {{-- BTN Pengguna --}}
                        <button type="button" id="userTabBtn" onclick="switchRegisterTab('pengguna')"
                            class="inline-flex items-center gap-2 rounded-md bg-[#3B82F6] px-4 py-1.5 text-xs md:text-sm shadow-sm text-white">
                            <span class="flex h-4 w-4 items-center justify-center">
                                <span class="h-[1px] w-3 border border-white/90 border-t-2 border-b-0"></span>
                            </span>
                            <span>Pengguna</span>
                        </button>
                        {{-- BTN Lembaga --}}
                        <button type="button" id="orgTabBtn" onclick="switchRegisterTab('lembaga')"
                            class="inline-flex items-center gap-2 rounded-md px-4 py-1.5 text-xs md:text-sm text-white/70">
                            <span class="flex h-4 w-4 items-center justify-center">
                                <span class="h-[1px] w-3 border border-white/60 border-t border-b"></span>
                            </span>
                            <span>Lembaga</span>
                        </button>
                    </div>
                </div>

                {{-- STEP INDICATOR – PENGGUNA / LEMBAGA DIPISAH --}}
                <div class="mb-6 px-2 sm:px-0">
                    {{-- Stepper Pengguna --}}
                    <div id="stepper-pengguna">
                        <div class="flex justify-between mb-3">
                            {{-- Info pribadi --}}
                            <div class="flex flex-col items-center w-1/4">
                                <div id="userStepIcon-1"
                                    class="flex h-8 w-8 sm:h-12 sm:w-12 items-center justify-center rounded-full bg-[#10B981] text-white shadow">
                                    <span class="text-xs sm:text-sm font-semibold">1</span>
                                </div>
                                <p class="mt-1 sm:mt-2 text-[10px] sm:text-xs font-medium text-center"
                                    id="userStepLabel-1">Info Pribadi</p>
                            </div>

                            {{-- Kontak --}}
                            <div class="flex flex-col items-center w-1/2">
                                <div id="userStepIcon-2"
                                    class="flex h-8 w-8 sm:h-12 sm:w-12 items-center justify-center rounded-full border border-white/30 bg-white/5 text-white/60">
                                    <span class="text-xs sm:text-sm font-semibold">2</span>
                                </div>
                                <p class="mt-1 sm:mt-2 text-[10px] sm:text-xs text-white/60 text-center"
                                    id="userStepLabel-2">Kontak</p>
                            </div>
                        </div>

                        {{-- Progress bar --}}
                        <div class="h-2 sm:h-4 rounded-full bg-white/10 overflow-hidden">
                            <div id="userStepProgress"
                                class="h-2 sm:h-4 w-1/4 bg-[#3B82F6] transition-all duration-300">
                            </div>
                        </div>
                    </div>

                    {{-- Stepper Lembaga (dinamis 4 step) --}}
                    <div id="stepper-lembaga" class="hidden">
                        <div class="flex justify-between mb-3">
                            {{-- Info Lembaga --}}
                            <div class="flex flex-col items-center w-1/4">
                                <div id="orgStepIcon-1"
                                    class="flex h-8 w-8 sm:h-12 sm:w-12 items-center justify-center rounded-full bg-[#10B981] text-white shadow">
                                    <span class="text-xs sm:text-sm font-semibold">1</span>
                                </div>
                                <p class="mt-1 sm:mt-2 text-[10px] sm:text-xs font-medium text-center"
                                    id="orgStepLabel-1">Info Lembaga</p>
                            </div>

                            {{-- Alamat --}}
                            <div class="flex flex-col items-center w-1/4">
                                <div id="orgStepIcon-2"
                                    class="flex h-8 w-8 sm:h-12 sm:w-12 items-center justify-center rounded-full border border-white/30 bg-white/5 text-white/60">
                                    <span class="text-xs sm:text-sm font-semibold">2</span>
                                </div>
                                <p class="mt-1 sm:mt-2 text-[10px] sm:text-xs text-white/60 text-center"
                                    id="orgStepLabel-2">Alamat</p>
                            </div>

                            {{-- Admin --}}
                            <div class="flex flex-col items-center w-1/4">
                                <div id="orgStepIcon-3"
                                    class="flex h-8 w-8 sm:h-12 sm:w-12 items-center justify-center rounded-full border border-white/30 bg-white/5 text-white/60">
                                    <span class="text-xs sm:text-sm font-semibold">3</span>
                                </div>
                                <p class="mt-1 sm:mt-2 text-[10px] sm:text-xs text-white/60 text-center"
                                    id="orgStepLabel-3">Lembaga</p>
                            </div>

                            {{-- Dokumen --}}
                            <div class="flex flex-col items-center w-1/4">
                                <div id="orgStepIcon-4"
                                    class="flex h-8 w-8 sm:h-12 sm:w-12 items-center justify-center rounded-full border border-white/30 bg-white/5 text-white/60">
                                    <span class="text-xs sm:text-sm font-semibold">4</span>
                                </div>
                                <p class="mt-1 sm:mt-2 text-[10px] sm:text-xs text-white/60 text-center"
                                    id="orgStepLabel-4">Dokumen</p>
                            </div>
                        </div>

                        {{-- Progress bar lembaga --}}
                        <div class="h-2 sm:h-4 rounded-full bg-white/10 overflow-hidden">
                            <div id="orgStepProgress" class="h-2 sm:h-4 w-1/4 bg-[#3B82F6] transition-all duration-300">
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
                    <div
                        class="mb-4 rounded-xl border border-emerald-400/60 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                        {{ session('status') }}
                    </div>
                @endif

                {{-- CARD TAB PENGGUNA / LEMBAGA --}}
                <div class="rounded-3xl border border-white/20 bg-white/10 px-6 py-8 md:px-10 md:py-10">

                    {{-- ==================== TAB PENGGUNA ==================== --}}
                    <div id="tab-pengguna">
                        {{-- FORM WIZARD PENGGUNA --}}
                        <form action="{{ route('onboarding.store') }}" method="POST" class="space-y-6">
                            @csrf
                            {{-- STEP HIDDEN --}}
                            <input type="hidden" name="step" id="userStepInput" value="{{ old('step', 1) }}">
                            <input type="hidden" name="account_type" value="personal">

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
                                        value="{{ old('full_name', $user->name) }}"
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
                                        value="{{ old('occupation', $user->occupation) }}"
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
                                        value="{{ old('institution', $user->user_institution) }}"
                                        data-step="1"
                                        class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40
                                               focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70"
                                        placeholder="Nama universitas/perusahaan">
                                </div>

                                {{-- Tombol step 1 --}}
                                <div class="mt-4 flex items-center justify-end border-t border-white/10 pt-4">
                                    <button type="button"
                                            class="inline-flex items-center rounded-lg bg-[#2563EB]
                                                   px-6 py-2.5 text-sm font-medium shadow-md shadow-blue-500/20
                                                   hover:bg-[#3B82F6] transition"
                                            onclick="goToStep(2)">
                                        Lanjut
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
                                        value="{{ old('email', $user->email) }}"
                                        readonly
                                        data-step="2" data-required="true"
                                        class="w-full rounded-lg border border-white/10 bg-white/10 px-4 py-3 text-sm text-white/70 cursor-not-allowed
                                               focus:outline-none"
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
                                        value="{{ old('phone', $user->phone) }}"
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
                                        id="user_select_country"
                                        data-old="{{ old('country', $user->country ?? 'Indonesia') }}"
                                        data-step="2" data-required="true"
                                        class="w-full rounded-lg border bg-white/5 px-4 py-3 text-sm text-white
                                               focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70
                                               @error('country') border-red-400/80 focus:ring-red-400/80 @else border-white/20 @enderror">
                                        <option value="" class="bg-[#1E293B]">Pilih negara</option>
                                        <option value="Indonesia" class="bg-[#1E293B]">Indonesia</option>
                                        <option value="" class="bg-[#1E293B]" disabled>Loading other countries...</option>
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
                                        Kembali
                                    </button>

                                    <button type="submit"
                                            class="inline-flex items-center rounded-lg bg-[#2563EB]
                                                   px-6 py-2.5 text-sm font-medium shadow-md shadow-blue-500/20
                                                   hover:bg-[#3B82F6] transition">
                                        Simpan
                                    </button>
                                </div>
                            </section>
                        </form>
                    </div>

                    {{-- ==================== TAB LEMBAGA (4 STEP) ==================== --}}
                    <div id="tab-lembaga" class="hidden">
                        {{-- FORM WIZARD LEMBAGA --}}
                        <form id="orgWizard"
                              action="{{ route('onboarding.store') }}"
                              method="POST"
                              enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="org_step" id="orgStepInput" value="{{ old('org_step', 1) }}">
                            <input type="hidden" name="account_type" value="institution">

                            {{-- ========== STEP 1: INFO LEMBAGA ========== --}}
                            <section id="org-step-1" class="org-step space-y-6">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <h2 class="text-2xl font-normal">Informasi Lembaga</h2>
                                        <p class="mt-1 text-sm text-[#BEDBFF]/80">Lengkapi data dasar lembaga Anda</p>
                                    </div>
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

                                <div class="mt-4 flex items-center justify-end border-t border-white/10 pt-4">
                                    <button type="button"
                                            class="inline-flex items-center rounded-lg bg-[#2563EB]
                                                   px-6 py-2.5 text-sm font-medium shadow-md shadow-blue-500/20
                                                   hover:bg-[#3B82F6] transition ml-auto"
                                            onclick="goToOrgStep(2)">
                                        Lanjut
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
                                        
                                        <input type="text" name="city" id="org_input_city" value="{{ old('city') }}"
                                               data-org-step="2" data-required="true"
                                               class="w-full rounded-lg border bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40
                                                      focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70
                                                      @error('city') border-red-400/80 focus:ring-red-400/80 @else border-white/20 @enderror"
                                               placeholder="Kota">

                                        <select name="city" id="org_select_city" disabled
                                               data-org-step="2" data-required="true"
                                               class="hidden w-full rounded-lg border bg-white/5 px-4 py-3 text-sm text-white
                                                      focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70
                                                      @error('city') border-red-400/80 focus:ring-red-400/80 @else border-white/20 @enderror">
                                            <option value="" class="bg-[#1E293B]">Pilih Kota/Kabupaten</option>
                                        </select>

                                        <p class="mt-1 text-xs text-red-300 hidden" data-org-error-for="city"></p>
                                        @error('city')
                                        <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-sm">Provinsi <span class="text-red-400">*</span></label>
                                        
                                        <input type="text" name="province" id="org_input_province" value="{{ old('province') }}"
                                               data-org-step="2" data-required="true"
                                               class="w-full rounded-lg border bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40
                                                      focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70
                                                      @error('province') border-red-400/80 focus:ring-red-400/80 @else border-white/20 @enderror"
                                               placeholder="Provinsi">

                                        <select name="province" id="org_select_province" disabled
                                               data-org-step="2" data-required="true"
                                               class="hidden w-full rounded-lg border bg-white/5 px-4 py-3 text-sm text-white
                                                      focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70
                                                      @error('province') border-red-400/80 focus:ring-red-400/80 @else border-white/20 @enderror">
                                            <option value="" class="bg-[#1E293B]">Pilih Provinsi</option>
                                        </select>

                                        <p class="mt-1 text-xs text-red-300 hidden" data-org-error-for="province"></p>
                                        @error('province')
                                        <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <label class="text-sm">Kecamatan <span class="text-red-400">*</span></label>
                                        
                                        <input type="text" name="district" id="org_input_district" value="{{ old('district') }}"
                                               data-org-step="2" data-required="true"
                                               class="w-full rounded-lg border bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40
                                                      focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70
                                                      @error('district') border-red-400/80 focus:ring-red-400/80 @else border-white/20 @enderror"
                                               placeholder="Kecamatan">

                                        <select name="district" id="org_select_district" disabled
                                               data-org-step="2" data-required="true"
                                               class="hidden w-full rounded-lg border bg-white/5 px-4 py-3 text-sm text-white
                                                      focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70
                                                      @error('district') border-red-400/80 focus:ring-red-400/80 @else border-white/20 @enderror">
                                            <option value="" class="bg-[#1E293B]">Pilih Kecamatan</option>
                                        </select>

                                        <p class="mt-1 text-xs text-red-300 hidden" data-org-error-for="district"></p>
                                        @error('district')
                                        <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-sm">Kelurahan / Desa <span class="text-red-400">*</span></label>
                                        
                                        <input type="text" name="village" id="org_input_village" value="{{ old('village') }}"
                                               data-org-step="2" data-required="true"
                                               class="w-full rounded-lg border bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40
                                                      focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70
                                                      @error('village') border-red-400/80 focus:ring-red-400/80 @else border-white/20 @enderror"
                                               placeholder="Kelurahan">

                                        <select name="village" id="org_select_village" disabled
                                               data-org-step="2" data-required="true"
                                               class="hidden w-full rounded-lg border bg-white/5 px-4 py-3 text-sm text-white
                                                      focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70
                                                      @error('village') border-red-400/80 focus:ring-red-400/80 @else border-white/20 @enderror">
                                            <option value="" class="bg-[#1E293B]">Pilih Kelurahan</option>
                                        </select>

                                        <p class="mt-1 text-xs text-red-300 hidden" data-org-error-for="village"></p>
                                        @error('village')
                                        <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <label class="text-sm">Kode Pos <span class="text-red-400">*</span></label>
                                        
                                        <input type="text" name="postal_code" id="org_input_postal_code" value="{{ old('postal_code') }}"
                                               data-org-step="2" data-required="true"
                                               class="w-full rounded-lg border bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/40
                                                      focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70
                                                      @error('postal_code') border-red-400/80 focus:ring-red-400/80 @else border-white/20 @enderror"
                                               placeholder="Kode pos">

                                        <select name="postal_code" id="org_select_postal_code" disabled
                                               data-org-step="2" data-required="true"
                                               class="hidden w-full rounded-lg border bg-white/5 px-4 py-3 text-sm text-white
                                                      focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70
                                                      @error('postal_code') border-red-400/80 focus:ring-red-400/80 @else border-white/20 @enderror">
                                            <option value="" class="bg-[#1E293B]">Pilih Kode Pos</option>
                                        </select>

                                        <p class="mt-1 text-xs text-red-300 hidden" data-org-error-for="postal_code"></p>
                                        @error('postal_code')
                                        <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-sm">Negara <span class="text-red-400">*</span></label>
                                        <select name="country"
                                               id="org_select_country"
                                               data-old="{{ old('country', 'Indonesia') }}"
                                               data-org-step="2" data-required="true"
                                               class="w-full rounded-lg border bg-white/5 px-4 py-3 text-sm text-white
                                                      focus:outline-none focus:ring-2 focus:ring-[#2563EB]/70
                                                      @error('country') border-red-400/80 focus:ring-red-400/80 @else border-white/20 @enderror">
                                            <option value="Indonesia" class="bg-[#1E293B]" selected>Indonesia</option>
                                            <option value="" class="bg-[#1E293B]">Loading other countries...</option>
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
                                        Kembali
                                    </button>

                                    <button type="button"
                                            class="inline-flex items-center rounded-lg bg-[#2563EB]
                                                   px-6 py-2.5 text-sm font-medium shadow-md shadow-blue-500/20
                                                   hover:bg-[#3B82F6] transition"
                                            onclick="goToOrgStep(3)">
                                        Lanjut
                                    </button>
                                </div>
                            </section>

                            <section id="org-step-3" class="org-step space-y-6 hidden">
                                <div>
                                    <h2 class="text-2xl font-normal">Lembaga</h2>
                                    <p class="mt-1 text-sm text-[#BEDBFF]/80">Kontak utama yang akan mengelola SertiKu</p>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm">Nama Lengkap Lembaga <span class="text-red-400">*</span></label>
                                    <input type="text" name="admin_name" value="{{ old('admin_name', $user->name) }}"
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
                                    <label class="text-sm">Email Lembaga <span class="text-red-400">*</span></label>
                                    <input type="email" name="admin_email" value="{{ old('admin_email', $user->email) }}"
                                           readonly
                                           data-org-step="3" data-required="true"
                                           class="w-full rounded-lg border border-white/10 bg-white/10 px-4 py-3 text-sm text-white/70 cursor-not-allowed
                                                  focus:outline-none">
                                    <p class="mt-1 text-xs text-red-300 hidden" data-org-error-for="admin_email"></p>
                                    @error('admin_email')
                                    <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm">Nomor Telepon Lembaga <span class="text-red-400">*</span></label>
                                    <input type="text" name="admin_phone" value="{{ old('admin_phone', $user->phone) }}"
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

                                <div class="mt-4 flex items-center justify-between border-t border-white/10 pt-4">
                                    <button type="button"
                                            class="inline-flex items-center rounded-lg border border-white/30 bg-white/5 px-5 py-2 text-sm hover:bg-white/10"
                                            onclick="goToOrgStep(2, false)">
                                        Kembali
                                    </button>

                                    <button type="button"
                                            class="inline-flex items-center rounded-lg bg-[#2563EB]
                                                   px-6 py-2.5 text-sm font-medium shadow-md shadow-blue-500/20
                                                   hover:bg-[#3B82F6] transition"
                                            onclick="goToOrgStep(4)">
                                        Lanjut
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
                                        Kembali
                                    </button>

                                    <button type="submit"
                                            class="inline-flex items-center rounded-lg bg-[#3B82F6]
                                                   px-6 py-2.5 text-sm font-medium shadow-[0_10px_15px_-3px_rgba(43,127,255,0.5)]
                                                   hover:brightness-110 transition">
                                        Simpan Profil
                                    </button>
                                </div>
                            </section>
                        </form>
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

                userBtn.classList.add('bg-[#3B82F6]', 'text-white', 'shadow-sm');
                userBtn.classList.remove('text-white/70');

                orgBtn.classList.remove('bg-[#3B82F6]', 'text-white', 'shadow-sm');
                orgBtn.classList.add('text-white/70');
            } else {
                userTab.classList.add('hidden');
                orgTab.classList.remove('hidden');
                stepperUser.classList.add('hidden');
                stepperOrg.classList.remove('hidden');

                orgBtn.classList.add('bg-[#3B82F6]', 'text-white', 'shadow-sm');
                orgBtn.classList.remove('text-white/70');

                userBtn.classList.remove('bg-[#3B82F6]', 'text-white', 'shadow-sm');
                userBtn.classList.add('text-white/70');
            }
        }

        // ===== WIZARD PENGGUNA =====
        let currentStep = parseInt(document.getElementById('userStepInput')?.value || '1');

        function setStepUI(step) {
            currentStep = step;
            const input = document.getElementById('userStepInput');
            if (input) input.value = step;
            document.querySelectorAll('.wizard-step').forEach((el, idx) => {
                el.classList.toggle('hidden', idx + 1 !== step);
            });
            const progress = document.getElementById('userStepProgress');
            if(progress) progress.style.width = (step / 2 * 100) + '%';
            for (let i = 1; i <= 2; i++) {
                const icon = document.getElementById('userStepIcon-' + i);
                const label = document.getElementById('userStepLabel-' + i);
                if (!icon || !label) continue;
                if (i < step) {
                    icon.className = 'flex h-12 w-12 items-center justify-center rounded-full bg-[#10B981] text-white shadow';
                    label.className = 'mt-2 text-xs font-medium';
                } else if (i === step) {
                    icon.className = 'flex h-12 w-12 items-center justify-center rounded-full bg-[#3B82F6] text-white shadow';
                    label.className = 'mt-2 text-xs font-medium';
                } else {
                    icon.className = 'flex h-12 w-12 items-center justify-center rounded-full border border-white/30 bg-white/5 text-white/60';
                    label.className = 'mt-2 text-xs text-white/60';
                }
            }
        }

        function validateStep(step) {
            let valid = true;
            document.querySelectorAll('[data-step="' + step + '"][data-required="true"]').forEach((input) => {
                const errEl = document.querySelector('[data-error-for="' + input.name + '"]');
                if (errEl) { errEl.classList.add('hidden'); errEl.textContent = ''; }
                input.classList.remove('border-red-400', 'focus:ring-red-400/80');
            });
            document.querySelectorAll('[data-step="' + step + '"][data-required="true"]').forEach((input) => {
                if (!input.value.trim()) {
                    valid = false;
                    const errEl = document.querySelector('[data-error-for="' + input.name + '"]');
                    if (errEl) { errEl.textContent = 'Field ini wajib diisi.'; errEl.classList.remove('hidden'); }
                    input.classList.add('border-red-400', 'focus:ring-red-400/80');
                }
            });
            return valid;
        }

        function goToStep(step, needValidate = true) {
            if (needValidate && step > currentStep) {
                if (!validateStep(currentStep)) return;
            }
            setStepUI(step);
        }

        function updateStrength(value) {
            const bar = document.getElementById('passwordStrengthBar');
            const label = document.getElementById('passwordStrengthLabel');
            if (!bar || !label) return;
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
            if (score <= 2) label.textContent = 'Keamanan password: Lemah';
            else if (score === 3 || score === 4) label.textContent = 'Keamanan password: Cukup';
            else label.textContent = 'Keamanan password: Kuat';
        }

        // ===== WIZARD LEMBAGA =====
        let orgCurrentStep = parseInt(document.getElementById('orgStepInput')?.value || '1');

        function setOrgStepUI(step) {
            orgCurrentStep = step;
            const input = document.getElementById('orgStepInput');
            if (input) input.value = step;
            document.querySelectorAll('.org-step').forEach((el, idx) => {
                el.classList.toggle('hidden', idx + 1 !== step);
            });
            const progress = document.getElementById('orgStepProgress');
            if (progress) progress.style.width = (step / 4 * 100) + '%';
            for (let i = 1; i <= 4; i++) {
                const icon = document.getElementById('orgStepIcon-' + i);
                const label = document.getElementById('orgStepLabel-' + i);
                if (!icon || !label) continue;
                if (i < step) {
                    icon.className = 'flex h-12 w-12 items-center justify-center rounded-full bg-[#10B981] text-white shadow';
                    label.className = 'mt-2 text-xs font-medium';
                } else if (i === step) {
                    icon.className = 'flex h-12 w-12 items-center justify-center rounded-full bg-[#3B82F6] text-white shadow';
                    label.className = 'mt-2 text-xs font-medium';
                } else {
                    icon.className = 'flex h-12 w-12 items-center justify-center rounded-full border border-white/30 bg-white/5 text-white/60';
                    label.className = 'mt-2 text-xs text-white/60';
                }
            }
        }

        function validateOrgStep(step) {
            let valid = true;
            document.querySelectorAll('[data-org-step="' + step + '"][data-required="true"]').forEach((input) => {
                const errEl = document.querySelector('[data-org-error-for="' + input.name + '"]');
                if (errEl) { errEl.classList.add('hidden'); errEl.textContent = ''; }
                input.classList.remove('border-red-400', 'focus:ring-red-400/80');
            });
            document.querySelectorAll('[data-org-step="' + step + '"][data-required="true"]').forEach((input) => {
                // SKIP validation if input is disabled (standard HTML behavior)
                if (input.disabled) return;

                if (!input.value.trim()) {
                    valid = false;
                    const errEl = document.querySelector('[data-org-error-for="' + input.name + '"]');
                    if (errEl) { errEl.textContent = 'Field ini wajib diisi.'; errEl.classList.remove('hidden'); }
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

        // ===== PASSWORD STRENGTH (Lembaga) =====
        function updateOrgStrength(value) {
            const bar = document.getElementById('orgPasswordStrengthBar');
            const label = document.getElementById('orgPasswordStrengthLabel');
            if (!bar || !label) return;

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

            if (score <= 2) label.textContent = 'Keamanan password: Lemah';
            else if (score === 3 || score === 4) label.textContent = 'Keamanan password: Cukup';
            else label.textContent = 'Keamanan password: Kuat';
        }

        // ===== LISTENER FOR INIT =====
        document.addEventListener('DOMContentLoaded', () => {
            // Init based on OLD input
            const oldType = "{{ old('account_type', 'personal') }}";
            const oldUserStep = parseInt("{{ old('step', 1) }}");
            const oldOrgStep = parseInt("{{ old('org_step', 1) }}");

            if (oldType === 'institution') {
                switchRegisterTab('lembaga');
                setOrgStepUI(oldOrgStep);
            } else {
                switchRegisterTab('pengguna');
                setStepUI(oldUserStep);
            }
        });

        // ===== LOCATION API HANDLING (Scoped IIFE) =====
        (function () {
            const apiBaseUrl = 'https://www.emsifa.com/api-wilayah-indonesia/api';

            async function fetchWithTimeout(resource, options = {}) {
                const { timeout = 5000 } = options;
                const controller = new AbortController();
                const id = setTimeout(() => controller.abort(), timeout);
                try {
                    const response = await fetch(resource, { ...options, signal: controller.signal });
                    clearTimeout(id);
                    return response;
                } catch (error) {
                    clearTimeout(id);
                    throw error;
                }
            }

            function toTitleCase(str) {
                return str.replace(/\w\S*/g, function (txt) {
                    return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
                });
            }

            function resetSelect(field) {
                const select = document.getElementById(`org_select_${field}`);
                if (select) {
                    select.innerHTML = `<option value="" class="bg-[#1E293B]">Pilih ${toTitleCase(field.replace('_', ' '))}</option>`;
                }
            }

            async function loadCountries() {
                const userSelect = document.getElementById('user_select_country');
                const orgSelect = document.getElementById('org_select_country');

                try {
                    const response = await fetchWithTimeout('https://restcountries.com/v3.1/all?fields=name', { timeout: 5000 });
                    if (!response.ok) throw new Error('API Error');
                    const data = await response.json();
                    data.sort((a, b) => a.name.common.localeCompare(b.name.common));

                    const optionsHtml = data
                        .filter(c => c.name.common !== 'Indonesia')
                        .map(c => `<option value="${c.name.common}" class="bg-[#1E293B]">${c.name.common}</option>`)
                        .join('');

                    if (userSelect) {
                        userSelect.innerHTML = '<option value="" class="bg-[#1E293B]">Pilih Negara</option><option value="Indonesia" class="bg-[#1E293B]">Indonesia</option>' + optionsHtml;
                        const oldVal = userSelect.dataset.old;
                        if (oldVal) userSelect.value = oldVal;
                    }
                    if (orgSelect) {
                        orgSelect.innerHTML = '<option value="Indonesia" class="bg-[#1E293B]" selected>Indonesia</option>' + optionsHtml;
                        const oldVal = orgSelect.dataset.old || 'Indonesia';
                        if (oldVal) {
                            orgSelect.value = oldVal;
                        }
                    }
                } catch (error) {
                    console.error('Failed to load countries:', error);
                    const fallbackCountries = ["Malaysia", "Singapore", "Thailand", "Vietnam", "Philippines", "Brunei", "Cambodia", "Laos", "Myanmar", "Timor-Leste", "Japan", "South Korea", "China", "United States", "United Kingdom", "Australia", "Saudi Arabia", "United Arab Emirates", "Other"];
                    fallbackCountries.sort((a, b) => a.localeCompare(b));
                    const optionsHtml = fallbackCountries.map(c => `<option value="${c}" class="bg-[#1E293B]">${c}</option>`).join('');

                    if (userSelect) {
                        userSelect.innerHTML = '<option value="" class="bg-[#1E293B]">Pilih Negara</option><option value="Indonesia" class="bg-[#1E293B]">Indonesia</option>' + optionsHtml;
                        const oldVal = userSelect.dataset.old;
                        if (oldVal) userSelect.value = oldVal;
                    }
                    if (orgSelect) {
                        orgSelect.innerHTML = '<option value="Indonesia" class="bg-[#1E293B]" selected>Indonesia</option>' + optionsHtml;
                        const oldVal = orgSelect.dataset.old || 'Indonesia';
                        if (oldVal) orgSelect.value = oldVal;
                    }
                }
            }

            async function loadProvinces() {
                const select = document.getElementById('org_select_province');
                if (!select) return;
                select.innerHTML = '<option value="" class="bg-[#1E293B]">Loading...</option>';
                try {
                    const response = await fetch(`${apiBaseUrl}/provinces.json`);
                    if (!response.ok) throw new Error('API Error');
                    const provinces = await response.json();
                    select.innerHTML = '<option value="" class="bg-[#1E293B]">Pilih Provinsi</option>';
                    provinces.forEach(prov => {
                        const option = document.createElement('option');
                        option.value = toTitleCase(prov.name);
                        option.dataset.id = prov.id;
                        option.textContent = toTitleCase(prov.name);
                        option.className = 'bg-[#1E293B]';
                        select.appendChild(option);
                    });
                } catch (error) {
                    console.error('Error loading provinces:', error);
                    const fallbackProvinces = [
                        { id: "11", name: "ACEH" }, { id: "12", name: "SUMATERA UTARA" }, { id: "13", name: "SUMATERA BARAT" },
                        { id: "14", name: "RIAU" }, { id: "15", name: "JAMBI" }, { id: "16", name: "SUMATERA SELATAN" },
                        { id: "17", name: "BENGKULU" }, { id: "18", name: "LAMPUNG" }, { id: "19", name: "KEPULAUAN BANGKA BELITUNG" },
                        { id: "21", name: "KEPULAUAN RIAU" }, { id: "31", name: "DKI JAKARTA" }, { id: "32", name: "JAWA BARAT" },
                        { id: "33", name: "JAWA TENGAH" }, { id: "34", name: "DI YOGYAKARTA" }, { id: "35", name: "JAWA TIMUR" },
                        { id: "36", name: "BANTEN" }, { id: "51", name: "BALI" }, { id: "52", name: "NUSA TENGGARA BARAT" },
                        { id: "53", name: "NUSA TENGGARA TIMUR" }, { id: "61", name: "KALIMANTAN BARAT" }, { id: "62", name: "KALIMANTAN TENGAH" },
                        { id: "63", name: "KALIMANTAN SELATAN" }, { id: "64", name: "KALIMANTAN TIMUR" }, { id: "65", name: "KALIMANTAN UTARA" },
                        { id: "71", name: "SULAWESI UTARA" }, { id: "72", name: "SULAWESI TENGAH" }, { id: "73", name: "SULAWESI SELATAN" },
                        { id: "74", name: "SULAWESI TENGGARA" }, { id: "75", name: "GORONTALO" }, { id: "76", name: "SULAWESI BARAT" },
                        { id: "81", name: "MALUKU" }, { id: "82", name: "MALUKU UTARA" }, { id: "91", name: "PAPUA BARAT" },
                        { id: "94", name: "PAPUA" }
                    ];
                    select.innerHTML = '<option value="" class="bg-[#1E293B]">Pilih Provinsi</option>';
                    fallbackProvinces.forEach(prov => {
                        const option = document.createElement('option');
                        option.value = toTitleCase(prov.name);
                        option.dataset.id = prov.id;
                        option.textContent = toTitleCase(prov.name);
                        option.className = 'bg-[#1E293B]';
                        select.appendChild(option);
                    });
                }
            }

            async function loadCities(provinceId) {
                const select = document.getElementById('org_select_city');
                if (!select || !provinceId) return;
                select.innerHTML = '<option value="" class="bg-[#1E293B]">Loading...</option>';
                resetSelect('district'); resetSelect('village'); resetSelect('postal_code');
                try {
                    const response = await fetchWithTimeout(`${apiBaseUrl}/regencies/${provinceId}.json`, { timeout: 5000 });
                    const cities = await response.json();
                    select.innerHTML = '<option value="" class="bg-[#1E293B]">Pilih Kota/Kabupaten</option>';
                    cities.forEach(city => {
                        const option = document.createElement('option');
                        option.value = toTitleCase(city.name);
                        option.dataset.id = city.id;
                        option.textContent = toTitleCase(city.name);
                        option.className = 'bg-[#1E293B]';
                        select.appendChild(option);
                    });
                } catch (error) {
                    console.error('Error loading cities:', error);
                    select.innerHTML = '<option value="" class="bg-[#1E293B]">Gagal memuat data</option>';
                }
            }

            async function loadDistricts(cityId) {
                const select = document.getElementById('org_select_district');
                if (!select || !cityId) return;
                select.innerHTML = '<option value="" class="bg-[#1E293B]">Loading...</option>';
                resetSelect('village'); resetSelect('postal_code');
                try {
                    const response = await fetchWithTimeout(`${apiBaseUrl}/districts/${cityId}.json`, { timeout: 5000 });
                    const districts = await response.json();
                    select.innerHTML = '<option value="" class="bg-[#1E293B]">Pilih Kecamatan</option>';
                    districts.forEach(dist => {
                        const option = document.createElement('option');
                        option.value = toTitleCase(dist.name);
                        option.dataset.id = dist.id;
                        option.textContent = toTitleCase(dist.name);
                        option.className = 'bg-[#1E293B]';
                        select.appendChild(option);
                    });
                } catch (error) {
                    console.error('Error loading districts:', error);
                    select.innerHTML = '<option value="" class="bg-[#1E293B]">Gagal memuat data</option>';
                }
            }

            async function loadVillages(districtId) {
                const select = document.getElementById('org_select_village');
                if (!select || !districtId) return;
                select.innerHTML = '<option value="" class="bg-[#1E293B]">Loading...</option>';
                resetSelect('postal_code');
                try {
                    const response = await fetchWithTimeout(`${apiBaseUrl}/villages/${districtId}.json`, { timeout: 5000 });
                    const villages = await response.json();
                    select.innerHTML = '<option value="" class="bg-[#1E293B]">Pilih Kelurahan</option>';
                    villages.forEach(vill => {
                        const option = document.createElement('option');
                        option.value = toTitleCase(vill.name);
                        option.dataset.id = vill.id;
                        option.textContent = toTitleCase(vill.name);
                        option.className = 'bg-[#1E293B]';
                        select.appendChild(option);
                    });
                } catch (error) {
                    console.error('Error loading villages:', error);
                    select.innerHTML = '<option value="" class="bg-[#1E293B]">Gagal memuat data</option>';
                }
            }

            async function fetchPostalCode(villageName) {
                const select = document.getElementById('org_select_postal_code');
                const input = document.getElementById('org_input_postal_code');

                if (!select || !villageName) return;

                // Set loading state
                select.innerHTML = '<option value="" class="bg-[#1E293B]">Mencari Kode Pos...</option>';

                try {
                    const districtName = document.getElementById('org_select_district').value;
                    const cleanVillage = villageName.replace(/^(Kelurahan|Desa)\s+/i, '');
                    const cleanDistrict = districtName.replace(/^(Kecamatan|Distrik)\s+/i, '');

                    const query = `${cleanVillage} ${cleanDistrict}`;
                    const response = await fetch(`https://kodepos.vercel.app/search?q=${query}`);
                    const data = await response.json();

                    if (data.data && data.data.length > 0) {
                        let postalCodes = data.data
                            .map(item => item.postal_code || item.code)
                            .filter(code => code);

                        postalCodes = [...new Set(postalCodes)];

                        if (postalCodes.length > 0) {
                            if (input) { input.classList.add('hidden'); input.disabled = true; }
                            select.classList.remove('hidden'); select.disabled = false;
                            select.innerHTML = '<option value="" class="bg-[#1E293B]">Pilih Kode Pos</option>';
                            postalCodes.forEach(code => {
                                const option = document.createElement('option');
                                option.value = code;
                                option.textContent = code;
                                option.className = 'bg-[#1E293B]';
                                select.appendChild(option);
                            });
                            if (postalCodes.length === 1) select.value = postalCodes[0];
                        } else {
                            throw new Error('No valid postal codes found');
                        }
                    } else {
                        throw new Error('Not found');
                    }
                } catch (e) {
                    // Fallback to Input
                    if (input) {
                        input.classList.remove('hidden');
                        input.disabled = false;
                        input.placeholder = "Kode Pos (Tidak ditemukan, isi manual)";
                        input.focus();
                    }
                    select.classList.add('hidden');
                    select.disabled = true;
                }
            }

            function handleOrgCountryChange(e) {
                const country = e.target.value;
                const inputs = ['province', 'city', 'district', 'village', 'postal_code'];
                inputs.forEach(field => {
                    const inputEl = document.getElementById(`org_input_${field}`);
                    const selectEl = document.getElementById(`org_select_${field}`);
                    if (country === 'Indonesia') {
                        inputEl.classList.add('hidden'); inputEl.disabled = true;
                        selectEl.classList.remove('hidden'); selectEl.disabled = false;
                    } else {
                        inputEl.classList.remove('hidden'); inputEl.disabled = false;
                        selectEl.classList.add('hidden'); selectEl.disabled = true;
                    }
                });
                if (country === 'Indonesia') {
                    const provinceSelect = document.getElementById('org_select_province');
                    if (provinceSelect.options.length <= 1) loadProvinces();
                }
            }

            // Init Logic
            document.addEventListener('DOMContentLoaded', () => {
                loadCountries();
                const orgCountry = document.querySelector('select[name="country"][data-org-step="2"]');
                if (orgCountry) {
                    orgCountry.addEventListener('change', handleOrgCountryChange);
                    if (orgCountry.value === 'Indonesia') {
                        handleOrgCountryChange({ target: orgCountry });
                    }
                }

                // Change Listeners for Region Selects
                document.addEventListener('change', function (e) {
                    const target = e.target;
                    if (!target) return;
                    if (target.id === 'org_select_province') {
                        const id = target.options[target.selectedIndex]?.dataset.id;
                        if (id) loadCities(id);
                    } else if (target.id === 'org_select_city') {
                        const id = target.options[target.selectedIndex]?.dataset.id;
                        if (id) loadDistricts(id);
                    } else if (target.id === 'org_select_district') {
                        const id = target.options[target.selectedIndex]?.dataset.id;
                        if (id) loadVillages(id);
                    } else if (target.id === 'org_select_village') {
                        const val = target.value;
                        if (val) fetchPostalCode(val);
                    }
                });
            });

        })();
    </script>
</x-layouts.app>