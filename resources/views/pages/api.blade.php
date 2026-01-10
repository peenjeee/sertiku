<x-layouts.app title="API Documentation - SertiKu"
    description="Dokumentasi API SertiKu untuk integrasi verifikasi sertifikat digital">
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            {{-- Header --}}
            <div class="text-center mb-8 md:mb-12 scroll-animate">
                <h1 class="text-2xl md:text-4xl font-bold text-white mb-3 md:mb-4">API Documentation</h1>
                <p class="text-[#8EC5FF]/80 text-sm md:text-lg max-w-2xl mx-auto px-2">
                    Integrasikan SertiKu ke aplikasi Anda untuk verifikasi sertifikat digital secara otomatis.
                </p>
            </div>

            {{-- Base URL --}}
            <div class="bg-[#0A1929]/80 border border-[#1E3A5F] rounded-xl p-6 mb-8 scroll-animate">
                <h2 class="text-xl font-semibold text-white mb-3">Base URL</h2>
                <div class="flex items-center gap-2 bg-[#050C1F] rounded-lg p-3 md:p-4">
                    <code class="text-[#10B981] font-mono text-xs md:text-lg flex-1 break-all"
                        id="baseUrl">{{ url('/api/v1') }}</code>
                    <button onclick="copyToClipboard('baseUrl')" class="text-[#8EC5FF] hover:text-white transition p-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Quick Stats Widget --}}
            <div class="bg-[rgba(59,130,246,0.15)] border border-[#3B82F6]/30 rounded-xl p-6 mb-8 scroll-animate">
                <h3 class="text-lg font-semibold text-white mb-4">Platform Statistics (Live)</h3>
                <div id="statsContainer" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-[#3B82F6]" id="statTotal">
                            <div
                                class="w-8 h-8 mx-auto border-2 border-[#3B82F6] border-t-transparent rounded-full animate-spin">
                            </div>
                        </div>
                        <div class="text-xs text-[#8EC5FF]/70">Total Sertifikat</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-[#10B981]" id="statActive">
                            <div
                                class="w-8 h-8 mx-auto border-2 border-[#10B981] border-t-transparent rounded-full animate-spin">
                            </div>
                        </div>
                        <div class="text-xs text-[#8EC5FF]/70">Aktif</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-[#8B5CF6]" id="statIssuers">
                            <div
                                class="w-8 h-8 mx-auto border-2 border-[#8B5CF6] border-t-transparent rounded-full animate-spin">
                            </div>
                        </div>
                        <div class="text-xs text-[#8EC5FF]/70">Penerbit</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-[#F59E0B]" id="statBlockchain">
                            <div
                                class="w-8 h-8 mx-auto border-2 border-[#F59E0B] border-t-transparent rounded-full animate-spin">
                            </div>
                        </div>
                        <div class="text-xs text-[#8EC5FF]/70">On-Chain</div>
                    </div>
                </div>
            </div>

            {{-- Endpoints --}}
            <div class="space-y-6">
                {{-- Verify Endpoint --}}
                <div class="bg-[#0A1929]/80 border border-[#1E3A5F] rounded-xl overflow-hidden scroll-animate"
                    x-data="{ open: true }">
                    <button @click="open = !open" class="w-full flex items-center justify-between p-4 md:p-6 text-left">
                        <div class="flex flex-wrap items-center gap-2 md:gap-4">
                            <span
                                class="px-2 md:px-3 py-1 bg-[#10B981]/20 text-[#10B981] text-xs md:text-sm font-mono rounded">GET</span>
                            <code class="text-white font-mono text-sm md:text-base">/verify/{hash}</code>
                            <span class="text-[#8EC5FF]/60 text-xs md:text-sm hidden sm:inline">Verifikasi
                                Sertifikat</span>
                        </div>
                        <svg class="w-5 h-5 text-[#8EC5FF] transition-transform flex-shrink-0"
                            :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                        <td>UUID hash atau nomor sertifikat (e.g. SERT-202512-XXXXXX)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Try it out --}}
                        <div class="bg-[#050C1F] rounded-lg p-3 md:p-4">
                            <h4 class="text-white font-medium mb-3">Try it out</h4>
                            <div class="flex flex-col sm:flex-row gap-2">
                                <input type="text" id="verifyInput" placeholder="Masukkan hash atau nomor sertifikat"
                                    class="flex-1 bg-[#0A1929] border border-[#1E3A5F] rounded-lg px-3 md:px-4 py-2 text-sm md:text-base text-white placeholder-[#8EC5FF]/50 focus:border-[#3B82F6] focus:outline-none">
                                <button onclick="tryVerify()"
                                    class="px-4 py-2 bg-[#3B82F6] text-white rounded-lg hover:bg-[#2563EB] transition whitespace-nowrap">
                                    Test
                                </button>
                            </div>
                            <pre id="verifyResult" class="mt-4 text-xs text-[#8EC5FF] overflow-x-auto hidden"></pre>
                        </div>

                        {{-- Response Example --}}
                        <div>
                            <h4 class="text-white font-medium mb-2">Response Example</h4>
                            <pre class="bg-[#050C1F] rounded-lg p-4 text-xs text-[#10B981] overflow-x-auto">{
  "success": true,
  "message": "Sertifikat valid",
  "data": {
    "valid": true,
    "certificate": {
      "certificate_number": "SERT-202512-ABCDEF",
      "recipient_name": "John Doe",
      "course_name": "Web Development Bootcamp",
      "issue_date": "2024-12-27",
      "status": "active",
      "issuer": {
        "name": "Tech Academy",
        "type": "Lembaga Pelatihan"
      },
      "blockchain": {
        "enabled": true,
        "verified": true,
        "tx_hash": "0x123...",
        "explorer_url": "https://polygonscan.com/tx/0x123..."
      },
      "verification_url": "https://sertiku.web.id/verifikasi/SERT-202512-ABCDEF"
    }
  }
}</pre>
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
                                <pre x-show="tab === 'curl'" class="text-[#10B981]">curl -X GET "{{ url('/api/v1/verify') }}/SERT-202512-ABCDEF" \
  -H "Accept: application/json"</pre>
                                <pre x-show="tab === 'php'" class="text-[#10B981]">$response = Http::get('{{ url('/api/v1/verify') }}/SERT-202512-ABCDEF');
$data = $response->json();</pre>
                                <pre x-show="tab === 'js'" class="text-[#10B981]">const response = await fetch('{{ url('/api/v1/verify') }}/SERT-202512-ABCDEF');
const data = await response.json();</pre>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Stats Endpoint --}}
                <div class="bg-[#0A1929]/80 border border-[#1E3A5F] rounded-xl overflow-hidden scroll-animate"
                    x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex items-center justify-between p-4 md:p-6 text-left">
                        <div class="flex flex-wrap items-center gap-2 md:gap-4">
                            <span
                                class="px-2 md:px-3 py-1 bg-[#10B981]/20 text-[#10B981] text-xs md:text-sm font-mono rounded">GET</span>
                            <code class="text-white font-mono text-sm md:text-base">/stats</code>
                            <span class="text-[#8EC5FF]/60 text-xs md:text-sm hidden sm:inline">Statistik
                                Platform</span>
                        </div>
                        <svg class="w-5 h-5 text-[#8EC5FF] transition-transform flex-shrink-0"
                            :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    "total_certificates": 1250,
    "active_certificates": 1200,
    "total_issuers": 45,
    "blockchain_verified": 350,
    "platform": {
      "name": "SertiKu",
      "version": "1.0.0",
      "website": "https://sertiku.web.id"
    }
  }
}</pre>
                        </div>
                    </div>
                </div>

                {{-- GET Certificates Endpoint --}}
                <div class="bg-[#0A1929]/80 border border-[#1E3A5F] rounded-xl overflow-hidden scroll-animate"
                    x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex items-center justify-between p-4 md:p-6 text-left">
                        <div class="flex flex-wrap items-center gap-2 md:gap-4">
                            <span
                                class="px-2 md:px-3 py-1 bg-[#10B981]/20 text-[#10B981] text-xs md:text-sm font-mono rounded">GET</span>
                            <code class="text-white font-mono text-sm md:text-base">/certificates</code>
                            <span class="text-[#8EC5FF]/60 text-xs md:text-sm hidden sm:inline">List Sertifikat</span>
                            <span class="px-2 py-0.5 bg-[#F59E0B]/20 text-[#F59E0B] text-xs rounded">Auth</span>
                        </div>
                        <svg class="w-5 h-5 text-[#8EC5FF] transition-transform flex-shrink-0"
                            :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="border-t border-[#1E3A5F] p-6 space-y-4">
                        <p class="text-[#BEDBFF]">Dapatkan daftar semua sertifikat milik Anda.</p>

                        {{-- Response Example --}}
                        <div>
                            <h4 class="text-white font-medium mb-2">Response Example</h4>
                            <pre class="bg-[#050C1F] rounded-lg p-4 text-xs text-[#10B981] overflow-x-auto">{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "certificate_number": "SERT-202512-ABCDEF",
        "recipient_name": "John Doe",
        "course_name": "Web Development",
        "status": "active",
        "created_at": "2024-12-27T10:00:00.000000Z"
      }
    ],
    "per_page": 20,
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
                    <button @click="open = !open" class="w-full flex items-center justify-between p-4 md:p-6 text-left">
                        <div class="flex flex-wrap items-center gap-2 md:gap-4">
                            <span
                                class="px-2 md:px-3 py-1 bg-[#3B82F6]/20 text-[#3B82F6] text-xs md:text-sm font-mono rounded">POST</span>
                            <code class="text-white font-mono text-sm md:text-base">/certificates</code>
                            <span class="text-[#8EC5FF]/60 text-xs md:text-sm hidden sm:inline">Buat Sertifikat
                                Baru</span>
                            <span class="px-2 py-0.5 bg-[#F59E0B]/20 text-[#F59E0B] text-xs rounded">Auth</span>
                        </div>
                        <svg class="w-5 h-5 text-[#8EC5FF] transition-transform flex-shrink-0"
                            :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="border-t border-[#1E3A5F] p-6 space-y-4">
                        <p class="text-[#BEDBFF]">Buat sertifikat baru (sama seperti menu Terbitkan Sertifikat).
                            Pastikan kuota bulanan mencukupi.</p>

                        {{-- Request Body --}}
                        <div>
                            <h4 class="text-white font-medium mb-2">Request Body</h4>
                            <div class="overflow-x-auto">
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
                                            <td class="text-[#10B981]">✓</td>
                                            <td>Nama penerima sertifikat</td>
                                        </tr>
                                        <tr>
                                            <td class="py-2"><code>recipient_email</code></td>
                                            <td>string</td>
                                            <td></td>
                                            <td>Email penerima</td>
                                        </tr>
                                        <tr>
                                            <td class="py-2"><code>course_name</code></td>
                                            <td>string</td>
                                            <td class="text-[#10B981]">✓</td>
                                            <td>Nama program/pelatihan/kursus</td>
                                        </tr>
                                        <tr>
                                            <td class="py-2"><code>category</code></td>
                                            <td>string</td>
                                            <td></td>
                                            <td>Kategori (misal: "Webinar", "Bootcamp")</td>
                                        </tr>
                                        <tr>
                                            <td class="py-2"><code>description</code></td>
                                            <td>string</td>
                                            <td></td>
                                            <td>Deskripsi sertifikat</td>
                                        </tr>
                                        <tr>
                                            <td class="py-2"><code>issue_date</code></td>
                                            <td>date</td>
                                            <td class="text-[#10B981]">✓</td>
                                            <td>Tanggal terbit (YYYY-MM-DD)</td>
                                        </tr>
                                        <tr>
                                            <td class="py-2"><code>expire_date</code></td>
                                            <td>date</td>
                                            <td></td>
                                            <td>Tanggal kadaluarsa (opsional)</td>
                                        </tr>
                                        <tr>
                                            <td class="py-2"><code>template_id</code></td>
                                            <td>integer</td>
                                            <td></td>
                                            <td>ID template sertifikat</td>
                                        </tr>
                                        <tr>
                                            <td class="py-2"><code>blockchain_enabled</code></td>
                                            <td>boolean</td>
                                            <td></td>
                                            <td>Upload ke Polygon Blockchain</td>
                                        </tr>
                                        <tr>
                                            <td class="py-2"><code>ipfs_enabled</code></td>
                                            <td>boolean</td>
                                            <td></td>
                                            <td>Upload ke IPFS</td>
                                        </tr>
                                        <tr>
                                            <td class="py-2"><code>send_email</code></td>
                                            <td>boolean</td>
                                            <td></td>
                                            <td>Kirim notifikasi email ke penerima</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Request Example --}}
                        <div>
                            <h4 class="text-white font-medium mb-2">Request Example</h4>
                            <pre class="bg-[#050C1F] rounded-lg p-4 text-xs text-[#10B981] overflow-x-auto">{
  "recipient_name": "John Doe",
  "recipient_email": "john@example.com",
  "course_name": "Web Development Bootcamp",
  "category": "Bootcamp",
  "description": "Telah menyelesaikan pelatihan Web Development",
  "issue_date": "2024-12-27",
  "expire_date": "2025-12-27",
  "blockchain_enabled": true,
  "ipfs_enabled": true,
  "send_email": true
}</pre>
                        </div>

                        {{-- Response Example --}}
                        <div>
                            <h4 class="text-white font-medium mb-2">Response Example (201 Created)</h4>
                            <pre class="bg-[#050C1F] rounded-lg p-4 text-xs text-[#10B981] overflow-x-auto">{
  "success": true,
  "message": "Sertifikat berhasil dibuat",
  "data": {
    "id": 123,
    "certificate_number": "SERT-202512-ABCDEF",
    "recipient_name": "John Doe",
    "recipient_email": "john@example.com",
    "course_name": "Web Development Bootcamp",
    "category": "Bootcamp",
    "issue_date": "2024-12-27",
    "expire_date": "2025-12-27",
    "status": "active",
    "blockchain_enabled": true,
    "blockchain_status": "pending",
    "verification_url": "https://sertiku.web.id/verifikasi/SERT-202512-ABCDEF",
    "pdf_url": "https://sertiku.web.id/storage/certificates/...",
    "created_at": "2024-12-27T10:00:00.000000Z"
  }
}</pre>
                        </div>

                        {{-- Code Example --}}
                        <div class="bg-[#050C1F] rounded-lg p-4 font-mono text-sm overflow-x-auto">
                            <pre class="text-[#10B981]">curl -X POST "{{ url('/api/v1/certificates') }}" \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "recipient_name": "John Doe",
    "recipient_email": "john@example.com",
    "course_name": "Web Development Bootcamp",
    "issue_date": "2024-12-27",
    "blockchain_enabled": true,
    "send_email": true
  }'</pre>
                        </div>
                    </div>
                </div>

                {{-- PUT Revoke Endpoint --}}
                <div class="bg-[#0A1929]/80 border border-[#1E3A5F] rounded-xl overflow-hidden scroll-animate"
                    x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex items-center justify-between p-4 md:p-6 text-left">
                        <div class="flex flex-wrap items-center gap-2 md:gap-4">
                            <span
                                class="px-2 md:px-3 py-1 bg-[#EF4444]/20 text-[#EF4444] text-xs md:text-sm font-mono rounded">PUT</span>
                            <code
                                class="text-white font-mono text-xs md:text-base break-all">/certificates/{id}/revoke</code>
                            <span class="text-[#8EC5FF]/60 text-xs md:text-sm hidden sm:inline">Cabut Sertifikat</span>
                            <span class="px-2 py-0.5 bg-[#F59E0B]/20 text-[#F59E0B] text-xs rounded">Auth</span>
                        </div>
                        <svg class="w-5 h-5 text-[#8EC5FF] transition-transform flex-shrink-0"
                            :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="border-t border-[#1E3A5F] p-6 space-y-4">
                        <p class="text-[#BEDBFF]">Cabut sertifikat yang sudah diterbitkan. Sertifikat yang dicabut tidak
                            dapat digunakan lagi namun bisa diaktifkan kembali.</p>

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

                        {{-- Response Example --}}
                        <div>
                            <h4 class="text-white font-medium mb-2">Response Example</h4>
                            <pre class="bg-[#050C1F] rounded-lg p-4 text-xs text-[#10B981] overflow-x-auto">{
  "success": true,
  "message": "Sertifikat berhasil dicabut",
  "data": {
    "id": 123,
    "certificate_number": "SERT-202512-ABCDEF",
    "status": "revoked",
    "revoked_at": "2024-12-27T15:00:00.000000Z",
    "revoke_reason": "Data penerima salah"
  }
}</pre>
                        </div>

                        {{-- Code Example --}}
                        <div class="bg-[#050C1F] rounded-lg p-4 font-mono text-sm overflow-x-auto">
                            <pre class="text-[#10B981]">curl -X PUT "{{ url('/api/v1/certificates') }}/123/revoke" \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"reason": "Data penerima salah"}'</pre>
                        </div>
                    </div>
                </div>

                {{-- PUT Reactivate Endpoint --}}
                <div class="bg-[#0A1929]/80 border border-[#1E3A5F] rounded-xl overflow-hidden scroll-animate"
                    x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex items-center justify-between p-4 md:p-6 text-left">
                        <div class="flex flex-wrap items-center gap-2 md:gap-4">
                            <span
                                class="px-2 md:px-3 py-1 bg-[#10B981]/20 text-[#10B981] text-xs md:text-sm font-mono rounded">PUT</span>
                            <code
                                class="text-white font-mono text-xs md:text-base break-all">/certificates/{id}/reactivate</code>
                            <span class="text-[#8EC5FF]/60 text-xs md:text-sm hidden sm:inline">Aktifkan Kembali</span>
                            <span class="px-2 py-0.5 bg-[#F59E0B]/20 text-[#F59E0B] text-xs rounded">Auth</span>
                        </div>
                        <svg class="w-5 h-5 text-[#8EC5FF] transition-transform flex-shrink-0"
                            :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="border-t border-[#1E3A5F] p-6 space-y-4">
                        <p class="text-[#BEDBFF]">Aktifkan kembali sertifikat yang sebelumnya dicabut (revoked).</p>

                        {{-- Response Example --}}
                        <div>
                            <h4 class="text-white font-medium mb-2">Response Example</h4>
                            <pre class="bg-[#050C1F] rounded-lg p-4 text-xs text-[#10B981] overflow-x-auto">{
  "success": true,
  "message": "Sertifikat berhasil diaktifkan kembali",
  "data": {
    "id": 123,
    "certificate_number": "SERT-202512-ABCDEF",
    "recipient_name": "John Doe",
    "status": "active",
    "reactivated_at": "2024-12-27T16:00:00.000000Z"
  }
}</pre>
                        </div>

                        {{-- Code Example --}}
                        <div class="bg-[#050C1F] rounded-lg p-4 font-mono text-sm overflow-x-auto">
                            <pre class="text-[#10B981]">curl -X PUT "{{ url('/api/v1/certificates') }}/123/reactivate" \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Accept: application/json"</pre>
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
                                        class="px-2 py-0.5 bg-[#F59E0B]/20 text-[#F59E0B] rounded">400</span></td>
                                <td>Bad Request - Request tidak valid</td>
                            </tr>
                            <tr class="border-t border-[#1E3A5F]">
                                <td class="py-3 px-4"><span
                                        class="px-2 py-0.5 bg-[#F59E0B]/20 text-[#F59E0B] rounded">401</span></td>
                                <td>Unauthorized - Token tidak valid</td>
                            </tr>
                            <tr class="border-t border-[#1E3A5F]">
                                <td class="py-3 px-4"><span
                                        class="px-2 py-0.5 bg-[#F59E0B]/20 text-[#F59E0B] rounded">403</span></td>
                                <td>Forbidden - Kuota sertifikat/blockchain/IPFS habis</td>
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
                    const response = await fetch('{{ url('/api/v1/stats') }}', {
                        headers: {
                            'Accept': 'application/json',
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    const data = await response.json();
                    if (data.success) {
                        document.getElementById('statTotal').textContent = data.data.total_certificates.toLocaleString();
                        document.getElementById('statActive').textContent = data.data.active_certificates.toLocaleString();
                        document.getElementById('statIssuers').textContent = data.data.total_issuers.toLocaleString();
                        document.getElementById('statBlockchain').textContent = data.data.blockchain_verified.toLocaleString();
                    }
                } catch (e) {
                    console.error('Failed to load stats:', e);
                    document.getElementById('statTotal').textContent = '-';
                    document.getElementById('statActive').textContent = '-';
                    document.getElementById('statIssuers').textContent = '-';
                    document.getElementById('statBlockchain').textContent = '-';
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
                    const response = await fetch(`{{ url('/api/v1/verify') }}/${encodeURIComponent(hash)}`, {
                        headers: {
                            'Accept': 'application/json',
                        }
                    });
                    const data = await response.json();
                    resultEl.textContent = JSON.stringify(data, null, 2);
                    resultEl.classList.remove('text-red-400');
                    resultEl.classList.add('text-[#8EC5FF]');
                } catch (e) {
                    resultEl.textContent = 'Error: ' + e.message;
                    resultEl.classList.remove('text-[#8EC5FF]');
                    resultEl.classList.add('text-red-400');
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