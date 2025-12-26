{{-- resources/views/master/logs.blade.php --}}
<x-layouts.master title="Log Aktivitas – Master SertiKu">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6 animate-fade-in-up">
        <div>
            <h1 class="text-2xl lg:text-3xl font-bold text-white">Log Aktivitas</h1>
            <p class="text-white/60 mt-1">Pantau semua aktivitas di sistem</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="glass-card rounded-2xl p-4 mb-6 animate-fade-in-up">
        <form method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <select name="action" style="background-color: #1a1a2e !important; color: white !important;"
                    class="w-full px-4 py-2 rounded-xl border border-white/20 text-sm focus:outline-none focus:border-purple-500">
                    <option value="" style="background-color: #1a1a2e !important; color: white !important;">Semua
                        Aktivitas</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}
                            style="background-color: #1a1a2e !important; color: white !important;">
                            {{ ucwords(str_replace('_', ' ', $action)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <input type="date" name="date" value="{{ request('date') }}"
                    class="w-full px-4 py-2 rounded-xl bg-white/10 border border-white/10 text-white text-sm focus:outline-none focus:border-purple-500">
            </div>
            <button type="submit"
                class="px-6 py-2 rounded-xl bg-purple-500 text-white text-sm hover:bg-purple-600 transition">
                Filter
            </button>
            @if(request()->hasAny(['action', 'date', 'user_id']))
                <a href="{{ route('master.logs') }}"
                    class="px-6 py-2 rounded-xl bg-white/10 text-white/70 text-sm hover:bg-white/20 transition">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Logs Table --}}
    <div class="glass-card rounded-2xl overflow-hidden animate-fade-in-up">
        @if($logs->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="text-left text-white/50 text-xs font-medium py-4 px-5">Waktu</th>
                            <th class="text-left text-white/50 text-xs font-medium py-4 px-5">User</th>
                            <th class="text-left text-white/50 text-xs font-medium py-4 px-5">Aktivitas</th>
                            <th class="text-left text-white/50 text-xs font-medium py-4 px-5">Deskripsi</th>
                            <th class="text-left text-white/50 text-xs font-medium py-4 px-5">IP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($logs as $log)
                            <tr class="hover:bg-white/5 transition">
                                <td class="py-4 px-5">
                                    <span class="text-white/70 text-sm">{{ $log->created_at->format('d M Y') }}</span>
                                    <br>
                                    <span class="text-white/40 text-xs">{{ $log->created_at->format('H:i:s') }}</span>
                                </td>
                                <td class="py-4 px-5">
                                    @if($log->user)
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center overflow-hidden">
                                                @if($log->user->avatar && (str_starts_with($log->user->avatar, '/storage/') || str_starts_with($log->user->avatar, 'http')))
                                                    <img src="{{ $log->user->avatar }}" alt="Avatar" class="w-full h-full object-cover">
                                                @else
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($log->user->name) }}&email={{ urlencode($log->user->email) }}&background=8B5CF6&color=fff&bold=true&size=32"
                                                        alt="Avatar" class="w-full h-full object-cover">
                                                @endif
                                            </div>
                                            <div>
                                                <p class="text-white text-sm">{{ $log->user->name }}</p>
                                                <p class="text-white/40 text-xs">{{ $log->user->email }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-white/40 text-sm">System</span>
                                    @endif
                                </td>
                                <td class="py-4 px-5">
                                    <span
                                        class="px-2 py-1 rounded-lg bg-{{ $log->color }}-500/20 text-{{ $log->color }}-400 text-xs">
                                        {{ $log->icon }} {{ ucwords(str_replace('_', ' ', $log->action)) }}
                                    </span>
                                </td>
                                <td class="py-4 px-5">
                                    <span class="text-white/70 text-sm">{{ $log->description ?? '-' }}</span>
                                </td>
                                <td class="py-4 px-5">
                                    <span class="text-white/50 text-xs font-mono">{{ $log->ip_address ?? '-' }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-white/10">
                {{ $logs->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-white/5 flex items-center justify-center">
                    <svg class="w-10 h-10 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Belum Ada Log</h3>
                <p class="text-white/50">Aktivitas sistem akan muncul di sini</p>
            </div>
        @endif
    </div>

</x-layouts.master>