<x-layouts.lembaga>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">Import Data Sertifikat (Bulk)</h1>
                <p class="text-white/60">Upload file CSV atau Excel (.xlsx) untuk menerbitkan banyak sertifikat
                    sekaligus.</p>
            </div>
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" type="button"
                    class="px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg text-white text-sm transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Download Template
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false" x-transition
                    class="absolute right-0 mt-2 w-48 bg-gray-800 border border-gray-700 rounded-lg shadow-xl z-50">
                    <a href="{{ route('lembaga.sertifikat.bulk.template-csv') }}"
                        class="block px-4 py-3 text-sm text-white hover:bg-gray-700 rounded-t-lg flex items-center gap-2">
                        <span
                            class="w-6 h-6 bg-green-500/20 text-green-400 rounded flex items-center justify-center text-xs font-bold">CSV</span>
                        Template CSV
                    </a>
                    <a href="{{ route('lembaga.sertifikat.bulk.template-xlsx') }}"
                        class="block px-4 py-3 text-sm text-white hover:bg-gray-700 rounded-b-lg flex items-center gap-2">
                        <span
                            class="w-6 h-6 bg-blue-500/20 text-blue-400 rounded flex items-center justify-center text-xs font-bold">XLS</span>
                        Template Excel
                    </a>
                </div>
            </div>
        </div>

        @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session('error') }}' });
                });
            </script>
        @endif

        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}' });
                });
            </script>
        @endif

        <!-- Main Form -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Upload Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Enforce dark background for glass-card -->
                <div class="bg-gray-900 p-6 rounded-2xl border border-gray-700 shadow-xl">
                    <form action="{{ route('lembaga.sertifikat.bulk.store') }}" method="POST"
                        enctype="multipart/form-data" class="space-y-6" id="bulkForm" novalidate>
                        @csrf

                        <!-- Template Selection -->
                        <div>
                            <label class="block text-sm font-bold text-white mb-4">Pilih Desain Sertifikat</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($templates as $template)
                                    <label class="cursor-pointer group relative">
                                        <input type="radio" name="template_id" value="{{ $template->id }}"
                                            class="peer sr-only">

                                        <div
                                            class="aspect-video rounded-xl overflow-hidden relative ring-2 ring-transparent peer-checked:ring-blue-500 transition-all duration-300 group-hover:scale-[1.02]">
                                            <img src="{{ asset('storage/' . $template->file_path) }}"
                                                class="w-full h-full object-cover">

                                            <!-- Overlay & Checkmark -->
                                            <div
                                                class="absolute inset-0 bg-black/40 hidden peer-checked:flex items-center justify-center backdrop-blur-[1px]">
                                                <div
                                                    class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center shadow-lg animate-bounce">
                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>

                                        <p
                                            class="mt-2 text-xs text-center text-white/80 font-medium truncate group-hover:text-white transition">
                                            {{ $template->name }}
                                        </p>
                                    </label>
                                @endforeach
                            </div>
                            @error('template_id')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- File Upload -->
                        <div>
                            <label class="block text-sm font-bold text-white mb-2">Upload File Data</label>
                            <div class="relative group">
                                <div
                                    class="absolute -inset-0.5 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl opacity-20 group-hover:opacity-40 transition duration-200">
                                </div>
                                <input type="file" name="file" accept=".csv,.xlsx,.xls" required
                                    class="relative w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-500 transition cursor-pointer">
                            </div>
                            <p class="mt-2 text-xs text-white/50">
                                Format: CSV atau Excel (.xlsx). Kolom wajib: <code>recipient_name</code>,
                                <code>recipient_email</code>, <code>course_name</code>, <code>issue_date</code>.
                            </p>
                            @error('file')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Global Settings -->
                        <div class="border-t border-gray-700 pt-6">
                            <h3 class="text-sm font-bold text-white mb-6">Pengaturan Tambahan</h3>

                            <!-- Default Category & Description -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label class="block text-sm font-bold text-white mb-2">Kategori Sertifikat
                                        (Opsional)</label>
                                    <select name="default_category"
                                        class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Pilih kategori</option>
                                        <option value="Bootcamp">Bootcamp</option>
                                        <option value="Workshop">Workshop</option>
                                        <option value="Seminar">Seminar</option>
                                        <option value="Sertifikasi">Sertifikasi</option>
                                        <option value="Pelatihan">Pelatihan</option>
                                        <option value="Webinar">Webinar</option>
                                    </select>
                                    <p class="mt-1 text-xs text-gray-500">Akan digunakan jika kolom
                                        <code>category</code> di file kosong.
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-white mb-2">Deskripsi Sertifikat
                                        (Opsional)</label>
                                    <input type="text" name="default_description"
                                        placeholder="Contoh: Atas partisipasinya sebagai peserta..."
                                        class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder:text-gray-500">
                                    <p class="mt-1 text-xs text-gray-500">Akan digunakan jika kolom
                                        <code>description</code> di file kosong.
                                    </p>
                                </div>
                            </div>

                            <!-- Kirim Email -->
                            <div class="mb-6">
                                <label
                                    class="flex items-center justify-between cursor-pointer p-4 rounded-xl bg-gray-800 border border-gray-700 hover:bg-gray-700 transition group">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400 group-hover:scale-110 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="block text-white font-medium text-sm">Kirim email ke
                                                penerima</span>
                                            <span class="block text-gray-400 text-xs mt-0.5">Penerima akan menerima
                                                email berisi informasi sertifikat.</span>
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <input type="checkbox" name="send_email" value="1" class="peer sr-only" checked>
                                        <div
                                            class="w-11 h-6 bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <!-- Advanced Verification Options -->
                            <div class="space-y-4">
                                <!-- Blockchain -->
                                <div
                                    class="p-5 rounded-2xl bg-gray-800 border border-gray-700 hover:border-purple-500/30 transition duration-300">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                            </svg>
                                            <span class="text-white font-bold text-base">Blockchain Verification</span>
                                            <span
                                                class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-500/20 text-purple-300">GRATIS</span>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="blockchain_enabled" value="1"
                                                class="peer sr-only">
                                            <div
                                                class="w-11 h-6 bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600">
                                            </div>
                                        </label>
                                    </div>
                                    <p class="text-gray-300 text-sm mb-3">
                                        Upload ke Blockchain (Polygon)
                                    </p>
                                    <p class="text-gray-500 text-xs leading-relaxed">
                                        Simpan hash sertifikat ke jaringan Polygon untuk verifikasi tambahan yang tidak
                                        dapat dipalsukan.
                                        <br>
                                        <span class="flex items-center gap-4 mt-2 text-emerald-400">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Immutable
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Terverifikasi
                                            </span>
                                        </span>
                                    </p>
                                </div>

                                <!-- IPFS -->
                                <div
                                    class="p-5 rounded-2xl bg-gray-800 border border-gray-700 hover:border-cyan-500/30 transition duration-300">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                            <span class="text-white font-bold text-base">IPFS Storage</span>
                                            <span
                                                class="px-2 py-0.5 rounded text-[10px] font-bold bg-cyan-500/20 text-cyan-300">Web3</span>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="ipfs_enabled" value="1" class="peer sr-only">
                                            <div
                                                class="w-11 h-6 bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-cyan-600">
                                            </div>
                                        </label>
                                    </div>
                                    <p class="text-gray-300 text-sm mb-3">
                                        Upload ke IPFS (Storacha)
                                    </p>
                                    <p class="text-gray-500 text-xs leading-relaxed">
                                        Simpan metadata sertifikat ke jaringan IPFS + Filecoin untuk penyimpanan
                                        desentralisasi permanen.
                                        <br>
                                        <span class="flex items-center gap-4 mt-2 text-emerald-400">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Desentralisasi
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Permanen
                                            </span>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit"
                                class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl text-white font-bold hover:shadow-lg hover:scale-[1.02] transition-all duration-200">
                                Proses Bulk Upload
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Instructions -->
            <div class="space-y-6">
                <!-- Guide Card -->
                <div class="bg-gray-900 p-6 rounded-2xl border border-gray-700 shadow-xl">
                    <h3 class="font-bold text-blue-200 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Panduan Format File
                    </h3>
                    <ul class="space-y-3 text-sm text-gray-300">
                        <li class="flex gap-2">
                            <span class="font-bold text-blue-400">•</span>
                            <span>Gunakan format <strong>CSV</strong> atau <strong>Excel (.xlsx)</strong>.</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="font-bold text-blue-400">•</span>
                            <span>Baris pertama wajib berisi header kolom.</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="font-bold text-blue-400">•</span>
                            <span>Kolom Wajib: <br><code
                                    class="text-blue-300">recipient_name, recipient_email, course_name, issue_date</code>.</span>
                        </li>
                    </ul>

                    <div class="mt-4 bg-black/40 rounded-lg border border-gray-700 p-3 overflow-x-auto">
                        <table class="text-xs text-left w-full">
                            <thead>
                                <tr class="text-blue-300 border-b border-gray-700">
                                    <th class="p-2">recipient_name</th>
                                    <th class="p-2">recipient_email</th>
                                    <th class="p-2">course_name</th>
                                    <th class="p-2">category</th>
                                    <th class="p-2">description</th>
                                    <th class="p-2">issue_date</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-400">
                                <tr>
                                    <td class="p-2">John Doe</td>
                                    <td class="p-2">john@mail.com</td>
                                    <td class="p-2">Web Dev</td>
                                    <td class="p-2">Seminar</td>
                                    <td class="p-2">Atas partisipasinya...</td>
                                    <td class="p-2">2024-12-01</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Function to count rows in CSV file
        function countCSVRows(file) {
            return new Promise((resolve) => {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const text = e.target.result;
                    const lines = text.split('\n').filter(line => line.trim() !== '');
                    // Subtract 1 for header row
                    resolve(Math.max(0, lines.length - 1));
                };
                reader.onerror = () => resolve(0);
                reader.readAsText(file);
            });
        }

        document.getElementById('bulkForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const form = this;

            // Validate required fields
            const templateId = form.querySelector('input[name="template_id"]:checked');
            const fileInput = form.querySelector('input[name="file"]');

            // Check template selection
            if (!templateId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Template Belum Dipilih',
                    text: 'Silakan pilih desain sertifikat terlebih dahulu.',
                    confirmButtonColor: '#3B82F6'
                });
                return;
            }

            // Check file upload
            if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'File Belum Dipilih',
                    text: 'Silakan upload file CSV atau Excel (.xlsx) terlebih dahulu.',
                    confirmButtonColor: '#3B82F6'
                });
                return;
            }

            // Check file extension
            const file = fileInput.files[0];
            const fileName = file.name.toLowerCase();
            const isCSV = fileName.endsWith('.csv');
            const isExcel = fileName.endsWith('.xlsx') || fileName.endsWith('.xls');

            if (!isCSV && !isExcel) {
                Swal.fire({
                    icon: 'error',
                    title: 'Format File Tidak Valid',
                    text: 'Hanya file CSV dan Excel (.xlsx) yang didukung.',
                    confirmButtonColor: '#3B82F6'
                });
                return;
            }

            // Show initial loading while counting rows
            Swal.fire({
                title: 'Membaca File...',
                text: 'Menghitung jumlah data...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            // Count rows (only works for CSV, estimate for Excel)
            let rowCount = 0;
            if (isCSV) {
                rowCount = await countCSVRows(file);
            } else {
                // Estimate for Excel based on file size (rough estimate: ~100 bytes per row)
                rowCount = Math.max(1, Math.floor(file.size / 100));
            }

            // Check blockchain/IPFS enabled
            const blockchainEnabled = form.querySelector('input[name="blockchain_enabled"]')?.checked;
            const ipfsEnabled = form.querySelector('input[name="ipfs_enabled"]')?.checked;

            // Calculate estimated time: 3 sec per cert, +5 sec if blockchain, +3 sec if IPFS
            const timePerCert = 3 + (blockchainEnabled ? 5 : 0) + (ipfsEnabled ? 3 : 0);
            const estimatedTime = Math.max(10, rowCount * timePerCert);

            // Show SweetAlert with loading progress
            Swal.fire({
                title: 'Menerbitkan Sertifikat...',
                html: `
                    <div class="text-left mb-4">
                        <div class="flex items-center justify-between mb-3 p-3 bg-blue-50 rounded-lg">
                            <span class="text-gray-700 font-medium">Total Data:</span>
                            <span class="text-blue-600 font-bold text-lg">${rowCount} Sertifikat</span>
                        </div>
                        ${blockchainEnabled ? '<p class="text-purple-600 text-sm mb-1 flex items-center gap-2"><span class="w-2 h-2 bg-purple-500 rounded-full animate-pulse"></span><strong>Blockchain:</strong> Menyimpan ke Polygon Network</p>' : ''}
                        ${ipfsEnabled ? '<p class="text-cyan-600 text-sm mb-1 flex items-center gap-2"><span class="w-2 h-2 bg-cyan-500 rounded-full animate-pulse"></span><strong>IPFS:</strong> Menyimpan ke Storacha Network</p>' : ''}
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4 mb-2 overflow-hidden">
                        <div id="swal-progress" class="bg-gradient-to-r from-blue-500 to-indigo-600 h-4 rounded-full transition-all duration-500 relative" style="width: 0%">
                            <span id="swal-progress-text" class="absolute inset-0 flex items-center justify-center text-white text-xs font-bold">0%</span>
                        </div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 mb-2">
                        <span id="swal-current">0 / ${rowCount}</span>
                        <span id="swal-status">Membaca file...</span>
                    </div>
                    <p class="text-xs text-gray-400">Estimasi: <span id="swal-countdown">${estimatedTime}</span> detik</p>
                `,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();

                    // Progress animation
                    let currentCert = 0;
                    let countdown = estimatedTime;
                    const progressBar = document.getElementById('swal-progress');
                    const progressText = document.getElementById('swal-progress-text');
                    const statusText = document.getElementById('swal-status');
                    const currentText = document.getElementById('swal-current');
                    const countdownText = document.getElementById('swal-countdown');
                    const totalCerts = rowCount;

                    const interval = setInterval(() => {
                        // Update current certificate count
                        if (currentCert < totalCerts) {
                            currentCert++;
                        }

                        // Calculate progress
                        const progress = Math.min(95, (currentCert / totalCerts) * 100);

                        // Update UI
                        if (progressBar) progressBar.style.width = progress + '%';
                        if (progressText) progressText.textContent = Math.round(progress) + '%';
                        if (currentText) currentText.textContent = currentCert + ' / ' + totalCerts;

                        // Update countdown
                        countdown--;
                        if (countdownText && countdown >= 0) countdownText.textContent = countdown;

                        // Update status based on progress
                        if (statusText) {
                            if (progress < 20) {
                                statusText.textContent = 'Membaca file...';
                            } else if (progress < 40) {
                                statusText.textContent = 'Validasi data...';
                            } else if (progress < 60) {
                                statusText.textContent = 'Membuat sertifikat...';
                            } else if (progress < 80) {
                                statusText.textContent = blockchainEnabled ? 'Menyimpan ke blockchain...' : 'Menyimpan ke database...';
                            } else {
                                statusText.textContent = ipfsEnabled ? 'Mengupload ke IPFS...' : 'Menyelesaikan proses...';
                            }
                        }
                    }, (estimatedTime / totalCerts) * 1000);
                }
            });

            form.submit();
        });
    </script>
</x-layouts.lembaga>