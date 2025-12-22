{{-- resources/views/components/chat-widget.blade.php --}}
@props(['role' => 'user'])

@php
    // FAQ berdasarkan role
    $faqs = $role === 'lembaga' ? [
        ['q' => 'Bagaimana cara membuat sertifikat?', 'a' => 'Untuk membuat sertifikat, buka menu "Sertifikat" > "Buat Baru", pilih template, isi data penerima, lalu klik "Terbitkan". Sertifikat akan otomatis dikirim ke email penerima.'],
        ['q' => 'Bagaimana cara upload template?', 'a' => 'Buka menu "Template" > "Upload Template", pilih file template (format .docx/.pdf), atur placeholder, lalu klik "Simpan". Template siap digunakan untuk menerbitkan sertifikat.'],
        ['q' => 'Berapa kuota sertifikat saya?', 'a' => 'Kuota sertifikat dapat dilihat di Dashboard. Untuk menambah kuota, Anda bisa upgrade paket di menu "Pengaturan" > "Langganan".'],
        ['q' => 'Bagaimana cara revoke sertifikat?', 'a' => 'Buka detail sertifikat yang ingin dicabut, klik tombol "Revoke/Cabut", masukkan alasan pencabutan, lalu konfirmasi. Sertifikat akan langsung tidak valid.'],
    ] : [
        ['q' => 'Bagaimana cara melihat sertifikat saya?', 'a' => 'Buka menu "Sertifikat Saya" untuk melihat semua sertifikat yang Anda terima dari berbagai lembaga. Anda bisa download atau share sertifikat dari sana.'],
        ['q' => 'Bagaimana cara verifikasi sertifikat?', 'a' => 'Buka halaman Verifikasi, masukkan kode hash yang tertera di sertifikat atau scan QR Code. Sistem akan menampilkan status keaslian sertifikat.'],
        ['q' => 'Bagaimana cara share sertifikat?', 'a' => 'Buka detail sertifikat, klik tombol "Share", pilih platform (LinkedIn, WhatsApp, dll) atau salin link untuk dibagikan.'],
        ['q' => 'Sertifikat tidak muncul, apa yang harus dilakukan?', 'a' => 'Pastikan email Anda sama dengan email penerima sertifikat. Jika masih tidak muncul, hubungi lembaga penerbit sertifikat untuk konfirmasi.'],
    ];
@endphp

{{-- Chat Widget Container --}}
<div class="fixed bottom-6 right-6 z-50" id="chatWidget">
    {{-- Chat Modal --}}
    <div id="chatModal" class="hidden mb-4 w-80 sm:w-96 rounded-2xl overflow-hidden shadow-2xl border border-white/10"
        style="background: linear-gradient(180deg, #0c1829 0%, #0f1f35 100%);">
        {{-- Header --}}
        <div
            class="flex items-center justify-between p-4 border-b border-white/10 bg-gradient-to-r from-blue-600 to-indigo-600">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <div>
                    <p class="text-white font-semibold text-sm">SertiKu Support</p>
                    <p class="text-white/70 text-xs flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                        Online • {{ $role === 'lembaga' ? 'Bantuan Lembaga' : 'Bantuan Pengguna' }}
                    </p>
                </div>
            </div>
            <button onclick="toggleChat()" class="text-white/70 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Messages Area --}}
        <div class="h-72 overflow-y-auto p-4 space-y-3" id="chatMessages">
            {{-- Welcome Message --}}
            <div class="flex items-start gap-2">
                <div
                    class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <div class="bg-white/10 rounded-xl rounded-tl-none px-3 py-2 max-w-[85%]">
                    <p class="text-white text-sm">Halo! 👋 Ada yang bisa kami bantu?</p>
                    <p class="text-white/40 text-xs mt-1">Baru saja</p>
                </div>
            </div>

            {{-- Quick FAQ Buttons --}}
            <div class="pl-10 space-y-2" id="faqButtons">
                <p class="text-white/50 text-xs mb-2">Pertanyaan Umum
                    {{ $role === 'lembaga' ? 'Lembaga' : 'Pengguna' }}:
                </p>
                @foreach($faqs as $index => $faq)
                    <button onclick="askFAQ({{ $index }})"
                        class="w-full text-left px-3 py-2 rounded-lg bg-blue-500/10 border border-blue-500/20 text-blue-300 text-xs hover:bg-blue-500/20 transition truncate">
                        {{ $faq['q'] }}
                    </button>
                @endforeach

                {{-- Hubungi Admin Button - Redirect to Support Page --}}
                @auth
                <a href="{{ route('support.index') }}"
                    class="w-full text-left px-3 py-2.5 rounded-lg bg-green-500/20 border border-green-400/40 text-green-400 text-xs hover:bg-green-500/30 transition flex items-center gap-2 mt-3 font-semibold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Hubungi Admin Langsung
                    <svg class="w-3 h-3 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                @endauth
            </div>
        </div>

        {{-- Input Area --}}
        <div class="p-3 border-t border-white/10">
            <div class="flex gap-2">
                <input type="text" id="chatInput"
                    class="flex-1 rounded-xl bg-white/10 border border-white/10 px-4 py-2 text-white text-sm placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500/50"
                    placeholder="Ketik pesan..." onkeypress="if(event.key === 'Enter') sendChatMessage()">
                <button onclick="sendChatMessage()"
                    class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white hover:brightness-110 transition flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Toggle Button --}}
    <button onclick="toggleChat()" id="chatToggleBtn"
        class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg hover:scale-110 transition-all duration-300">
        <svg id="chatIconOpen" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
        <svg id="chatIconClose" class="w-6 h-6 text-white hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>

<script>
    // FAQ Data dari Blade
    const chatFaqs = @json($faqs);
    const chatRole = '{{ $role }}';
    const CHAT_STORAGE_KEY = 'sertiku_chat_' + chatRole;
    const CHAT_EXPIRY_MS = 5 * 60 * 1000; // 5 minutes
    let supportTicketId = null;
    let supportPollingInterval = null;

    // Initialize chat from localStorage
    document.addEventListener('DOMContentLoaded', function() {
        loadChatFromStorage();
    });

    function toggleChat() {
        const modal = document.getElementById('chatModal');
        const iconOpen = document.getElementById('chatIconOpen');
        const iconClose = document.getElementById('chatIconClose');

        modal.classList.toggle('hidden');
        iconOpen.classList.toggle('hidden');
        iconClose.classList.toggle('hidden');
    }

    function saveChatToStorage() {
        const messages = document.getElementById('chatMessages').innerHTML;
        const faqVisible = document.getElementById('faqButtons')?.style.display !== 'none';
        const data = {
            messages: messages,
            faqVisible: faqVisible,
            timestamp: Date.now(),
            ticketId: supportTicketId,
        };
        localStorage.setItem(CHAT_STORAGE_KEY, JSON.stringify(data));
    }

    function loadChatFromStorage() {
        const stored = localStorage.getItem(CHAT_STORAGE_KEY);
        if (!stored) return;

        try {
            const data = JSON.parse(stored);
            // Check if expired (5 minutes)
            if (Date.now() - data.timestamp > CHAT_EXPIRY_MS) {
                localStorage.removeItem(CHAT_STORAGE_KEY);
                return;
            }

            // Restore messages
            document.getElementById('chatMessages').innerHTML = data.messages;
            if (!data.faqVisible) {
                const faqBtns = document.getElementById('faqButtons');
                if (faqBtns) faqBtns.style.display = 'none';
            }

            // Restore ticket polling if exists
            if (data.ticketId) {
                supportTicketId = data.ticketId;
                startSupportPolling();
            }
        } catch (e) {
            console.error('Failed to load chat:', e);
        }
    }

    function askFAQ(index) {
        const faq = chatFaqs[index];
        document.getElementById('faqButtons').style.display = 'none';
        addUserMessage(faq.q);
        setTimeout(() => {
            addBotMessage(faq.a);
            saveChatToStorage();
        }, 800);
    }

    async function sendChatMessage() {
        const input = document.getElementById('chatInput');
        const text = input.value.trim();

        if (!text) return;

        const faqBtns = document.getElementById('faqButtons');
        if (faqBtns) faqBtns.style.display = 'none';

        addUserMessage(text);
        input.value = '';
        showTypingIndicator();

        try {
            const response = await fetch('/api/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ message: text, role: chatRole }),
            });

            const data = await response.json();
            hideTypingIndicator();
            addBotMessage(data.reply || 'Maaf, terjadi kesalahan. Silakan coba lagi.');
            saveChatToStorage();

        } catch (error) {
            console.error('Chat error:', error);
            hideTypingIndicator();
            addBotMessage('Maaf, terjadi kesalahan koneksi. Silakan coba lagi.');
            saveChatToStorage();
        }
    }

    // ======== HUBUNGI ADMIN FEATURE (INLINE CHAT) ========
    let isSupportMode = false;

    async function startSupportChat() {
        // Hide FAQ and show support mode
        document.getElementById('faqButtons').style.display = 'none';

        addBotMessage('🎫 Memulai chat dengan Admin...');

        try {
            // Create new ticket automatically
            const response = await fetch('/support/ticket', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    subject: 'Chat Support - ' + new Date().toLocaleString('id-ID'),
                    message: 'Memulai percakapan dengan admin'
                }),
            });

            console.log('Response status:', response.status);

            if (!response.ok) {
                const errorText = await response.text();
                console.error('Response error:', errorText);
                addBotMessage(`❌ Gagal membuat tiket (${response.status}). Pastikan Anda sudah login.`);
                return;
            }

            const data = await response.json();
            console.log('Response data:', data);

            if (data.success) {
                supportTicketId = data.ticket.id;
                isSupportMode = true;

                // Show ticket number
                addBotMessage(`✅ **Tiket #${supportTicketId}** berhasil dibuat!\n\nSilakan ketik pesan Anda. Admin akan membalas secepatnya.\n\n_Simpan nomor tiket ini untuk referensi._`);

                // Change input placeholder
                document.getElementById('chatInput').placeholder = 'Ketik pesan ke admin...';

                // Start polling for admin replies
                startSupportPolling();
                saveChatToStorage();
            } else {
                addBotMessage('❌ Gagal membuat tiket: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Ticket error:', error);
            addBotMessage('❌ Terjadi kesalahan jaringan. Pastikan Anda sudah login.');
        }
    }

    // Override sendChatMessage when in support mode
    const originalSendChatMessage = sendChatMessage;
    sendChatMessage = async function() {
        if (isSupportMode && supportTicketId) {
            await sendSupportMessage();
        } else {
            await originalSendChatMessage();
        }
    };

    async function sendSupportMessage() {
        const input = document.getElementById('chatInput');
        const text = input.value.trim();

        if (!text || !supportTicketId) return;

        addUserMessage(text);
        input.value = '';

        try {
            const response = await fetch('/support/message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ ticket_id: supportTicketId, message: text }),
            });

            const data = await response.json();
            if (data.success) {
                saveChatToStorage();
            }
        } catch (e) {
            console.error('Send message error:', e);
            addBotMessage('❌ Gagal mengirim pesan. Coba lagi.');
        }
    }

    function startSupportPolling() {
        if (supportPollingInterval) clearInterval(supportPollingInterval);
        supportPollingInterval = setInterval(checkSupportReplies, 5000);
    }

    async function checkSupportReplies() {
        if (!supportTicketId) return;

        try {
            const response = await fetch(`/support/messages/${supportTicketId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
            });

            const data = await response.json();
            if (data.messages) {
                const adminReplies = data.messages.filter(m => m.is_from_admin && !m.displayed);
                adminReplies.forEach(msg => {
                    addAdminReplyMessage(msg.message, msg.sender?.name || 'Admin');
                    msg.displayed = true;
                });
                if (adminReplies.length > 0) saveChatToStorage();
            }
        } catch (e) {
            console.log('Polling error:', e);
        }
    }

    async function sendSupportReply(text) {
        if (!supportTicketId) return;

        try {
            await fetch('/support/message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ ticket_id: supportTicketId, message: text }),
            });
            saveChatToStorage();
        } catch (e) {
            console.error('Reply error:', e);
        }
    }

    function addAdminReplyMessage(text, adminName) {
        const messages = document.getElementById('chatMessages');
        const time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

        messages.innerHTML += `
            <div class="flex items-start gap-2">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="bg-green-500/20 border border-green-500/30 rounded-xl rounded-tl-none px-3 py-2 max-w-[85%]">
                    <p class="text-green-300 text-xs font-medium mb-1">${escapeHtml(adminName)}</p>
                    <p class="text-white text-sm">${escapeHtml(text)}</p>
                    <p class="text-white/40 text-xs mt-1">${time}</p>
                </div>
            </div>
        `;
        messages.scrollTop = messages.scrollHeight;
    }

    // ======== HELPER FUNCTIONS ========
    function showTypingIndicator() {
        const messages = document.getElementById('chatMessages');
        const indicator = document.createElement('div');
        indicator.id = 'typingIndicator';
        indicator.className = 'flex items-start gap-2';
        indicator.innerHTML = `
            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01"/>
                </svg>
            </div>
            <div class="bg-white/10 rounded-xl rounded-tl-none px-3 py-2">
                <div class="flex gap-1">
                    <span class="w-2 h-2 bg-white/60 rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                    <span class="w-2 h-2 bg-white/60 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                    <span class="w-2 h-2 bg-white/60 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                </div>
            </div>
        `;
        messages.appendChild(indicator);
        messages.scrollTop = messages.scrollHeight;
    }

    function hideTypingIndicator() {
        document.getElementById('typingIndicator')?.remove();
    }

    function addUserMessage(text) {
        const messages = document.getElementById('chatMessages');
        const time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

        messages.innerHTML += `
            <div class="flex items-start gap-2 justify-end">
                <div class="bg-blue-500/30 rounded-xl rounded-tr-none px-3 py-2 max-w-[85%]">
                    <p class="text-white text-sm">${escapeHtml(text)}</p>
                    <p class="text-white/40 text-xs mt-1">${time}</p>
                </div>
            </div>
        `;
        messages.scrollTop = messages.scrollHeight;
        saveChatToStorage();
    }

    function addBotMessage(text) {
        const messages = document.getElementById('chatMessages');
        const time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

        messages.innerHTML += `
            <div class="flex items-start gap-2">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <div class="bg-white/10 rounded-xl rounded-tl-none px-3 py-2 max-w-[85%]">
                    <div class="text-white text-sm leading-relaxed">${formatMarkdown(text)}</div>
                    <p class="text-white/40 text-xs mt-1">${time}</p>
                </div>
            </div>
        `;
        messages.scrollTop = messages.scrollHeight;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function formatMarkdown(text) {
        if (!text) return '';
        return text
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/__(.*?)__/g, '<strong>$1</strong>')
            .replace(/\*([^*]+)\*/g, '<em>$1</em>')
            .replace(/_([^_]+)_/g, '<em>$1</em>')
            .replace(/^\d+\.\s+(.*)$/gm, '<li class="ml-4 list-decimal">$1</li>')
            .replace(/^[\-\*]\s+(.*)$/gm, '<li class="ml-4 list-disc">$1</li>')
            .replace(/^###\s+(.*)$/gm, '<strong class="text-blue-300">$1</strong>')
            .replace(/^##\s+(.*)$/gm, '<strong class="text-blue-300 text-base">$1</strong>')
            .replace(/\n\n/g, '<br><br>')
            .replace(/\n/g, '<br>')
            .replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" class="text-blue-400 underline" target="_blank">$1</a>');
    }
</script>
