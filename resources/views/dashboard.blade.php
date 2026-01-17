<x-layouts.app title="Dashboard – SertiKu">
    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 flex flex-col gap-3"></div>

    <main class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <!-- Welcome Card -->
        <div class="rounded-3xl border border-white/10 bg-[#0F172A] p-8 shadow-2xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-[#8B5CF6] rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">
                    Selamat Datang, {{ auth()->user()->name }}!
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-lg mb-2">Mengapa perlu melengkapi profil?</h3>
                        <ul class="text-[#BEDBFF]/80 space-y-2 text-sm">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Akses fitur sesuai jenis akun Anda
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Terbitkan atau terima sertifikat digital
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Kelola dan verifikasi sertifikat dengan mudah
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Step 1: Account Type Selection -->
            <div id="step1">
                <h2 class="text-white font-bold text-xl mb-4 text-center">Pilih Jenis Akun Anda</h2>
                <div class="grid md:grid-cols-2 gap-4 mb-6">
                    <!-- Personal Account -->
                    <div onclick="selectAccountType('personal')" id="card-personal"
                        class="account-card bg-emerald-500/10 border-2 border-emerald-500/30 rounded-2xl p-6 hover:border-emerald-400 transition cursor-pointer group">
                        <div class="flex items-center gap-4 mb-4">
                            <div
                                class="w-14 h-14 bg-[#10B981] rounded-full flex items-center justify-center group-hover:scale-110 transition">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-emerald-400 font-bold text-lg">Akun Personal</h3>
                                <p class="text-emerald-300/60 text-sm">Untuk individu</p>
                            </div>
                            <div class="ml-auto hidden" id="check-personal">
                                <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-[#BEDBFF]/70 text-sm mb-4">
                            Cocok untuk Anda yang ingin menerima, menyimpan, dan membagikan sertifikat digital dari
                            berbagai lembaga.
                        </p>
                        <ul class="text-[#BEDBFF]/60 text-xs space-y-1">
                            <li> Simpan sertifikat di portofolio</li>
                            <li> Verifikasi sertifikat kapan saja</li>
                            <li> Bagikan ke LinkedIn & sosial media</li>
                        </ul>
                    </div>

                    <!-- Institution Account -->
                    <div onclick="selectAccountType('institution')" id="card-institution"
                        class="account-card bg-blue-500/10 border-2 border-blue-500/30 rounded-2xl p-6 hover:border-blue-400 transition cursor-pointer group">
                        <div class="flex items-center gap-4 mb-4">
                            <div
                                class="w-14 h-14 bg-[#3B82F6] rounded-full flex items-center justify-center group-hover:scale-110 transition">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-blue-400 font-bold text-lg">Akun Lembaga</h3>
                                <p class="text-blue-300/60 text-sm">Untuk organisasi</p>
                            </div>
                            <div class="ml-auto hidden" id="check-institution">
                                <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-[#BEDBFF]/70 text-sm mb-4">
                            Cocok untuk universitas, perusahaan, atau lembaga yang ingin menerbitkan sertifikat digital.
                        </p>
                        <ul class="text-[#BEDBFF]/60 text-xs space-y-1">
                            <li> Terbitkan sertifikat tanpa batas</li>
                            <li> Kelola template sertifikat</li>
                            <li> Pantau statistik verifikasi</li>
                        </ul>
                    </div>
                </div>

                <!-- Continue Button -->
                <button onclick="goToStep2()" id="btn-continue" disabled
                    class="w-full py-4 px-6 bg-gray-600 text-white/50 font-bold text-center rounded-xl transition cursor-not-allowed">
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                        Pilih jenis akun terlebih dahulu
                    </span>
                </button>
            </div>

            <!-- Step 2: Personal Form -->
            <div id="step2-personal" class="hidden">
                <div class="flex items-center gap-3 mb-6">
                    <button onclick="goBackToStep1()"
                        class="p-2 text-white/60 hover:text-white hover:bg-white/10 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <h2 class="text-white font-bold text-xl">Lengkapi Profil Personal</h2>
                </div>

                <form id="form-personal" class="space-y-5">
                    @csrf
                    <input type="hidden" name="account_type" value="personal">

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-white/80 text-sm font-medium mb-2">Nama Lengkap *</label>
                            <input type="text" name="name" value="{{ auth()->user()->name }}" required
                                class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-white/40 focus:outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-white/80 text-sm font-medium mb-2">Nomor Telepon</label>
                            <input type="tel" name="phone" placeholder="08xxxxxxxxxx"
                                class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-white/40 focus:outline-none focus:border-blue-500">
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-white/80 text-sm font-medium mb-2">Pekerjaan</label>
                            <input type="text" name="occupation" placeholder="Contoh: Software Engineer"
                                class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-white/40 focus:outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-white/80 text-sm font-medium mb-2">Instansi/Perusahaan</label>
                            <input type="text" name="user_institution" placeholder="Nama tempat Anda bekerja/belajar"
                                class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-white/40 focus:outline-none focus:border-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-white/80 text-sm font-medium mb-2">Kota</label>
                        <input type="text" name="city" placeholder="Kota tempat tinggal"
                            class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-white/40 focus:outline-none focus:border-blue-500">
                    </div>

                    <button type="submit" id="btn-submit-personal"
                        class="w-full py-4 px-6 bg-[#10B981] hover:bg-[#059669] text-white font-bold text-center rounded-xl shadow-md shadow-emerald-500/20 transition disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="btn-text flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan & Lanjutkan ke Dashboard
                        </span>
                        <span class="btn-loading hidden flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Menyimpan...
                        </span>
                    </button>
                </form>
            </div>

            <!-- Step 2: Institution Form -->
            <div id="step2-institution" class="hidden">
                <div class="flex items-center gap-3 mb-6">
                    <button onclick="goBackToStep1()"
                        class="p-2 text-white/60 hover:text-white hover:bg-white/10 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <h2 class="text-white font-bold text-xl">Lengkapi Profil Lembaga</h2>
                </div>

                <form id="form-institution" class="space-y-5">
                    @csrf
                    <input type="hidden" name="account_type" value="institution">

                    <!-- Institution Info -->
                    <div class="bg-blue-500/10 border border-blue-500/20 rounded-xl p-4 mb-4">
                        <h3 class="text-blue-400 font-bold mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" />
                            </svg>
                            Informasi Lembaga
                        </h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-white/80 text-sm font-medium mb-2">Nama Lembaga *</label>
                                <input type="text" name="institution_name" placeholder="Nama resmi lembaga" required
                                    class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-white/40 focus:outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-white/80 text-sm font-medium mb-2">Jenis Lembaga *</label>
                                <select name="institution_type" required
                                    class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-blue-500">
                                    <option value="" class="bg-gray-800">Pilih jenis</option>
                                    <option value="university" class="bg-gray-800">Universitas/Perguruan Tinggi</option>
                                    <option value="school" class="bg-gray-800">Sekolah</option>
                                    <option value="company" class="bg-gray-800">Perusahaan</option>
                                    <option value="organization" class="bg-gray-800">Organisasi/Komunitas</option>
                                    <option value="government" class="bg-gray-800">Instansi Pemerintah</option>
                                    <option value="other" class="bg-gray-800">Lainnya</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-white/80 text-sm font-medium mb-2">Website</label>
                                <input type="url" name="website" placeholder="https://contoh.com"
                                    class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-white/40 focus:outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-white/80 text-sm font-medium mb-2">Kota *</label>
                                <input type="text" name="city" placeholder="Kota lokasi lembaga" required
                                    class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-white/40 focus:outline-none focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Admin Info -->
                    <div class="bg-purple-500/10 border border-purple-500/20 rounded-xl p-4">
                        <h3 class="text-purple-400 font-bold mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Informasi Admin/PIC
                        </h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-white/80 text-sm font-medium mb-2">Nama Admin *</label>
                                <input type="text" name="admin_name" value="{{ auth()->user()->name }}" required
                                    class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-white/40 focus:outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-white/80 text-sm font-medium mb-2">Jabatan</label>
                                <input type="text" name="admin_position" placeholder="Contoh: Manager HRD"
                                    class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-white/40 focus:outline-none focus:border-blue-500">
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-white/80 text-sm font-medium mb-2">Nomor Telepon Admin</label>
                            <input type="tel" name="admin_phone" placeholder="08xxxxxxxxxx"
                                class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-white/40 focus:outline-none focus:border-blue-500">
                        </div>
                    </div>

                    <button type="submit" id="btn-submit-institution"
                        class="w-full py-4 px-6 bg-[#3B82F6] hover:bg-[#2563EB] text-white font-bold text-center rounded-xl shadow-md shadow-blue-500/20 transition disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="btn-text flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan & Lanjutkan ke Dashboard Lembaga
                        </span>
                        <span class="btn-loading hidden flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Menyimpan...
                        </span>
                    </button>
                </form>
            </div>

            <p class="text-center text-white/40 text-sm mt-4">
                Proses ini hanya membutuhkan 2-3 menit
            </p>
        </div>
    </main>

    <script>
        let selectedType = null;

        // Toast notification function
        function showToast(type, message, countdown = 0, redirectUrl = null) {
            const container = document.getElementById('toast-container');
            const toastId = 'toast-' + Date.now();

            const bgColor = type === 'success'
                ? 'bg-[#10B981]'
                : 'bg-[#EF4444]';

            const icon = type === 'success'
                ? `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                   </svg>`
                : `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                   </svg>`;

            const countdownHtml = countdown > 0
                ? `<div class="mt-2 text-sm opacity-90">
                       Mengarahkan ke dashboard dalam <span id="${toastId}-countdown" class="font-bold">${countdown}</span> detik...
                   </div>`
                : '';

            const toast = document.createElement('div');
            toast.id = toastId;
            toast.className = `${bgColor} text-white px-5 py-4 rounded-xl shadow-2xl flex items-start gap-3 min-w-[320px] max-w-[400px] transform translate-x-full transition-transform duration-300`;
            toast.innerHTML = `
                <div class="flex-shrink-0">${icon}</div>
                <div class="flex-1">
                    <div class="font-bold">${message}</div>
                    ${countdownHtml}
                </div>
                <button onclick="removeToast('${toastId}')" class="flex-shrink-0 opacity-70 hover:opacity-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            `;

            container.appendChild(toast);

            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 10);

            // Handle countdown
            if (countdown > 0 && redirectUrl) {
                let remaining = countdown;
                const countdownEl = document.getElementById(`${toastId}-countdown`);

                const interval = setInterval(() => {
                    remaining--;
                    if (countdownEl) countdownEl.textContent = remaining;

                    if (remaining <= 0) {
                        clearInterval(interval);
                        window.location.href = redirectUrl;
                    }
                }, 1000);
            }
        }

        function removeToast(toastId) {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.classList.add('translate-x-full');
                setTimeout(() => toast.remove(), 300);
            }
        }

        // Handle form submission
        async function submitForm(form, buttonId) {
            const button = document.getElementById(buttonId);
            const btnText = button.querySelector('.btn-text');
            const btnLoading = button.querySelector('.btn-loading');

            // Show loading state
            button.disabled = true;
            btnText.classList.add('hidden');
            btnLoading.classList.remove('hidden');

            try {
                const formData = new FormData(form);

                const response = await fetch('{{ route("onboarding.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                const data = await response.json();

                if (data.success) {
                    // Show success toast with countdown
                    showToast('success', data.message || 'Profil berhasil disimpan!', 5, data.redirect_url);
                } else {
                    showToast('error', data.message || 'Terjadi kesalahan. Silakan coba lagi.');
                    resetButton(button, btnText, btnLoading);
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('error', 'Terjadi kesalahan. Silakan coba lagi.');
                resetButton(button, btnText, btnLoading);
            }
        }

        function resetButton(button, btnText, btnLoading) {
            button.disabled = false;
            btnText.classList.remove('hidden');
            btnLoading.classList.add('hidden');
        }

        // Attach form handlers
        document.getElementById('form-personal').addEventListener('submit', function (e) {
            e.preventDefault();
            submitForm(this, 'btn-submit-personal');
        });

        document.getElementById('form-institution').addEventListener('submit', function (e) {
            e.preventDefault();
            submitForm(this, 'btn-submit-institution');
        });

        function selectAccountType(type) {
            selectedType = type;

            // Reset all cards
            document.querySelectorAll('.account-card').forEach(card => {
                card.classList.remove('ring-2', 'ring-offset-2', 'ring-offset-[#0F172A]');
            });
            document.getElementById('check-personal').classList.add('hidden');
            document.getElementById('check-institution').classList.add('hidden');

            // Highlight selected card
            if (type === 'personal') {
                document.getElementById('card-personal').classList.add('ring-2', 'ring-emerald-400', 'ring-offset-2', 'ring-offset-[#0F172A]');
                document.getElementById('check-personal').classList.remove('hidden');
            } else {
                document.getElementById('card-institution').classList.add('ring-2', 'ring-blue-400', 'ring-offset-2', 'ring-offset-[#0F172A]');
                document.getElementById('check-institution').classList.remove('hidden');
            }

            // Enable continue button
            const btn = document.getElementById('btn-continue');
            btn.disabled = false;
            btn.classList.remove('bg-gray-600', 'text-white/50', 'cursor-not-allowed');
            btn.classList.add('bg-[#3B82F6]', 'hover:bg-[#2563EB]', 'text-white', 'shadow-lg', 'shadow-blue-500/25', 'cursor-pointer');
            btn.innerHTML = `
                <span class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                    Lanjutkan ke Pengisian Profil
                </span>
            `;
        }

        function goToStep2() {
            if (!selectedType) return;

            // Hide step 1
            document.getElementById('step1').classList.add('hidden');

            // Show appropriate form
            if (selectedType === 'personal') {
                document.getElementById('step2-personal').classList.remove('hidden');
            } else {
                document.getElementById('step2-institution').classList.remove('hidden');
            }
        }

        function goBackToStep1() {
            // Hide both forms
            document.getElementById('step2-personal').classList.add('hidden');
            document.getElementById('step2-institution').classList.add('hidden');

            // Show step 1
            document.getElementById('step1').classList.remove('hidden');
        }
    </script>
</x-layouts.app>