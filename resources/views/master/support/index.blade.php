{{-- resources/views/master/support/index.blade.php --}}
<x-layouts.master title="Support Tickets – Master SertiKu">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6 animate-fade-in-up">
        <div>
            <h1 class="text-2xl lg:text-3xl font-bold text-white">Support Tickets</h1>
            <p class="text-white/60 mt-1">Kelola tiket bantuan dari pengguna</p>
        </div>
        <div class="mt-4 lg:mt-0 flex gap-2">
            <a href="{{ route('master.support', ['status' => 'all']) }}"
                class="px-4 py-2 rounded-lg text-sm {{ !request('status') || request('status') === 'all' ? 'bg-purple-500 text-white' : 'bg-white/10 text-white/70 hover:bg-white/20' }} transition">
                Semua
            </a>
            <a href="{{ route('master.support', ['status' => 'open']) }}"
                class="px-4 py-2 rounded-lg text-sm {{ request('status') === 'open' ? 'bg-green-500 text-white' : 'bg-white/10 text-white/70 hover:bg-white/20' }} transition">
                Open
            </a>
            <a href="{{ route('master.support', ['status' => 'in_progress']) }}"
                class="px-4 py-2 rounded-lg text-sm {{ request('status') === 'in_progress' ? 'bg-yellow-500 text-white' : 'bg-white/10 text-white/70 hover:bg-white/20' }} transition">
                In Progress
            </a>
            <a href="{{ route('master.support', ['status' => 'closed']) }}"
                class="px-4 py-2 rounded-lg text-sm {{ request('status') === 'closed' ? 'bg-gray-500 text-white' : 'bg-white/10 text-white/70 hover:bg-white/20' }} transition">
                Closed
            </a>
        </div>
    </div>

    {{-- Tickets List --}}
    <div class="glass-card rounded-2xl overflow-hidden animate-fade-in-up">
        @if($tickets->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="text-left text-white/50 text-xs font-medium py-4 px-5">User</th>
                            <th class="text-left text-white/50 text-xs font-medium py-4 px-5">Subject</th>
                            <th class="text-left text-white/50 text-xs font-medium py-4 px-5">Status</th>
                            <th class="text-left text-white/50 text-xs font-medium py-4 px-5">Assigned</th>
                            <th class="text-left text-white/50 text-xs font-medium py-4 px-5">Last Update</th>
                            <th class="text-left text-white/50 text-xs font-medium py-4 px-5">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($tickets as $ticket)
                            <tr class="hover:bg-white/5 transition">
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-[#8B5CF6] flex items-center justify-center overflow-hidden">
                                            @if($ticket->user->avatar && (str_starts_with($ticket->user->avatar, '/storage/') || str_starts_with($ticket->user->avatar, 'http')))
                                                <img src="{{ $ticket->user->avatar }}" alt="Avatar"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($ticket->user->name ?? 'U') }}&email={{ urlencode($ticket->user->email ?? '') }}&background=8B5CF6&color=fff&bold=true&size=40"
                                                    alt="Avatar" class="w-full h-full object-cover">
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-white font-medium text-sm">{{ $ticket->user->name }}</p>
                                            <p class="text-white/50 text-xs">{{ $ticket->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-5">
                                    <p class="text-white text-sm">{{ $ticket->subject }}</p>
                                    @if($ticket->unread_count > 0)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full bg-red-500 text-white text-xs mt-1">
                                            {{ $ticket->unread_count }} pesan baru
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-5">
                                    @if($ticket->status === 'open')
                                        <span class="inline-block whitespace-nowrap px-3 py-1 rounded-full bg-green-500/20 text-green-400 text-xs">Open</span>
                                    @elseif($ticket->status === 'in_progress')
                                        <span class="inline-block whitespace-nowrap px-3 py-1 rounded-full bg-yellow-500/20 text-yellow-400 text-xs">In Progress</span>
                                    @else
                                        <span class="inline-block whitespace-nowrap px-3 py-1 rounded-full bg-gray-500/20 text-gray-400 text-xs">Closed</span>
                                    @endif
                                </td>
                                <td class="py-4 px-5">
                                    <span class="text-white/70 text-sm">{{ $ticket->assignedAdmin->name ?? '-' }}</span>
                                </td>
                                <td class="py-4 px-5">
                                    <span
                                        class="text-white/50 text-sm">{{ $ticket->last_message_at?->diffForHumans() ?? '-' }}</span>
                                </td>
                                <td class="py-4 px-5">
                                    <a href="{{ route('master.support.show', $ticket) }}"
                                        class="inline-flex items-center gap-1.5 whitespace-nowrap px-4 py-2 rounded-lg bg-purple-500/20 text-purple-400 text-sm hover:bg-purple-500/30 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                        Buka Chat
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-white/10">
                {{ $tickets->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-white/5 flex items-center justify-center">
                    <svg class="w-10 h-10 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Tidak ada tiket</h3>
                <p class="text-white/50">Belum ada tiket support dari pengguna</p>
            </div>
        @endif
    </div>

</x-layouts.master>