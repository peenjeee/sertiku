<x-layouts.lembaga>
    <div class="space-y-6">
        <!-- Header with Quota Display -->
        <div class="info-box rounded-2xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-white text-2xl font-bold mb-2">Import Data Sertifikat (Bulk)</h1>
                    <p class="text-white/70 text-base">Upload file CSV atau Excel untuk menerbitkan banyak sertifikat sekaligus</p>
                </div>
                <!-- Usage Badge -->
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-white/60 text-xs">Sisa Kuota Bulan Ini</p>
                        <p class="text-white font-bold">{{ $remainingCertificates }} / {{ $certificateLimit }}</p>
                    </div>
                    <div
                        class="w-12 h-12 rounded-full flex items-center justify-center {{ $remainingCertificates <= 10 ? 'bg-red-500/20' : 'bg-green-500/20' }}">
                        <span
                            class="text-lg font-bold {{ $remainingCertificates <= 10 ? 'text-red-400' : 'text-green-400' }}">{{ $remainingCertificates }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Download Template Button -->
        <div class="flex justify-end">
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

                            <!-- Advanced Verification Options -->
                            <div class="space-y-4">
                                <!-- Blockchain -->
                                <div
                                    class="p-5 rounded-2xl bg-gray-800 border {{ !$canUseBlockchain ? 'border-red-500/30 opacity-75' : 'border-gray-700 hover:border-purple-500/30' }} transition duration-300">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                            </svg>
                                            <span class="text-white font-bold text-base">Blockchain Verification</span>
                                            <span
                                                class="px-2 py-0.5 rounded text-[10px] font-bold {{ $canUseBlockchain ? 'bg-purple-500/20 text-purple-300' : 'bg-red-500/20 text-red-300' }}">
                                                {{ $remainingBlockchain }}/{{ $blockchainLimit }}
                                            </span>
                                        </div>
                                        <label class="relative inline-flex items-center {{ $canUseBlockchain ? 'cursor-pointer' : 'cursor-not-allowed' }}">
                                            <input type="checkbox" name="blockchain_enabled" value="1"
                                                class="peer sr-only" {{ !$canUseBlockchain ? 'disabled' : '' }}>
                                            <div
                                                class="w-11 h-6 bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600">
                                            </div>
                                        </label>
                                    </div>
                                    
                                    @if(!$canUseBlockchain)
                                        <div class="mb-3 p-2 bg-red-900/30 border border-red-500/30 rounded text-red-300 text-xs">
                                            Kuota Blockchain habis. <a href="{{ url('/#harga') }}" class="underline hover:text-white">Upgrade</a>
                                        </div>
                                    @endif

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
                                    class="p-5 rounded-2xl bg-gray-800 border {{ !$canUseIpfs ? 'border-red-500/30 opacity-75' : 'border-gray-700 hover:border-cyan-500/30' }} transition duration-300">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                            <span class="text-white font-bold text-base">IPFS Storage</span>
                                            <span
                                                class="px-2 py-0.5 rounded text-[10px] font-bold {{ $canUseIpfs ? 'bg-cyan-500/20 text-cyan-300' : 'bg-red-500/20 text-red-300' }}">
                                                {{ $remainingIpfs }}/{{ $ipfsLimit }}
                                            </span>
                                        </div>
                                        <label class="relative inline-flex items-center {{ $canUseIpfs ? 'cursor-pointer' : 'cursor-not-allowed' }}">
                                            <input type="checkbox" name="ipfs_enabled" value="1" class="peer sr-only" {{ !$canUseIpfs ? 'disabled' : '' }}>
                                            <div
                                                class="w-11 h-6 bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-cyan-600">
                                            </div>
                                        </label>
                                    </div>
                                    
                                    @if(!$canUseIpfs)
                                        <div class="mb-3 p-2 bg-red-900/30 border border-red-500/30 rounded text-red-300 text-xs">
                                            Kuota IPFS habis. <a href="{{ url('/#harga') }}" class="underline hover:text-white">Upgrade</a>
                                        </div>
                                    @endif

                                    <p class="text-gray-300 text-sm mb-3">
                                        Upload ke IPFS
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
    <!-- SheetJS for Excel parsing -->
    <script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>
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

        // Function to count rows in Excel file using SheetJS
        function countExcelRows(file) {
            return new Promise((resolve) => {
                const reader = new FileReader();
                reader.onload = function (e) {
                    try {
                        const data = new Uint8Array(e.target.result);
                        const workbook = XLSX.read(data, { type: 'array' });
                        
                        // Get first sheet
                        const firstSheetName = workbook.SheetNames[0];
                        const worksheet = workbook.Sheets[firstSheetName];
                        
                        // Convert to JSON to count rows
                        const jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
                        
                        // Filter out empty rows and subtract 1 for header
                        const nonEmptyRows = jsonData.filter(row => row.some(cell => cell !== null && cell !== undefined && cell !== ''));
                        resolve(Math.max(0, nonEmptyRows.length - 1));
                    } catch (error) {
                        console.error('Excel parsing error:', error);
                        resolve(null); // Fallback to server estimation
                    }
                };
                reader.onerror = () => resolve(null);
                reader.readAsArrayBuffer(file);
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

            // Count rows (supports both CSV and Excel now)
            let rowCount = null;
            if (isCSV) {
                rowCount = await countCSVRows(file);
            } else if (isExcel) {
                rowCount = await countExcelRows(file);
            }

            // Check blockchain/IPFS/Email enabled
            const blockchainEnabled = form.querySelector('input[name="blockchain_enabled"]')?.checked;
            const ipfsEnabled = form.querySelector('input[name="ipfs_enabled"]')?.checked;
            const sendEmailEnabled = form.querySelector('input[name="send_email"]')?.checked;

            // QUOTA PRE-CHECK (Only if rowCount is known)
            if (rowCount !== null) {
                const remainingCertificates = {{ $remainingCertificates }};
                const remainingBlockchain = {{ $remainingBlockchain }};
                const remainingIpfs = {{ $remainingIpfs }};

                // 1. Certificate Quota Check (Always required)
                if (rowCount > remainingCertificates) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kuota Sertifikat Tidak Cukup',
                        html: `File Anda berisi <b>${rowCount}</b> data, namun sisa kuota Sertifikat Anda hanya <b>${remainingCertificates}</b>.<br><br>Silakan upgrade paket atau kurangi data.`,
                        confirmButtonColor: '#3B82F6'
                    });
                    return;
                }

                // 2. Blockchain Quota Check (If enabled)
                if (blockchainEnabled && rowCount > remainingBlockchain) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kuota Blockchain Tidak Cukup',
                        html: `File Anda berisi <b>${rowCount}</b> data, namun sisa kuota Blockchain Anda hanya <b>${remainingBlockchain}</b>.<br><br>Silakan upgrade paket atau kurangi data.`,
                        confirmButtonColor: '#3B82F6'
                    });
                    return;
                }

                // 3. IPFS Quota Check (If enabled)
                if (ipfsEnabled && rowCount > remainingIpfs) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kuota IPFS Tidak Cukup',
                        html: `File Anda berisi <b>${rowCount}</b> data, namun sisa kuota IPFS Anda hanya <b>${remainingIpfs}</b>.<br><br>Silakan upgrade paket atau kurangi data.`,
                        confirmButtonColor: '#3B82F6'
                    });
                    return;
                }
            }

            // Calculate estimated time
            // If unknown count, assume default 20s for UX
            const timePerCert = 3 + (blockchainEnabled ? 5 : 0) + (ipfsEnabled ? 3 : 0) + (sendEmailEnabled ? 2 : 0);
            const totalEstimated = rowCount !== null ? Math.max(10, rowCount * timePerCert) : 20;

            let rowCountDisplay = rowCount !== null ? `${rowCount} Sertifikat` : '<span class="italic text-gray-500">Estimasi Server (Excel)</span>';

            Swal.fire({
                title: 'Menerbitkan Sertifikat...',
                html: `
                    <div class="text-left mb-4 px-4">
                        <div class="flex justify-between items-center bg-blue-50 p-3 rounded-lg border border-blue-100 mb-3">
                            <span class="text-blue-800 font-bold">Total Data:</span>
                            <span class="text-blue-600 font-mono text-lg">${rowCountDisplay}</span>
                        </div>
                        <div class="space-y-1 mb-2 text-sm">
                            ${sendEmailEnabled ? '<div class="flex items-center gap-2 text-yellow-600 font-medium"><div class="w-2 h-2 rounded-full bg-yellow-500"></div> Email: Mengirim ke penerima</div>' : ''}
                            ${blockchainEnabled ? '<div class="flex items-center gap-2 text-purple-600 font-medium"><div class="w-2 h-2 rounded-full bg-purple-500"></div> Blockchain: Menyimpan ke Polygon Network</div>' : ''}
                            ${ipfsEnabled ? '<div class="flex items-center gap-2 text-cyan-600 font-medium"><div class="w-2 h-2 rounded-full bg-cyan-500"></div> IPFS: Menyimpan ke Pinata Network</div>' : ''}
                        </div>
                    </div>
                    
                    <div class="w-full bg-gray-200 rounded-full h-4 mb-2 overflow-hidden relative">
                        <div id="swal-progress" class="bg-gradient-to-r from-blue-500 to-indigo-600 h-4 rounded-full transition-all duration-300 relative" style="width: 0%">
                            <div class="absolute top-0 left-0 w-full h-full bg-white/30 animate-[shimmer_2s_infinite]"></div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between text-xs px-1 mb-1">
                        <span id="swal-counter" class="text-gray-500">${rowCount !== null ? `0 / ${rowCount}` : 'Memproses...'}</span>
                        <span id="swal-status" class="text-blue-600 font-medium animate-pulse">Menyiapkan data...</span>
                    </div>
                    
                    <p class="text-xs text-gray-400 mt-2">Estimasi: <span id="swal-countdown">${totalEstimated}</span> detik</p>
                `,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                    
                    // Trigger Form Submit
                    form.submit();

                    // UI Simulation
                    const progressEl = document.getElementById('swal-progress');
                    const statusEl = document.getElementById('swal-status');
                    const counterEl = document.getElementById('swal-counter');
                    const countdownEl = document.getElementById('swal-countdown');
                    
                    let processed = 0;
                    let remainingTime = totalEstimated;
                    
                    // Countdown
                    const countdownInterval = setInterval(() => {
                        remainingTime--;
                        if (countdownEl) countdownEl.textContent = Math.max(0, remainingTime);
                        if (remainingTime <= 0) clearInterval(countdownInterval);
                    }, 1000);

                    // Progress Simulation
                    // If rowCount unknown, animate to 90% slowly
                    const targetCount = rowCount || 100;
                    const simIntervalTime = rowCount !== null ? ((timePerCert * 1000) * 0.8) : 200; 
                    
                    const progressInterval = setInterval(() => {
                        if (processed < targetCount) {
                            processed++;
                            
                            let percent;
                            if (rowCount !== null) {
                                percent = Math.min(Math.round((processed / rowCount) * 100), 99);
                                if (counterEl) counterEl.textContent = `${processed} / ${rowCount}`;
                            } else {
                                // Fake progress for unknown count (logarithmic slowdown)
                                percent = Math.min(processed, 95); 
                            }
                            
                            if (progressEl) progressEl.style.width = `${percent}%`;
                            
                            // Randomize status
                            const statuses = ['Memproses...', 'Generate PDF...', 'Hashing...'];
                            if (blockchainEnabled) statuses.push('Blockchain Sync...');
                            if (ipfsEnabled) statuses.push('IPFS Upload...');
                            
                            if (statusEl) statusEl.textContent = statuses[Math.floor(Math.random() * statuses.length)];
                            
                        } else {
                            // If unknown count, we just stay at 95% until page reloads
                            if (rowCount !== null) {
                                if (statusEl) statusEl.textContent = 'Menyelesaikan...';
                                if (progressEl) progressEl.style.width = '100%';
                                clearInterval(progressInterval);
                            }
                        }
                    }, simIntervalTime); 
                }
            });

            form.submit();
        });
    </script>
</x-layouts.lembaga>