<x-layouts.lembaga>
    <div class="space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-white text-xl lg:text-2xl font-bold">Activity Log</h1>
            <p class="text-white/70 text-sm lg:text-base mt-1">Riwayat aktivitas terkait sertifikat dan akun Anda</p>
        </div>

        <!-- Activity Log Table -->
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="p-4 border-b border-gray-200">
                <h2 class="text-gray-800 font-bold flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    Riwayat Aktivitas
                </h2>
            </div>

            @if($logs->isEmpty())
                <div class="p-8 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <p class="text-gray-500 text-sm">Belum ada aktivitas yang tercatat.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Waktu</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tipe</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Deskripsi</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Detail</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($logs as $log)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">
                                        <div class="font-medium">{{ $log->created_at->format('d M Y') }}</div>
                                        <div class="text-gray-400 text-xs">{{ $log->created_at->format('H:i:s') }}</div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        @php
                                            $typeColors = [
                                                'create_certificate' => 'bg-green-100 text-green-800',
                                                'verify_certificate' => 'bg-blue-100 text-blue-800',
                                                'revoke_certificate' => 'bg-red-100 text-red-800',
                                                'reactivate_certificate' => 'bg-purple-100 text-purple-800',
                                                'fraud_report' => 'bg-orange-100 text-orange-800',
                                                'cta_lead' => 'bg-cyan-100 text-cyan-800',
                                            ];
                                            $colorClass = $typeColors[$log->type] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $colorClass }}">
                                            {{ ucwords(str_replace('_', ' ', $log->type)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-700 max-w-xs truncate">
                                        {{ $log->description }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-400">
                                        @if($log->properties)
                                            <button onclick="showDetails('{{ $log->id }}')"
                                                class="text-blue-600 hover:text-blue-800 text-xs underline">
                                                Lihat Detail
                                            </button>
                                            <div id="details-{{ $log->id }}"
                                                class="hidden mt-2 text-xs bg-gray-50 p-2 rounded max-w-xs overflow-auto">
                                                <pre
                                                    class="text-gray-600">{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}</pre>
                                            </div>
                                        @else
                                            <span class="text-gray-300">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-4 border-t border-gray-200">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            function showDetails(id) {
                const el = document.getElementById('details-' + id);
                el.classList.toggle('hidden');
            }
        </script>
    @endpush
</x-layouts.lembaga>