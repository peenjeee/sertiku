@php
    $layout = Auth::user()->account_type === 'lembaga' || Auth::user()->account_type === 'institution' ? 'layouts.lembaga' : 'layouts.user';
@endphp

<x-dynamic-component :component="$layout" title="{{ isset($existingFeedback) ? 'Feedback Anda' : 'Beri Feedback' }}">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white mb-2">
                {{ isset($existingFeedback) ? 'Feedback Anda' : 'Beri Masukan' }}
            </h1>
            <p class="text-white/60">
                {{ isset($existingFeedback) ? 'Berikut adalah masukan yang telah Anda kirimkan.' : 'Pendapat Anda sangat berharga untuk kemajuan SertiKu.' }}
            </p>
        </div>

        <div class="bg-white/5 border border-white/10 rounded-2xl p-6 md:p-8">
            @if(isset($existingFeedback))
                <div
                    class="mb-6 bg-blue-500/10 border border-blue-500/20 text-blue-200 px-4 py-3 rounded-xl flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm">Anda sudah mengirimkan feedback. Data ini bersifat permanen.</p>
                </div>
            @endif

            <form action="{{ route('feedback.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- User Info (Read Only) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-white/80 mb-2">Nama</label>
                        <input type="text" value="{{ $existingFeedback->name ?? Auth::user()->name }}" readonly
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white/50 focus:outline-none cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-white/80 mb-2">Institusi (Opsional)</label>
                        <input type="text"
                            value="{{ $existingFeedback->institution ?? Auth::user()->institution_name ?? Auth::user()->user_institution ?? '-' }}"
                            readonly
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white/50 focus:outline-none cursor-not-allowed">
                    </div>
                </div>

                {{-- Rating --}}
                <div>
                    <label class="block text-sm font-medium text-white/80 mb-4">
                        {{ isset($existingFeedback) ? 'Rating yang Anda berikan:' : 'Seberapa puas Anda dengan SertiKu?' }}
                    </label>

                    @if(isset($existingFeedback))
                        <div class="flex gap-2">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-10 h-10 {{ $i <= $existingFeedback->rating ? 'text-yellow-400 fill-current' : 'text-white/20 fill-current' }}"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                </svg>
                            @endfor
                        </div>
                    @else
                        <div class="flex flex-row-reverse justify-end gap-2 group">
                            @for($i = 5; $i >= 1; $i--)
                                <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}" class="peer hidden"
                                    required>
                                <label for="star{{ $i }}"
                                    class="cursor-pointer text-white/20 peer-checked:text-yellow-400 hover:text-yellow-400 peer-hover:text-yellow-400 transition-colors">
                                    <svg class="w-10 h-10 fill-current" viewBox="0 0 24 24">
                                        <path
                                            d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                    </svg>
                                </label>
                            @endfor
                        </div>
                        @error('rating')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                {{-- Content --}}
                <div>
                    <label for="content" class="block text-sm font-medium text-white/80 mb-2">Pesan / Masukan</label>
                    <textarea name="content" id="content" rows="4" {{ isset($existingFeedback) ? 'readonly' : 'required' }}
                        placeholder="{{ isset($existingFeedback) ? '' : 'Tulis pengalaman atau saran Anda di sini...' }}"
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-white/20 focus:outline-none {{ isset($existingFeedback) ? 'cursor-not-allowed text-white/70' : 'focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition' }}">{{ $existingFeedback->content ?? '' }}</textarea>

                    @if(!isset($existingFeedback))
                        @error('content')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                {{-- Submit Button --}}
                @if(!isset($existingFeedback))
                    <div class="flex justify-end pt-4">
                        <button type="submit"
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/20 transition transform hover:-translate-y-1">
                            Kirim Feedback
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</x-dynamic-component>