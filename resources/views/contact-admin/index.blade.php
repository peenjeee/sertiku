{{-- resources/views/contact-admin/index.blade.php --}}
@php
    $layoutComponent = $layout === 'lembaga' ? 'layouts.lembaga' : 'layouts.user';
@endphp

<x-dynamic-component :component="$layoutComponent" :title="'Hubungi Admin – SertiKu'">

    <div class="max-w-4xl mx-auto">
        {{-- Header --}}
        <div class="mb-6 animate-fade-in-up">
            <h1 class="text-2xl lg:text-3xl font-bold text-white">Hubungi Admin</h1>
            <p class="text-white/60 mt-1">Buat tiket support atau lanjutkan percakapan dengan admin</p>
        </div>

        @if(session('success'))
            <div
                class="mb-6 p-4 rounded-xl bg-green-500/20 border border-green-500/30 text-green-400 text-sm animate-fade-in-up">
                {{ session('success') }}
            </div>
        @endif

        {{-- Create New Ticket Form --}}
        <div class="rounded-2xl p-6 mb-6 animate-fade-in-up"
            style="background: #0F172A; border: 1px solid rgba(255,255,255,0.1);">
            <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Buat Tiket Baru
            </h2>
            <form action="{{ route('contact.admin.create') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-white/70 text-sm mb-2">Subjek</label>
                    <input type="text" name="subject" placeholder="Contoh: Masalah upload sertifikat" required
                        class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/40 focus:outline-none focus:border-green-500 transition"
                        value="{{ old('subject') }}">
                    @error('subject')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-white/70 text-sm mb-2">Pesan</label>
                    <textarea name="message" rows="4" placeholder="Jelaskan masalah atau pertanyaan Anda..." required
                        class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/40 focus:outline-none focus:border-green-500 transition resize-none">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                    class="w-full sm:w-auto px-6 py-3 rounded-xl bg-[#10B981] text-white font-medium hover:bg-[#059669] transition">
                    Kirim Tiket
                </button>
            </form>
        </div>

        {{-- My Tickets List --}}
        <div class="rounded-2xl overflow-hidden animate-fade-in-up"
            style="background: #0F172A; border: 1px solid rgba(255,255,255,0.1);">
            <div class="p-4 border-b border-white/10">
                <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    Tiket Saya
                </h2>
            </div>

            @if($tickets->count() > 0)
                <div class="divide-y divide-white/10">
                    @foreach($tickets as $ticket)
                        <a href="{{ route('contact.admin.show', $ticket) }}" class="block p-4 hover:bg-white/5 transition">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-white/50 text-xs">Tiket #{{ $ticket->id }}</span>
                                        @if($ticket->status === 'open')
                                            <span
                                                class="px-2 py-0.5 rounded-full bg-green-500/20 text-green-400 text-xs">Open</span>
                                        @elseif($ticket->status === 'in_progress')
                                            <span class="px-2 py-0.5 rounded-full bg-yellow-500/20 text-yellow-400 text-xs">In
                                                Progress</span>
                                        @else
                                            <span
                                                class="px-2 py-0.5 rounded-full bg-gray-500/20 text-gray-400 text-xs">Closed</span>
                                        @endif
                                        @if($ticket->unread_count > 0)
                                            <span
                                                class="px-2 py-0.5 rounded-full bg-red-500 text-white text-xs">{{ $ticket->unread_count }}
                                                baru</span>
                                        @endif
                                    </div>
                                    <p class="text-white font-medium text-sm truncate">{{ $ticket->subject }}</p>
                                    <p class="text-white/40 text-xs mt-1">
                                        {{ $ticket->last_message_at?->diffForHumans() ?? $ticket->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <svg class="w-5 h-5 text-white/30 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-white/5 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Belum Ada Tiket</h3>
                    <p class="text-white/50 text-sm">Buat tiket baru di atas untuk menghubungi admin</p>
                </div>
            @endif
        </div>
    </div>

</x-dynamic-component>