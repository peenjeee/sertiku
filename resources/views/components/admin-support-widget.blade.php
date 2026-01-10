{{-- resources/views/components/admin-support-widget.blade.php --}}
{{-- Chat widget for Admin/Master to view and reply to support tickets --}}

@php
    $openTickets = \App\Models\SupportTicket::whereIn('status', ['open', 'in_progress'])
        ->with([
            'user:id,name,avatar',
            'messages' => function ($q) {
                $q->latest()->take(1);
            }
        ])
        ->withCount([
            'messages as unread_count' => function ($q) {
                $q->whereNull('read_at')->where('is_from_admin', false);
            }
        ])
        ->latest('last_message_at')
        ->take(10)
        ->get();

    $totalUnread = $openTickets->sum('unread_count');
@endphp

<div class="fixed bottom-6 right-6 z-50" id="adminSupportWidget">
    {{-- Chat Modal --}}
    <div id="adminSupportModal"
        class="hidden mb-4 w-80 sm:w-96 rounded-2xl overflow-hidden shadow-2xl border border-white/10"
        style="background: #0F172A;">

        {{-- Header --}}
        <div id="adminSupportHeader"
            class="flex items-center justify-between p-4 border-b border-white/10 bg-[#10B981]">
            <div class="flex items-center gap-3">
                <button onclick="backToTicketList()" id="backToListBtn"
                    class="hidden p-1 rounded-lg hover:bg-white/20 transition">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center" id="headerIcon">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-white font-semibold text-sm" id="headerTitle">Support Tickets</p>
                    <p class="text-white/70 text-xs" id="headerSubtitle">{{ $openTickets->count() }} tiket aktif</p>
                </div>
            </div>
            <button onclick="toggleAdminSupport()" class="text-white/70 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Tickets List View --}}
        <div id="ticketListView" class="overflow-y-auto" style="max-height: 380px;">
            @forelse($openTickets as $ticket)
                <div onclick="openTicketChat({{ $ticket->id }}, '{{ addslashes($ticket->user->name ?? 'User') }}', '{{ addslashes($ticket->subject) }}')"
                    class="block p-4 border-b border-white/5 hover:bg-white/5 transition cursor-pointer">
                    <div class="flex items-start gap-3">
                        <div
                            class="w-10 h-10 rounded-full bg-[#3B82F6] flex items-center justify-center flex-shrink-0 overflow-hidden">
                            @if($ticket->user->avatar && (str_starts_with($ticket->user->avatar, '/storage/') || str_starts_with($ticket->user->avatar, 'http')))
                                <img src="{{ $ticket->user->avatar }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($ticket->user->name ?? 'U') }}&email={{ urlencode($ticket->user->email ?? '') }}&background=3B82F6&color=fff&bold=true&size=40"
                                    alt="Avatar" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-white font-medium text-sm truncate">{{ $ticket->user->name ?? 'User' }}</p>
                                @if($ticket->unread_count > 0)
                                    <span
                                        class="w-5 h-5 rounded-full bg-red-500 text-white text-xs flex items-center justify-center">
                                        {{ $ticket->unread_count }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-white/60 text-xs truncate mt-0.5">{{ $ticket->subject }}</p>
                            <p class="text-white/40 text-xs mt-1">
                                Tiket #{{ $ticket->id }} • {{ $ticket->last_message_at?->diffForHumans() ?? 'Baru' }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-white/5 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <p class="text-white/50 text-sm">Tidak ada tiket aktif</p>
                </div>
            @endforelse
        </div>

        {{-- Chat View (Hidden by default) --}}
        <div id="ticketChatView" class="hidden">
            {{-- Messages --}}
            <div class="h-64 overflow-y-auto p-4 space-y-3" id="adminChatMessages">
                <!-- Messages loaded dynamically -->
            </div>

            {{-- Reply Input --}}
            <div class="p-3 border-t border-white/10">
                <div class="flex gap-2">
                    <input type="text" id="adminReplyInput"
                        class="flex-1 rounded-xl bg-white/10 border border-white/10 px-4 py-2 text-white text-sm placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-green-500/50"
                        placeholder="Ketik balasan..." onkeypress="if(event.key === 'Enter') sendAdminReply()">
                    <button onclick="sendAdminReply()"
                        class="w-10 h-10 rounded-xl bg-[#10B981] flex items-center justify-center text-white hover:bg-[#059669] transition flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Toggle Button --}}
    <button onclick="toggleAdminSupport()" id="adminSupportToggleBtn"
        class="w-14 h-14 rounded-full bg-[#10B981] flex items-center justify-center shadow-lg hover:scale-110 transition-all duration-300 relative">
        <svg id="adminSupportIconOpen" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        <svg id="adminSupportIconClose" class="w-6 h-6 text-white hidden" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>

        @if($totalUnread > 0)
            <span
                class="absolute -top-1 -right-1 w-6 h-6 rounded-full bg-red-500 text-white text-xs font-bold flex items-center justify-center animate-pulse">
                {{ $totalUnread > 99 ? '99+' : $totalUnread }}
            </span>
        @endif
    </button>
</div>

<script>
    let currentAdminTicketId = null;
    let adminPollingInterval = null;
    let loadedMessageIds = new Set();

    function toggleAdminSupport() {
        const modal = document.getElementById('adminSupportModal');
        const toggleBtn = document.getElementById('adminSupportToggleBtn');

        modal.classList.toggle('hidden');
        toggleBtn.classList.toggle('hidden');

        // Reset to list view when closing
        if (modal.classList.contains('hidden')) {
            backToTicketList();
        }
    }

    async function openTicketChat(ticketId, userName, subject) {
        currentAdminTicketId = ticketId;
        loadedMessageIds.clear();

        // Switch views
        document.getElementById('ticketListView').classList.add('hidden');
        document.getElementById('ticketChatView').classList.remove('hidden');
        document.getElementById('backToListBtn').classList.remove('hidden');

        // Update header
        document.getElementById('headerTitle').textContent = userName;
        document.getElementById('headerSubtitle').textContent = `Tiket #${ticketId}`;

        // Clear and load messages
        document.getElementById('adminChatMessages').innerHTML = '<div class="text-center text-white/50 text-sm">Memuat pesan...</div>';

        await loadTicketMessages();

        // Start polling
        if (adminPollingInterval) clearInterval(adminPollingInterval);
        adminPollingInterval = setInterval(loadTicketMessages, 3000);
    }

    function backToTicketList() {
        currentAdminTicketId = null;
        if (adminPollingInterval) clearInterval(adminPollingInterval);

        document.getElementById('ticketListView').classList.remove('hidden');
        document.getElementById('ticketChatView').classList.add('hidden');
        document.getElementById('backToListBtn').classList.add('hidden');
        document.getElementById('headerTitle').textContent = 'Support Tickets';
        document.getElementById('headerSubtitle').textContent = '{{ $openTickets->count() }} tiket aktif';
    }

    async function loadTicketMessages() {
        if (!currentAdminTicketId) return;

        try {
            const response = await fetch(`/support/messages/${currentAdminTicketId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
            });

            const data = await response.json();
            const container = document.getElementById('adminChatMessages');

            if (data.messages && data.messages.length > 0) {
                let html = '';
                data.messages.forEach(msg => {
                    const isAdmin = msg.is_from_admin;
                    const time = new Date(msg.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

                    if (isAdmin) {
                        html += `
                            <div class="flex items-start gap-2 justify-end">
                                <div class="bg-green-500/30 rounded-xl rounded-tr-none px-3 py-2 max-w-[80%]">
                                    <p class="text-white text-sm">${escapeAdminHtml(msg.message)}</p>
                                    <p class="text-white/40 text-xs mt-1">${time}</p>
                                </div>
                            </div>
                        `;
                    } else {
                        html += `
                            <div class="flex items-start gap-2">
                                ${msg.sender?.avatar
                                ? `<img src="${msg.sender.avatar}" alt="Avatar" class="w-7 h-7 rounded-full object-cover flex-shrink-0">`
                                : `<img src="https://ui-avatars.com/api/?name=${encodeURIComponent(msg.sender?.name || 'User')}&background=3B82F6&color=fff&bold=true&size=28" alt="Avatar" class="w-7 h-7 rounded-full object-cover flex-shrink-0">`
                            }
                                <div class="bg-white/10 rounded-xl rounded-tl-none px-3 py-2 max-w-[80%]">
                                    <p class="text-white text-sm">${escapeAdminHtml(msg.message)}</p>
                                    <p class="text-white/40 text-xs mt-1">${time}</p>
                                </div>
                            </div>
                        `;
                    }
                });
                container.innerHTML = html;
                container.scrollTop = container.scrollHeight;
            }
        } catch (e) {
            console.error('Load messages error:', e);
        }
    }

    async function sendAdminReply() {
        const input = document.getElementById('adminReplyInput');
        const text = input.value.trim();

        if (!text || !currentAdminTicketId) return;

        input.value = '';

        // Optimistic update
        const container = document.getElementById('adminChatMessages');
        const time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        container.innerHTML += `
            <div class="flex items-start gap-2 justify-end">
                <div class="bg-green-500/30 rounded-xl rounded-tr-none px-3 py-2 max-w-[80%]">
                    <p class="text-white text-sm">${escapeAdminHtml(text)}</p>
                    <p class="text-white/40 text-xs mt-1">${time}</p>
                </div>
            </div>
        `;
        container.scrollTop = container.scrollHeight;

        try {
            await fetch(`/support/message`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ ticket_id: currentAdminTicketId, message: text }),
            });
        } catch (e) {
            console.error('Send reply error:', e);
        }
    }

    function escapeAdminHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
</script>