<x-layouts.app title="API Documentation - SertiKu"
    description="Dokumentasi API SertiKu untuk integrasi verifikasi sertifikat digital">
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            {{-- Header --}}
            <div class="text-center mb-12 scroll-animate">
                <h1 class="text-4xl font-bold text-white mb-4">API Documentation</h1>
                <p class="text-[#8EC5FF]/80 text-lg max-w-2xl mx-auto">
                    Integrasikan SertiKu ke aplikasi Anda untuk verifikasi sertifikat digital secara otomatis.
                </p>
            </div>

            {{-- Base URL --}}
            <div class="bg-[#0A1929]/80 border border-[#1E3A5F] rounded-xl p-6 mb-8 scroll-animate">
                <h2 class="text-xl font-semibold text-white mb-3">Base URL</h2>
                <div class="flex items-center gap-2 bg-[#050C1F] rounded-lg p-4">
                    <code class="text-[#10B981] font-mono text-lg flex-1" id="baseUrl">{{ url('/api/v1') }}</code>
                    <button onclick="copyToClipboard('baseUrl')" class="text-[#8EC5FF] hover:text-white transition p-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Quick Stats Widget --}}
            <div
                class="bg-gradient-to-r from-[#3B82F6]/20 to-[#8B5CF6]/20 border border-[#3B82F6]/30 rounded-xl p-6 mb-8 scroll-animate">
                <h3 class="text-lg font-semibold text-white mb-4">📊 Platform Statistics (Live)</h3>
                <div id="statsContainer" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-[#3B82F6]" id="statTotal">-</div>
                        <div class="text-xs text-[#8EC5FF]/70">Total Sertifikat</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-[#10B981]" id="statActive">-</div>
                        <div class="text-xs text-[#8EC5FF]/70">Aktif</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-[#8B5CF6]" id="statIssuers">-</div>
                        <div class="text-xs text-[#8EC5FF]/70">Penerbit</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-[#F59E0B]" id="statBlockchain">-</div>
                        <div class="text-xs text-[#8EC5FF]/70">On-Chain</div>
                    </div>
                </div>
            </div>

            {{-- Endpoints --}}
            <div class="space-y-6">
                {{-- Verify Endpoint --}}
                <div class="bg-[#0A1929]/80 border border-[#1E3A5F] rounded-xl overflow-hidden scroll-animate"
                    x-data="{ open: true }">
                    <button @click="open = !open" class="w-full flex items-center justify-between p-6 text-left">
                        <div class="flex items-center gap-4">
                            <span class="px-3 py-1 bg-[#10B981]/20 text-[#10B981] text-sm font-mono rounded">GET</span>
                            <code class="text-white font-mono">/verify/{hash}</code>
                            <span class="text-[#8EC5FF]/60 text-sm">Verifikasi Sertifikat</span>
                        </div>
                        <svg class="w-5 h-5 text-[#8EC5FF] transition-transform" :class="{ 'rotate-180': open }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="border-t border-[#1E3A5F] p-6 space-y-4">
                        <p class="text-[#BEDBFF]">Verifikasi keaslian sertifikat menggunakan hash atau nomor sertifikat.
                        </p>

                        {{-- Parameters --}}
                        <div>
                            <h4 class="text-white font-medium mb-2">Parameters</h4>
                            <table class="w-full text-sm">
                                <thead class="text-[#8EC5FF]/70">
                                    <tr>
                                        <th class="text-left py-2">Name</th>
                                        <th class="text-left">Type</th>
                                        <th class="text-left">Description</th>
                                    </tr>
                                </thead>
                                <tbody class="text-[#BEDBFF]">
                                    <tr>
                                        <td class="py-2"><code>hash</code></td>
                                        <td>string</td>
                                        <td>UUID hash atau nomor sertifikat</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Try it out --}}
                        <div class="bg-[#050C1F] rounded-lg p-4">
                            <h4 class="text-white font-medium mb-3">🧪 Try it out</h4>
                            <div class="flex gap-2">
                                <input type="text" id="verifyInput" placeholder="Masukkan hash atau nomor sertifikat"
                                    class="flex-1 bg-[#0A1929] border border-[#1E3A5F] rounded-lg px-4 py-2 text-white placeholder-[#8EC5FF]/50 focus:border-[#3B82F6] focus:outline-none">
                                <button onclick="tryVerify()"
                                    class="px-4 py-2 bg-[#3B82F6] text-white rounded-lg hover:bg-[#2563EB] transition">
                                    Test
                                </button>
                            </div>
                            <pre id="verifyResult" class="mt-4 text-xs text-[#8EC5FF] overflow-x-auto hidden"></pre>
                        </div>

                        {{-- Code Examples --}}
                        <div x-data="{ tab: 'curl' }">
                            <h4 class="text-white font-medium mb-3">Code Examples</h4>
                            <div class="flex gap-2 mb-3">
                                <button @click="tab = 'curl'"
                                    :class="tab === 'curl' ? 'bg-[#3B82F6] text-white' : 'bg-[#1E3A5F] text-[#8EC5FF]'"
                                    class="px-3 py-1 rounded text-sm transition">cURL</button>
                                <button @click="tab = 'php'"
                                    :class="tab === 'php' ? 'bg-[#3B82F6] text-white' : 'bg-[#1E3A5F] text-[#8EC5FF]'"
                                    class="px-3 py-1 rounded text-sm transition">PHP</button>
                                <button @click="tab = 'js'"
                                    :class="tab === 'js' ? 'bg-[#3B82F6] text-white' : 'bg-[#1E3A5F] text-[#8EC5FF]'"
                                    class="px-3 py-1 rounded text-sm transition">JavaScript</button>
                            </div>
                            <div class="bg-[#050C1F] rounded-lg p-4 font-mono text-sm overflow-x-auto">
                                <pre x-show="tab === 'curl'" class="text-[#10B981]">curl -X GET "{{ url('/api/v1/verify') }}/YOUR_HASH" \
  -H "Accept: application/json"</pre>
                                <pre x-show="tab === 'php'" class="text-[#10B981]">$response = Http::get('{{ url('/api/v1/verify') }}/YOUR_HASH');
$data = $response->json();</pre>
                                <pre x-show="tab === 'js'" class="text-[#10B981]">const response = await fetch('{{ url('/api/v1/verify') }}/YOUR_HASH');
const data = await response.json();</pre>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Stats Endpoint --}}
                <div class="bg-[#0A1929]/80 border border-[#1E3A5F] rounded-xl overflow-hidden scroll-animate"
                    x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex items-center justify-between p-6 text-left">
                        <div class="flex items-center gap-4">
                            <span class="px-3 py-1 bg-[#10B981]/20 text-[#10B981] text-sm font-mono rounded">GET</span>
                            <code class="text-white font-mono">/stats</code>
                            <span class="text-[#8EC5FF]/60 text-sm">Statistik Platform</span>
                        </div>
                        <svg class="w-5 h-5 text-[#8EC5FF] transition-transform" :class="{ 'rotate-180': open }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="border-t border-[#1E3A5F] p-6 space-y-4">
                        <p class="text-[#BEDBFF]">Dapatkan statistik platform SertiKu secara real-time.</p>

                        {{-- Response Example --}}
                        <div>
                            <h4 class="text-white font-medium mb-2">Response Example</h4>
                            <pre class="bg-[#050C1F] rounded-lg p-4 text-xs text-[#10B981] overflow-x-auto">{
  "success": true,
  "data": {
    "total_certificates": 67000,
    "active_certificates": 65000,
    "total_issuers": 67,
    "blockchain_verified": 1200
  }
}</pre>
                        </div>
                    </div>
                </div>

                {{-- GET Certificates Endpoint --}}
                <div class="bg-[#0A1929]/80 border border-[#1E3A5F] rounded-xl overflow-hidden scroll-animate"
                    x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex items-center justify-between p-6 text-left">
                        <div class="flex items-center gap-4">
                            <span class="px-3 py-1 bg-[#10B981]/20 text-[#10B981] text-sm font-mono rounded">GET</span>
                            <code class="text-white font-mono">/certificates</code>
                            <span class="text-[#8EC5FF]/60 text-sm">List Sertifikat</span>
                            <span class="px-2 py-0.5 bg-[#F59E0B]/20 text-[#F59E0B] text-xs rounded">Auth
                                Required</span>
                        </div>
                        <svg class="w-5 h-5 text-[#8EC5FF] transition-transform" :class="{ 'rotate-180': open }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="border-t border-[#1E3A5F] p-6 space-y-4">
                        <p class="text-[#BEDBFF]">Dapatkan daftar semua sertifikat milik Anda.</p>

                        {{-- Parameters --}}
                        <div>
                            <h4 class="text-white font-medium mb-2">Query Parameters</h4>
                            <table class="w-full text-sm">
                                <thead class="text-[#8EC5FF]/70">
                                    <tr>
                                        <th class="text-left py-2">Name</th>
                                        <th class="text-left">Type</th>
                                        <th class="text-left">Description</th>
                                    </tr>
                                </thead>
                                <tbody class="text-[#BEDBFF]">
                                    <tr>
                                        <td class="py-2"><code>page</code></td>
                                        <td>integer (optional)</td>
                                        <td>Nomor halaman untuk pagination</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2"><code>per_page</code></td>
                                        <td>integer (optional)</td>
                                        <td>Jumlah item per halaman (default: 15)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Response Example --}}
                        <div>
                            <h4 class="text-white font-medium mb-2">Response Example</h4>
                            <pre class="bg-[#050C1F] rounded-lg p-4 text-xs text-[#10B981] overflow-x-auto">{
  "success": true,
  "data": [
    {
      "id": 1,
      "certificate_number": "CERT-2024-001",
      "recipient_name": "John Doe",
      "program_name": "Web Development",
      "status": "active",
      "issued_at": "2024-01-15"
    }
  ],
  "meta": {
    "current_page": 1,
    "total": 50
  }
}</pre>
                        </div>

                        {{-- Code Example --}}
                        <div class="bg-[#050C1F] rounded-lg p-4 font-mono text-sm overflow-x-auto">
                            <pre class="text-[#10B981]">curl -X GET "{{ url('/api/v1/certificates') }}" \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Accept: application/json"</pre>
                        </div>
                    </div>
                </div>

                {{-- POST Certificates Endpoint --}}
                <div class="bg-[#0A1929]/80 border border-[#1E3A5F] rounded-xl overflow-hidden scroll-animate"
                    x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex items-center justify-between p-6 text-left">
                        <div class="flex items-center gap-4">
                            <span class="px-3 py-1 bg-[#3B82F6]/20 text-[#3B82F6] text-sm font-mono rounded">POST</span>
                            <code class="text-white font-mono">/certificates</code>
                            <span class="text-[#8EC5FF]/60 text-sm">Buat Sertifikat Baru</span>
                            <span class="px-2 py-0.5 bg-[#F59E0B]/20 text-[#F59E0B] text-xs rounded">Auth
                                Required</span>
                        </div>
                        <svg class="w-5 h-5 text-[#8EC5FF] transition-transform" :class="{ 'rotate-180': open }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="border-t border-[#1E3A5F] p-6 space-y-4">
                        <p class="text-[#BEDBFF]">Buat sertifikat baru. Pastikan kuota bulanan Anda mencukupi.</p>

                        {{-- Request Body --}}
                        <div>
                            <h4 class="text-white font-medium mb-2">Request Body</h4>
                            <table class="w-full text-sm">
                                <thead class="text-[#8EC5FF]/70">
                                    <tr>
                                        <th class="text-left py-2">Field</th>
                                        <th class="text-left">Type</th>
                                        <th class="text-left">Required</th>
                                        <th class="text-left">Description</th>
                                    </tr>
                                </thead>
                                <tbody class="text-[#BEDBFF]">
                                    <tr>
                                        <td class="py-2"><code>recipient_name</code></td>
                                        <td>string</td>
                                        <td>✓</td>
                                        <td>Nama penerima sertifikat</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2"><code>program_name</code></td>
                                        <td>string</td>
                                        <td>✓</td>
                                        <td>Nama program/pelatihan</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2"><code>issue_date</code></td>
                                        <td>date</td>
                                        <td>✓</td>
                                        <td>Tanggal terbit (YYYY-MM-DD)</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2"><code>expiry_date</code></td>
                                        <td>date</td>
                                        <td></td>
                                        <td>Tanggal kadaluarsa (opsional)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Request Example --}}
                        <div>
                            <h4 class="text-white font-medium mb-2">Request Example</h4>
                            <pre class="bg-[#050C1F] rounded-lg p-4 text-xs text-[#10B981] overflow-x-auto">{
  "recipient_name": "John Doe",
  "program_name": "Web Development Bootcamp",
  "issue_date": "2024-01-15",
  "expiry_date": "2025-01-15"
}</pre>
                        </div>

                        {{-- Code Example --}}
                        <div class="bg-[#050C1F] rounded-lg p-4 font-mono text-sm overflow-x-auto">
                            <pre class="text-[#10B981]">curl -X POST "{{ url('/api/v1/certificates') }}" \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"recipient_name":"John Doe","program_name":"Web Dev"}'</pre>
                        </div>
                    </div>
                </div>

                {{-- PUT Revoke Endpoint --}}
                <div class="bg-[#0A1929]/80 border border-[#1E3A5F] rounded-xl overflow-hidden scroll-animate"
                    x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex items-center justify-between p-6 text-left">
                        <div class="flex items-center gap-4">
                            <span class="px-3 py-1 bg-[#F59E0B]/20 text-[#F59E0B] text-sm font-mono rounded">PUT</span>
                            <code class="text-white font-mono">/certificates/{id}/revoke</code>
                            <span class="text-[#8EC5FF]/60 text-sm">Cabut Sertifikat</span>
                            <span class="px-2 py-0.5 bg-[#F59E0B]/20 text-[#F59E0B] text-xs rounded">Auth
                                Required</span>
                        </div>
                        <svg class="w-5 h-5 text-[#8EC5FF] transition-transform" :class="{ 'rotate-180': open }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="border-t border-[#1E3A5F] p-6 space-y-4">
                        <p class="text-[#BEDBFF]">Cabut sertifikat yang sudah diterbitkan. Sertifikat yang dicabut tidak
                            dapat digunakan lagi.</p>

                        {{-- Parameters --}}
                        <div>
                            <h4 class="text-white font-medium mb-2">Path Parameters</h4>
                            <table class="w-full text-sm">
                                <thead class="text-[#8EC5FF]/70">
                                    <tr>
                                        <th class="text-left py-2">Name</th>
                                        <th class="text-left">Type</th>
                                        <th class="text-left">Description</th>
                                    </tr>
                                </thead>
                                <tbody class="text-[#BEDBFF]">
                                    <tr>
                                        <td class="py-2"><code>id</code></td>
                                        <td>integer</td>
                                        <td>ID sertifikat yang akan dicabut</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Request Body --}}
                        <div>
                            <h4 class="text-white font-medium mb-2">Request Body (Optional)</h4>
                            <table class="w-full text-sm">
                                <thead class="text-[#8EC5FF]/70">
                                    <tr>
                                        <th class="text-left py-2">Field</th>
                                        <th class="text-left">Type</th>
                                        <th class="text-left">Description</th>
                                    </tr>
                                </thead>
                                <tbody class="text-[#BEDBFF]">
                                    <tr>
                                        <td class="py-2"><code>reason</code></td>
                                        <td>string</td>
                                        <td>Alasan pencabutan sertifikat</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Code Example --}}
                        <div class="bg-[#050C1F] rounded-lg p-4 font-mono text-sm overflow-x-auto">
                            <pre class="text-[#10B981]">curl -X PUT "{{ url('/api/v1/certificates') }}/123/revoke" \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"reason":"Data penerima salah"}'</pre>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Response Codes --}}
            <div class="mt-12 scroll-animate">
                <h2 class="text-2xl font-bold text-white mb-6">Response Codes</h2>
                <div class="bg-[#0A1929]/80 border border-[#1E3A5F] rounded-xl overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-[#1E3A5F]/30">
                            <tr>
                                <th class="text-left py-3 px-4 text-[#8EC5FF]">Code</th>
                                <th class="text-left py-3 px-4 text-[#8EC5FF]">Description</th>
                            </tr>
                        </thead>
                        <tbody class="text-[#BEDBFF]">
                            <tr class="border-t border-[#1E3A5F]">
                                <td class="py-3 px-4"><span
                                        class="px-2 py-0.5 bg-[#10B981]/20 text-[#10B981] rounded">200</span></td>
                                <td>Success</td>
                            </tr>
                            <tr class="border-t border-[#1E3A5F]">
                                <td class="py-3 px-4"><span
                                        class="px-2 py-0.5 bg-[#3B82F6]/20 text-[#3B82F6] rounded">201</span></td>
                                <td>Created - Resource berhasil dibuat</td>
                            </tr>
                            <tr class="border-t border-[#1E3A5F]">
                                <td class="py-3 px-4"><span
                                        class="px-2 py-0.5 bg-[#F59E0B]/20 text-[#F59E0B] rounded">401</span></td>
                                <td>Unauthorized - Token tidak valid</td>
                            </tr>
                            <tr class="border-t border-[#1E3A5F]">
                                <td class="py-3 px-4"><span
                                        class="px-2 py-0.5 bg-[#F59E0B]/20 text-[#F59E0B] rounded">404</span></td>
                                <td>Not Found - Resource tidak ditemukan</td>
                            </tr>
                            <tr class="border-t border-[#1E3A5F]">
                                <td class="py-3 px-4"><span
                                        class="px-2 py-0.5 bg-[#EF4444]/20 text-[#EF4444] rounded">422</span></td>
                                <td>Validation Error - Data tidak valid</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Load stats on page load
            document.addEventListener('DOMContentLoaded', async () => {
                try {
                    const response = await fetch('{{ url('/api/v1/stats') }}');
                    const data = await response.json();
                    if (data.success) {
                        document.getElementById('statTotal').textContent = data.data.total_certificates.toLocaleString();
                        document.getElementById('statActive').textContent = data.data.active_certificates.toLocaleString();
                        document.getElementById('statIssuers').textContent = data.data.total_issuers.toLocaleString();
                        document.getElementById('statBlockchain').textContent = data.data.blockchain_verified.toLocaleString();
                    }
                } catch (e) {
                    console.error('Failed to load stats:', e);
                }
            });

            // Try Verify
            async function tryVerify() {
                const hash = document.getElementById('verifyInput').value;
                const resultEl = document.getElementById('verifyResult');

                if (!hash) {
                    alert('Masukkan hash atau nomor sertifikat');
                    return;
                }

                resultEl.classList.remove('hidden');
                resultEl.textContent = 'Loading...';

                try {
                    const response = await fetch(`{{ url('/api/v1/verify') }}/${hash}`);
                    const data = await response.json();
                    resultEl.textContent = JSON.stringify(data, null, 2);
                } catch (e) {
                    resultEl.textContent = 'Error: ' + e.message;
                }
            }

            // Copy to clipboard
            function copyToClipboard(elementId) {
                const text = document.getElementById(elementId).textContent;
                navigator.clipboard.writeText(text);

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Copied!',
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            }
        </script>
    @endpush
</x-layouts.app>