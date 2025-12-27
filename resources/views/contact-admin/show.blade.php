{{-- resources/views/contact-admin/show.blade.php --}}
@php
    $layoutComponent = $layout === 'lembaga' ? 'layouts.lembaga' : 'layouts.user';
@endphp

<x-dynamic-component :component="$layoutComponent" :title="'Tiket #' . $ticket->id . ' – SertiKu'">

    <div class="max-w-4xl mx-auto">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6 animate-fade-in-up">
            <div>
                <a href="{{ route('contact.admin') }}"
                    class="text-white/50 hover:text-white text-sm flex items-center gap-1 mb-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>
                <h1 class="text-xl lg:text-2xl font-bold text-white">{{ $ticket->subject }}</h1>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-white/50 text-sm">Tiket #{{ $ticket->id }}</span>
                    @if($ticket->status === 'open')
                        <span class="px-2 py-0.5 rounded-full bg-green-500/20 text-green-400 text-xs">Open</span>
                    @elseif($ticket->status === 'in_progress')
                        <span class="px-2 py-0.5 rounded-full bg-yellow-500/20 text-yellow-400 text-xs">In Progress</span>
                    @else
                        <span class="px-2 py-0.5 rounded-full bg-gray-500/20 text-gray-400 text-xs">Closed</span>
                    @endif
                </div>
            </div>
            @if($ticket->assignedAdmin)
                <div class="text-right">
                    <p class="text-white/40 text-xs">Ditangani oleh</p>
                    <p class="text-white text-sm">{{ $ticket->assignedAdmin->name }}</p>
                </div>
            @endif
        </div>



        {{-- Chat Messages --}}
        <div class="rounded-2xl overflow-hidden animate-fade-in-up mb-6"
            style="background: linear-gradient(180deg, #0F172A 0%, #1E293B 100%); border: 1px solid rgba(255,255,255,0.1);">
            <div class="p-4 border-b border-white/10 bg-gradient-to-r from-green-600/20 to-emerald-600/20">
                <p class="text-white font-medium flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    Percakapan
                </p>
            </div>

            <div class="p-4 space-y-4 max-h-96 overflow-y-auto" id="chatContainer">
                @foreach($ticket->messages as $message)
                    {{-- Logic: User messages (not from admin) on Right (flex-row-reverse), Admin on Left --}}
                    @php
                        $isUserMessage = !$message->is_from_admin;
                        $senderName = $message->sender->name ?? ($message->is_from_admin ? 'Admin' : 'User');
                        $senderEmail = $message->sender->email ?? 'admin@sertiku.com';
                        $senderAvatar = $message->sender->avatar ?? null;
                        // Admin: Green, User: Blue
                        $avatarBg = $message->is_from_admin ? 'bg-gradient-to-br from-green-500 to-emerald-600' : 'bg-gradient-to-br from-blue-500 to-indigo-600';
                        $bgColor = $message->is_from_admin ? '10B981' : '3B82F6';
                    @endphp

                    <div class="flex items-start gap-3 {{ $isUserMessage ? 'flex-row-reverse' : '' }}">
                        {{-- Avatar --}}
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0
                                {{ $avatarBg }} overflow-hidden">
                            @if($senderAvatar && (str_starts_with($senderAvatar, '/storage/') || str_starts_with($senderAvatar, 'http')))
                                <img src="{{ $senderAvatar }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($senderName) }}&email={{ urlencode($senderEmail) }}&background={{ $bgColor }}&color=fff&bold=true&size=32"
                                    alt="Avatar" class="w-full h-full object-cover">
                            @endif
                        </div>

                        {{-- Message Bubble --}}
                        <div class="max-w-[75%] {{ $isUserMessage ? 'text-right' : '' }}">
                            <div
                                class="px-4 py-3 rounded-2xl {{ $isUserMessage ? 'bg-blue-500/20 rounded-tr-none' : 'bg-white/10 rounded-tl-none' }}">
                                <p class="text-white text-sm whitespace-pre-wrap">{{ $message->message }}</p>
                            </div>
                            <div class="flex items-center gap-2 mt-1 {{ $isUserMessage ? 'justify-end' : '' }}">
                                <span class="text-white/40 text-xs">
                                    {{ $message->sender->name ?? 'Admin' }}
                                </span>
                                <span class="text-white/30 text-xs">•</span>
                                <span class="text-white/40 text-xs">
                                    {{ $message->created_at->format('d M Y, H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Reply Form --}}
        @if($ticket->status !== 'closed')
            <div class="rounded-2xl p-4 animate-fade-in-up"
                style="background: linear-gradient(180deg, #0F172A 0%, #1E293B 100%); border: 1px solid rgba(255,255,255,0.1);">
                <form action="{{ route('contact.admin.send', $ticket) }}" method="POST" class="flex gap-3">
                    @csrf
                    <input type="text" name="message" placeholder="Ketik pesan..." required
                        class="flex-1 px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/40 focus:outline-none focus:border-green-500 transition">
                    <button type="submit"
                        class="px-6 py-3 rounded-xl bg-gradient-to-r from-green-500 to-emerald-600 text-white font-medium hover:opacity-90 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        <span class="hidden sm:inline">Kirim</span>
                    </button>
                </form>
            </div>
        @else
            <div class="rounded-2xl p-4 text-center animate-fade-in-up"
                style="background: linear-gradient(180deg, #0F172A 0%, #1E293B 100%); border: 1px solid rgba(255,255,255,0.1);">
                <p class="text-white/50 text-sm">Tiket ini sudah ditutup. Buat tiket baru jika ada pertanyaan lain.</p>
                <a href="{{ route('contact.admin') }}"
                    class="inline-block mt-3 px-4 py-2 rounded-lg bg-white/10 text-white text-sm hover:bg-white/20 transition">
                    Buat Tiket Baru
                </a>
            </div>
        @endif
    </div>

    <script>
        // Auto scroll to bottom
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('chatContainer');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        });
    </script>

</x-dynamic-component>