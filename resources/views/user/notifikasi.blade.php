{{-- resources/views/user/notifikasi.blade.php --}}
<x-layouts.user title="Notifikasi – SertiKu">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6 animate-fade-in-up">
        <div>
            <h1 class="text-2xl lg:text-3xl font-bold text-white">Notifikasi</h1>
            <p class="text-white/60 mt-1">Semua pemberitahuan aktivitas akun Anda</p>
        </div>
        @if($notifications->where('read_at', null)->count() > 0)
        <form action="{{ route('user.notifikasi.readAll') }}" method="POST">
            @csrf
            <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white/10 border border-white/20 text-white/70 text-sm hover:bg-white/20 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Tandai Semua Dibaca
            </button>
        </form>
        @endif
    </div>

    {{-- Notifications List --}}
    <div class="glass-card rounded-2xl p-5 lg:p-6 animate-fade-in-up">
        @if($notifications->count() > 0)
            <div class="space-y-3">
                @foreach($notifications as $notification)
                <div class="flex items-start gap-4 p-4 rounded-xl transition
                    {{ $notification->read_at ? 'bg-white/5' : 'bg-blue-500/10 border border-blue-500/20' }}">
                    {{-- Icon --}}
                    @php
                        $type = $notification->data['type'] ?? 'general';
                    @endphp
                    <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center
                        @if($type === 'certificate_received')
                            bg-green-500/20
                        @elseif($type === 'certificate_viewed')
                            bg-yellow-500/20
                        @elseif($type === 'certificate' || $type === 'verification')
                            bg-blue-500/20
                        @else
                            bg-purple-500/20
                        @endif
                    ">
                        @if($type === 'certificate_received')
                            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                        @elseif($type === 'certificate_viewed')
                            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        @elseif($type === 'certificate' || $type === 'verification')
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @else
                            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-white font-medium">{{ $notification->data['title'] ?? 'Notifikasi' }}</p>
                                <p class="text-white/50 text-sm mt-1">{{ $notification->data['subtitle'] ?? $notification->data['message'] ?? '' }}</p>
                            </div>
                            @if(!$notification->read_at)
                            <span class="w-2 h-2 rounded-full bg-blue-400 flex-shrink-0 mt-2"></span>
                            @endif
                        </div>
                        <div class="flex items-center gap-4 mt-2">
                            <p class="text-white/40 text-xs">{{ $notification->created_at->diffForHumans() }}</p>
                            @if(!$notification->read_at)
                            <form action="{{ route('user.notifikasi.read', $notification->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-xs text-blue-400 hover:text-blue-300">
                                    Tandai dibaca
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @else
            {{-- Empty State --}}
            <div class="text-center py-16">
                <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-white/5 flex items-center justify-center">
                    <svg class="w-10 h-10 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Tidak ada notifikasi</h3>
                <p class="text-white/50">Notifikasi akan muncul di sini saat ada aktivitas baru di akun Anda</p>
            </div>
        @endif
    </div>

</x-layouts.user>
