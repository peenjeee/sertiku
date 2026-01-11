<x-layouts.lembaga title="API Tokens - SertiKu">
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 md:mb-8">
                <div>
                    <h1 class="text-xl md:text-2xl font-bold text-white">API Tokens</h1>
                    <p class="text-sm md:text-base text-[#8EC5FF]/70">Kelola token untuk mengakses SertiKu API</p>
                </div>
                <a href="{{ route('api.docs') }}"
                    class="text-[#3B82F6] hover:underline text-sm flex items-center gap-1 self-start sm:self-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    Lihat Dokumentasi API
                </a>
            </div>

            {{-- New Token Alert --}}
            @if(session('newToken'))
                <div class="bg-[#10B981]/20 border border-[#10B981]/50 rounded-xl p-4 mb-6">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-[#10B981] flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="text-[#10B981] font-medium mb-2">API Token berhasil dibuat!</p>
                            <p class="text-[#8EC5FF]/70 text-sm mb-3">Salin token ini sekarang. Token tidak akan ditampilkan
                                lagi.</p>
                            <div class="flex items-center gap-2 bg-[#050C1F] rounded-lg p-3">
                                <code class="text-white font-mono text-sm flex-1 break-all"
                                    id="newToken">{{ session('newToken') }}</code>
                                <button onclick="copyToken()" class="text-[#3B82F6] hover:text-white transition p-2"
                                    title="Salin">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Alerts --}}
            @if(session('success') && !session('newToken'))
                <div class="bg-[#10B981]/20 border border-[#10B981]/50 rounded-xl p-4 mb-6">
                    <p class="text-[#10B981]">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-500/20 border border-red-500/50 rounded-xl p-4 mb-6">
                    <p class="text-red-400">{{ session('error') }}</p>
                </div>
            @endif

            {{-- Create Token Form --}}
            <div class="bg-[#0A1929]/80 border border-[#1E3A5F] rounded-xl p-4 md:p-6 mb-6 md:mb-8">
                <h2 class="text-base md:text-lg font-semibold text-white mb-4">Buat Token Baru</h2>
                <form action="{{ route('lembaga.api-tokens.store') }}" method="POST"
                    class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                    @csrf
                    <input type="text" name="name" placeholder="Nama token (contoh: Production App)" required
                        class="flex-1 bg-[#050C1F] border border-[#1E3A5F] rounded-lg px-3 md:px-4 py-2.5 text-sm md:text-base text-white placeholder-[#8EC5FF]/50 focus:border-[#3B82F6] focus:outline-none">
                    <button type="submit"
                        class="px-4 md:px-6 py-2.5 bg-[#3B82F6] text-white font-medium rounded-lg hover:bg-[#2563EB] transition whitespace-nowrap text-sm md:text-base">
                        Buat Token
                    </button>
                </form>
                <p class="text-[#8EC5FF]/50 text-xs md:text-sm mt-3">Maksimal 5 token per akun.</p>
            </div>

            {{-- Tokens List --}}
            <div class="bg-[#0A1929]/80 border border-[#1E3A5F] rounded-xl overflow-hidden">
                <div class="px-4 md:px-6 py-3 md:py-4 border-b border-[#1E3A5F]">
                    <h2 class="text-base md:text-lg font-semibold text-white">Token Aktif</h2>
                </div>

                @forelse($tokens as $token)
                    <div
                        class="px-4 md:px-6 py-3 md:py-4 border-b border-[#1E3A5F] flex flex-col sm:flex-row sm:items-center justify-between gap-2 sm:gap-4">
                        <div class="flex-1 min-w-0">
                            <p class="text-white font-medium text-sm md:text-base truncate">{{ $token->name }}</p>
                            <p class="text-[#8EC5FF]/50 text-xs md:text-sm">
                                Dibuat {{ $token->created_at->diffForHumans() }}
                                @if($token->last_used_at)
                                    • Terakhir digunakan {{ $token->last_used_at->diffForHumans() }}
                                @endif
                            </p>
                        </div>
                        <form action="{{ route('lembaga.api-tokens.destroy', $token->id) }}" method="POST"
                            onsubmit="return confirmAction(event, 'Yakin ingin menghapus token ini?')"
                            class="self-end sm:self-auto">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300 transition p-2"
                                title="Hapus Token">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <svg class="w-12 h-12 text-[#8EC5FF]/30 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                        <p class="text-[#8EC5FF]/50">Belum ada token. Buat token pertama Anda untuk mulai menggunakan API.
                        </p>
                    </div>
                @endforelse
            </div>

            {{-- Usage Guide --}}
            <div class="mt-6 md:mt-8 bg-[#0A1929]/80 border border-[#1E3A5F] rounded-xl p-4 md:p-6">
                <h3 class="text-base md:text-lg font-semibold text-white mb-4">Cara Menggunakan Token</h3>
                <p class="text-sm md:text-base text-[#BEDBFF] mb-4">Tambahkan header <code
                        class="text-[#10B981]">Authorization</code> pada
                    setiap request:</p>
                <div class="bg-[#050C1F] rounded-lg p-3 md:p-4 font-mono text-xs md:text-sm overflow-x-auto">
                    <pre class="text-[#10B981]">Authorization: Bearer YOUR_API_TOKEN</pre>
                </div>
                <p class="text-[#8EC5FF]/50 text-xs md:text-sm mt-4">Contoh menggunakan cURL:</p>
                <div class="bg-[#050C1F] rounded-lg p-3 md:p-4 font-mono text-xs md:text-sm mt-2 overflow-x-auto">
                    <pre class="text-[#10B981]">curl -X GET "{{ url('/api/v1/certificates') }}" \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Accept: application/json"</pre>
                </div>
            </div>

            {{-- Test API Token --}}
            <div class="mt-6 md:mt-8 bg-[rgba(59,130,246,0.1)] border border-[#3B82F6]/30 rounded-xl p-4 md:p-6">
                <h3 class="text-base md:text-lg font-semibold text-white mb-4 flex items-center gap-2"><svg
                        class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg> Test API Token</h3>
                <p class="text-sm md:text-base text-[#BEDBFF] mb-4">Uji token Anda dengan mengirim request ke API.</p>

                <div class="space-y-4">
                    {{-- Token Input --}}
                    <div>
                        <label class="text-[#8EC5FF] text-sm mb-2 block">API Token</label>
                        <input type="text" id="testToken" placeholder="Paste token Anda di sini..."
                            class="w-full bg-[#050C1F] border border-[#1E3A5F] rounded-lg px-4 py-2.5 text-white placeholder-[#8EC5FF]/50 focus:border-[#3B82F6] focus:outline-none font-mono text-sm">
                    </div>

                    {{-- Endpoint Selector --}}
                    <div>
                        <label class="text-[#8EC5FF] text-sm mb-2 block">Endpoint</label>
                        <select id="testEndpoint" onchange="updateTestUI()"
                            class="w-full bg-[#050C1F] border border-[#1E3A5F] rounded-lg px-4 py-2.5 text-white focus:border-[#3B82F6] focus:outline-none text-sm">
                            <option value="verify">GET /verify/{hash|code} - Verifikasi (Public)</option>
                            <option value="stats">GET /stats - Statistik (Public)</option>
                            <option value="certificates">GET /certificates - List (Auth)</option>
                            <option value="detail">GET /certificates/{id|hash|code} - Detail (Auth)</option>
                            <option value="create">POST /certificates - Buat Baru (Auth)</option>
                            <option value="revoke">PUT /certificates/{id|hash|code}/revoke (Auth)</option>
                            <option value="reactivate">PUT /certificates/{id|hash|code}/reactivate (Auth)</option>
                        </select>
                    </div>

                    {{-- Additional Input for verify/revoke --}}
                    <div id="hashInputContainer" class="hidden">
                        <label class="text-[#8EC5FF] text-sm mb-2 block">Hash atau ID Sertifikat</label>
                        <input type="text" id="hashInput" placeholder="Masukkan hash/nomor sertifikat atau ID..."
                            class="w-full bg-[#050C1F] border border-[#1E3A5F] rounded-lg px-4 py-2.5 text-white placeholder-[#8EC5FF]/50 focus:border-[#3B82F6] focus:outline-none font-mono text-sm">
                    </div>

                    {{-- Create Certificate Form --}}
                    <div id="createFormContainer"
                        class="hidden space-y-4 p-4 bg-[#050C1F]/50 border border-[#1E3A5F] rounded-lg">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-yellow-400"><svg class="w-4 h-4 inline" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg></span>
                            <span class="text-yellow-400 text-sm">Mode Test: Sertifikat TIDAK akan disimpan ke
                                database</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Nama Kursus/Program --}}
                            <div>
                                <label class="text-[#8EC5FF] text-sm mb-1 block">Nama Kursus/Program <span
                                        class="text-red-400">*</span></label>
                                <input type="text" id="testCourseName" placeholder="Web Development Bootcamp 2026"
                                    class="w-full bg-[#050C1F] border border-[#1E3A5F] rounded-lg px-3 py-2 text-white placeholder-[#8EC5FF]/50 focus:border-[#3B82F6] focus:outline-none text-sm">
                            </div>

                            {{-- Kategori Event --}}
                            <div>
                                <label class="text-[#8EC5FF] text-sm mb-1 block">Kategori Event</label>
                                <select id="testCategory"
                                    class="w-full bg-[#050C1F] border border-[#1E3A5F] rounded-lg px-3 py-2 text-white focus:border-[#3B82F6] focus:outline-none text-sm">
                                    <option value="">Pilih kategori</option>
                                    <option value="Pelatihan">Pelatihan</option>
                                    <option value="Webinar">Webinar</option>
                                    <option value="Workshop">Workshop</option>
                                    <option value="Sertifikasi">Sertifikasi</option>
                                    <option value="Bootcamp">Bootcamp</option>
                                    <option value="Seminar">Seminar</option>
                                    <option value="Kompetisi">Kompetisi</option>
                                </select>
                            </div>
                        </div>

                        {{-- Template Sertifikat --}}
                        <div>
                            <label class="text-[#8EC5FF] text-sm mb-1 block">Template Sertifikat <span
                                    class="text-red-400">*</span></label>
                            <select id="testTemplate"
                                class="w-full bg-[#050C1F] border border-[#1E3A5F] rounded-lg px-3 py-2 text-white focus:border-[#3B82F6] focus:outline-none text-sm">
                                <option value="">Pilih template</option>
                                @foreach(auth()->user()->templates ?? [] as $template)
                                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                                @endforeach
                                <option value="default">Template Default</option>
                            </select>
                        </div>

                        {{-- Deskripsi --}}
                        <div>
                            <label class="text-[#8EC5FF] text-sm mb-1 block">Deskripsi (Opsional)</label>
                            <textarea id="testDescription" rows="2"
                                placeholder="Deskripsi singkat tentang program/kursus"
                                class="w-full bg-[#050C1F] border border-[#1E3A5F] rounded-lg px-3 py-2 text-white placeholder-[#8EC5FF]/50 focus:border-[#3B82F6] focus:outline-none text-sm resize-none"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Tanggal Penerbitan --}}
                            <div>
                                <label class="text-[#8EC5FF] text-sm mb-1 block">Tanggal Penerbitan <span
                                        class="text-red-400">*</span></label>
                                <input type="date" id="testIssueDate"
                                    class="w-full bg-[#050C1F] border border-[#1E3A5F] rounded-lg px-3 py-2 text-white focus:border-[#3B82F6] focus:outline-none text-sm">
                            </div>

                            {{-- Tanggal Kadaluarsa --}}
                            <div>
                                <label class="text-[#8EC5FF] text-sm mb-1 block">Tanggal Kadaluarsa (Opsional)</label>
                                <input type="date" id="testExpireDate"
                                    class="w-full bg-[#050C1F] border border-[#1E3A5F] rounded-lg px-3 py-2 text-white focus:border-[#3B82F6] focus:outline-none text-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Nama Penerima --}}
                            <div>
                                <label class="text-[#8EC5FF] text-sm mb-1 block">Nama Penerima <span
                                        class="text-red-400">*</span></label>
                                <input type="text" id="testRecipientName" placeholder="John Doe"
                                    class="w-full bg-[#050C1F] border border-[#1E3A5F] rounded-lg px-3 py-2 text-white placeholder-[#8EC5FF]/50 focus:border-[#3B82F6] focus:outline-none text-sm">
                            </div>

                            {{-- Email Penerima --}}
                            <div>
                                <label class="text-[#8EC5FF] text-sm mb-1 block">Email Penerima <span
                                        class="text-red-400">*</span></label>
                                <input type="email" id="testRecipientEmail" placeholder="john@example.com"
                                    class="w-full bg-[#050C1F] border border-[#1E3A5F] rounded-lg px-3 py-2 text-white placeholder-[#8EC5FF]/50 focus:border-[#3B82F6] focus:outline-none text-sm">
                            </div>
                        </div>

                        {{-- Toggle Options --}}
                        <div class="flex flex-wrap gap-4 pt-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="testSendEmail"
                                    class="w-4 h-4 rounded border-[#1E3A5F] bg-[#050C1F] text-[#3B82F6] focus:ring-[#3B82F6]">
                                <span class="text-[#8EC5FF] text-sm">Kirim email ke penerima</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="testBlockchain"
                                    class="w-4 h-4 rounded border-[#1E3A5F] bg-[#050C1F] text-[#3B82F6] focus:ring-[#3B82F6]">
                                <span class="text-[#8EC5FF] text-sm">Upload ke Blockchain</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="testIpfs"
                                    class="w-4 h-4 rounded border-[#1E3A5F] bg-[#050C1F] text-[#3B82F6] focus:ring-[#3B82F6]">
                                <span class="text-[#8EC5FF] text-sm">Upload ke IPFS</span>
                            </label>
                        </div>
                    </div>

                    {{-- Test Button --}}
                    <button onclick="testApi()"
                        class="w-full px-6 py-3 bg-[#3B82F6] text-white font-medium rounded-lg hover:bg-[#2563EB] transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span id="testButtonText">Test API</span>
                    </button>

                    {{-- Result --}}
                    <div id="testResultContainer" class="hidden">
                        <label class="text-[#8EC5FF] text-sm mb-2 block">Response</label>
                        <div class="relative">
                            <div id="testStatus" class="absolute top-2 right-2 px-2 py-0.5 rounded text-xs font-medium">
                            </div>
                            <pre id="testResult"
                                class="bg-[#050C1F] rounded-lg p-4 font-mono text-xs text-[#10B981] overflow-x-auto max-h-64 overflow-y-auto"></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function copyToken() {
                const token = document.getElementById('newToken').textContent;
                navigator.clipboard.writeText(token);

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Token Disalin!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            }

            function updateTestUI() {
                const endpoint = document.getElementById('testEndpoint').value;
                const hashContainer = document.getElementById('hashInputContainer');
                const createContainer = document.getElementById('createFormContainer');
                const hashInput = document.getElementById('hashInput');
                const testButton = document.getElementById('testButtonText');

                // Hide all containers first
                hashContainer.classList.add('hidden');
                createContainer.classList.add('hidden');

                if (endpoint === 'verify' || endpoint === 'revoke' || endpoint === 'reactivate' || endpoint === 'detail') {
                    hashContainer.classList.remove('hidden');
                    if (endpoint === 'verify') {
                        hashInput.placeholder = 'Masukkan hash atau nomor sertifikat...';
                    } else {
                        hashInput.placeholder = 'Masukkan ID, Hash, atau Nomor Sertifikat...';
                    }
                    testButton.textContent = 'Test API';
                } else if (endpoint === 'create') {
                    createContainer.classList.remove('hidden');
                    testButton.textContent = 'Simulasi API (Tidak Simpan)';
                    // Set default date to today
                    document.getElementById('testIssueDate').value = new Date().toISOString().split('T')[0];
                } else {
                    testButton.textContent = 'Test API';
                }
            }

            async function testApi() {
                const token = document.getElementById('testToken').value;
                const endpoint = document.getElementById('testEndpoint').value;
                const hashValue = document.getElementById('hashInput')?.value || '';
                const resultContainer = document.getElementById('testResultContainer');
                const resultEl = document.getElementById('testResult');
                const statusEl = document.getElementById('testStatus');

                // Show loading
                resultContainer.classList.remove('hidden');
                resultEl.textContent = 'Loading...';
                statusEl.textContent = '';
                statusEl.className = 'absolute top-2 right-2 px-2 py-0.5 rounded text-xs font-medium';

                // Check if token is required for authenticated endpoints
                const authRequiredEndpoints = ['certificates', 'create', 'revoke', 'reactivate', 'detail'];
                if (authRequiredEndpoints.includes(endpoint) && !token.trim()) {
                    resultEl.textContent = 'Error: API Token wajib diisi untuk endpoint yang memerlukan autentikasi.\n\nBuat token baru di form di atas, lalu paste token-nya di sini.';
                    statusEl.textContent = '✗ 401 Unauthorized';
                    statusEl.classList.add('bg-red-500/20', 'text-red-400');
                    return;
                }

                let url, method = 'GET', body = null;

                switch (endpoint) {
                    case 'verify':
                        if (!hashValue) {
                            resultEl.textContent = 'Error: Masukkan hash atau nomor sertifikat';
                            statusEl.textContent = '✗ Error';
                            statusEl.classList.add('bg-red-500/20', 'text-red-400');
                            return;
                        }
                        url = `{{ url('/api/v1/verify') }}/${hashValue}`;
                        break;
                    case 'stats':
                        url = '{{ url('/api/v1/stats') }}';
                        break;
                    case 'certificates':
                        url = '{{ url('/api/v1/certificates') }}';
                        break;
                    case 'detail':
                        if (!hashValue) {
                            resultEl.textContent = 'Error: Masukkan ID, Hash, atau Nomor Sertifikat';
                            statusEl.textContent = '✗ Error';
                            statusEl.classList.add('bg-red-500/20', 'text-red-400');
                            return;
                        }
                        url = `{{ url('/api/v1/certificates') }}/${hashValue}`;
                        break;
                    case 'create':
                        // Get form values
                        const courseName = document.getElementById('testCourseName').value;
                        const category = document.getElementById('testCategory').value;
                        const templateId = document.getElementById('testTemplate').value;
                        const description = document.getElementById('testDescription').value;
                        const issueDate = document.getElementById('testIssueDate').value;
                        const expireDate = document.getElementById('testExpireDate').value;
                        const recipientName = document.getElementById('testRecipientName').value;
                        const recipientEmail = document.getElementById('testRecipientEmail').value;
                        const sendEmail = document.getElementById('testSendEmail').checked;
                        const blockchain = document.getElementById('testBlockchain').checked;
                        const ipfs = document.getElementById('testIpfs').checked;

                        // Validate required fields
                        if (!courseName || !templateId || !issueDate || !recipientName || !recipientEmail) {
                            resultEl.textContent = 'Error: Semua field bertanda * wajib diisi (Kursus, Template, Tanggal, Nama, Email)';
                            statusEl.textContent = '✗ Validation Error';
                            statusEl.classList.add('bg-red-500/20', 'text-red-400');
                            return;
                        }

                        const requestBody = {
                            course_name: courseName,
                            template_id: templateId,
                            category: category || null,
                            description: description || null,
                            issue_date: issueDate,
                            expire_date: expireDate || null,
                            recipient_name: recipientName,
                            recipient_email: recipientEmail,
                            send_email: sendEmail,
                            blockchain_enabled: blockchain,
                            ipfs_enabled: ipfs
                        };

                        // Show simulated response
                        const simulatedResponse = {
                            success: true,
                            message: '[SIMULASI] Data TIDAK disimpan ke database',
                            test_mode: true,
                            request_body: requestBody,
                            simulated_result: {
                                id: Math.floor(Math.random() * 10000),
                                certificate_number: `SERT-${new Date().toISOString().slice(0, 7).replace('-', '')}-${Math.random().toString(36).substring(2, 7).toUpperCase()}`,
                                recipient_name: recipientName,
                                recipient_email: recipientEmail,
                                course_name: courseName,
                                category: category,
                                issue_date: issueDate,
                                expire_date: expireDate,
                                status: 'active',
                                blockchain_enabled: blockchain,
                                ipfs_enabled: ipfs,
                                email_sent: sendEmail,
                                created_at: new Date().toISOString()
                            }
                        };

                        statusEl.textContent = '✓ 200 OK (Simulasi)';
                        statusEl.classList.add('bg-yellow-500/20', 'text-yellow-400');
                        resultEl.textContent = JSON.stringify(simulatedResponse, null, 2);
                        return;

                    case 'revoke':
                        if (!hashValue) {
                            resultEl.textContent = 'Error: Masukkan ID atau nomor sertifikat';
                            statusEl.textContent = '✗ Error';
                            statusEl.classList.add('bg-red-500/20', 'text-red-400');
                            return;
                        }

                        // Simulate revoke (tidak benar-benar revoke)
                        const revokeResponse = {
                            success: true,
                            message: '[SIMULASI] Sertifikat TIDAK dicabut di database',
                            test_mode: true,
                            request: {
                                certificate_id: hashValue,
                                reason: 'Test revocation via API'
                            },
                            simulated_result: {
                                id: hashValue,
                                certificate_number: hashValue.includes('SERT') ? hashValue : `SERT-XXXXXX-${hashValue}`,
                                status: 'revoked',
                                revoked_at: new Date().toISOString(),
                                revocation_reason: 'Test revocation via API'
                            }
                        };

                        statusEl.textContent = '✓ 200 OK (Simulasi)';
                        statusEl.classList.add('bg-yellow-500/20', 'text-yellow-400');
                        resultEl.textContent = JSON.stringify(revokeResponse, null, 2);
                        return;

                    case 'reactivate':
                        if (!hashValue) {
                            resultEl.textContent = 'Error: Masukkan ID atau nomor sertifikat';
                            statusEl.textContent = '✗ Error';
                            statusEl.classList.add('bg-red-500/20', 'text-red-400');
                            return;
                        }

                        // Simulate reactivate
                        const reactivateResponse = {
                            success: true,
                            message: '[SIMULASI] Sertifikat TIDAK diaktifkan kembali di database',
                            test_mode: true,
                            request: {
                                certificate_id: hashValue
                            },
                            simulated_result: {
                                id: hashValue,
                                certificate_number: hashValue.includes('SERT') ? hashValue : `SERT-XXXXXX-${hashValue}`,
                                status: 'active',
                                reactivated_at: new Date().toISOString()
                            }
                        };

                        statusEl.textContent = '✓ 200 OK (Simulasi)';
                        statusEl.classList.add('bg-yellow-500/20', 'text-yellow-400');
                        resultEl.textContent = JSON.stringify(reactivateResponse, null, 2);
                        return;
                }

                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                };

                // Add auth header if token provided
                if (token.trim()) {
                    headers['Authorization'] = `Bearer ${token}`;
                }

                try {
                    const fetchOptions = { method, headers };
                    if (body) fetchOptions.body = body;

                    const response = await fetch(url, fetchOptions);
                    const data = await response.json();

                    // Show status
                    if (response.ok) {
                        statusEl.textContent = `✓ ${response.status} OK`;
                        statusEl.classList.add('bg-[#10B981]/20', 'text-[#10B981]');
                    } else {
                        statusEl.textContent = `✗ ${response.status} ${response.statusText}`;
                        statusEl.classList.add('bg-red-500/20', 'text-red-400');
                    }

                    resultEl.textContent = JSON.stringify(data, null, 2);
                } catch (error) {
                    statusEl.textContent = '✗ Error';
                    statusEl.classList.add('bg-red-500/20', 'text-red-400');
                    resultEl.textContent = 'Error: ' + error.message;
                }
            }

            // Initialize UI
            document.addEventListener('DOMContentLoaded', updateTestUI);
        </script>
    @endpush
</x-layouts.lembaga>