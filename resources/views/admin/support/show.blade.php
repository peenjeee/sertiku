{{-- resources/views/admin/support/show.blade.php --}}
<x-layouts.admin title="Chat Support – {{ $ticket->subject }}">

    <div class="max-w-4xl mx-auto">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6 animate-fade-in-up">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.support.index') }}"
                    class="p-2 rounded-lg bg-white/10 text-white/70 hover:bg-white/20 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-white">{{ $ticket->subject }}</h1>
                    <p class="text-white/50 text-sm">
                        {{ $ticket->user->name }} •
                        @if($ticket->status === 'open')
                            <span class="text-green-400">Open</span>
                        @elseif($ticket->status === 'in_progress')
                            <span class="text-yellow-400">In Progress</span>
                        @else
                            <span class="text-gray-400">Closed</span>
                        @endif
                    </p>
                </div>
            </div>
            @if($ticket->status !== 'closed')
                <form action="{{ route('support.ticket.close', $ticket) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-red-500/20 text-red-400 text-sm hover:bg-red-500/30 transition">
                        Tutup Tiket
                    </button>
                </form>
            @endif
        </div>

        {{-- Chat Container --}}
        <div class="glass-card rounded-2xl overflow-hidden animate-fade-in-up">
            {{-- Messages --}}
            <div class="h-[500px] overflow-y-auto p-6 space-y-4" id="chatMessages">
                @foreach($ticket->messages as $message)
                    <div class="flex {{ $message->is_from_admin ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[70%]">
                            <div class="flex items-center gap-2 mb-1 {{ $message->is_from_admin ? 'justify-end' : '' }}">
                                @if(!$message->is_from_admin)
                                    <div
                                        class="w-6 h-6 rounded-full bg-[#3B82F6] flex items-center justify-center overflow-hidden">
                                        @if($message->sender->avatar && (str_starts_with($message->sender->avatar, '/storage/') || str_starts_with($message->sender->avatar, 'http')))
                                            <img src="{{ $message->sender->avatar }}" alt="Avatar"
                                                class="w-full h-full object-cover">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($message->sender->name ?? 'U') }}&email={{ urlencode($message->sender->email ?? '') }}&background=3B82F6&color=fff&bold=true&size=24"
                                                alt="Avatar" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                @endif
                                <span class="text-gray-500 text-xs">
                                    {{ $message->sender->name ?? 'User' }}
                                </span>
                                @if($message->is_from_admin)
                                    <div
                                        class="w-6 h-6 rounded-full bg-blue-600 flex items-center justify-center overflow-hidden">
                                        @if($message->sender->avatar && (str_starts_with($message->sender->avatar, '/storage/') || str_starts_with($message->sender->avatar, 'http')))
                                            <img src="{{ $message->sender->avatar }}" alt="Avatar"
                                                class="w-full h-full object-cover">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($message->sender->name ?? 'A') }}&email={{ urlencode($message->sender->email ?? '') }}&background=2563EB&color=fff&bold=true&size=24"
                                                alt="Avatar" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div
                                class="rounded-2xl px-4 py-3 {{ $message->is_from_admin ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-900' }}">
                                <p class="text-sm">{{ $message->message }}</p>
                            </div>
                            <p class="text-gray-400 text-xs mt-1 {{ $message->is_from_admin ? 'text-right' : '' }}">
                                {{ $message->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Reply Form --}}
            @if($ticket->status !== 'closed')
                <form action="{{ route('admin.support.reply', $ticket) }}" method="POST"
                    class="p-4 border-t border-gray-100">
                    @csrf
                    <div class="flex gap-3">
                        <input type="text" name="message" placeholder="Ketik balasan..." required
                            class="flex-1 px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 transition">
                        <button type="submit"
                            class="px-6 py-3 rounded-xl bg-blue-600 text-white font-medium hover:bg-blue-700 transition flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </button>
                    </div>
                </form>
            @else
                <div class="p-4 border-t border-gray-100 text-center text-gray-500">
                    Tiket ini sudah ditutup
                </div>
            @endif
        </div>
    </div>

    <script>
        // Auto scroll to bottom
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(() => {
                const messageContainer = document.getElementById('chatMessages');
                if (messageContainer) {
                    messageContainer.scrollTop = messageContainer.scrollHeight;
                }
            }, 100);
        });
    </script>

</x-layouts.admin>