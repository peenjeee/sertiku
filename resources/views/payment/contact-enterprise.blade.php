<x-layouts.app title="Hubungi Sales Enterprise - SertiKu">

    <section class="mx-auto max-w-2xl px-4 py-16 lg:px-0">
        <div class="rounded-[28px] border border-[rgba(255,255,255,0.14)] bg-[rgba(15,23,42,0.9)] p-8">

            {{-- Header --}}
            <div class="text-center">
                <div
                    class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-[#8B5CF6] to-[#EC4899]">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h1 class="mt-4 text-2xl font-semibold text-white">Paket Enterprise</h1>
                <p class="mt-2 text-sm text-[rgba(190,219,255,0.7)]">
                    Solusi custom untuk universitas dan korporasi besar.<br>
                    Tim kami akan menghubungi Anda dalam 1x24 jam.
                </p>
            </div>

            {{-- Success Message --}}
            @if(session('success'))
                <div
                    class="mt-6 rounded-[12px] bg-[#05DF72]/20 border border-[#05DF72]/30 p-4 text-center text-sm text-[#05DF72]">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('contact.enterprise.send') }}" method="POST" class="mt-8 space-y-5">
                @csrf

                {{-- Nama --}}
                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-[rgba(219,234,254,0.9)]">
                        Nama Lengkap <span class="text-red-400">*</span>
                    </label>
                    <input type="text" id="name" name="name" required value="{{ old('name') }}"
                        class="w-full rounded-[12px] border border-[rgba(255,255,255,0.12)] bg-[rgba(15,23,42,0.6)] px-4 py-3 text-sm text-white placeholder:text-[rgba(190,219,255,0.5)] focus:border-[#3B82F6] focus:outline-none focus:ring-2 focus:ring-[#3B82F6]/30"
                        placeholder="Masukkan nama lengkap">
                    @error('name')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="mb-2 block text-sm font-medium text-[rgba(219,234,254,0.9)]">
                        Email <span class="text-red-400">*</span>
                    </label>
                    <input type="email" id="email" name="email" required value="{{ old('email') }}"
                        class="w-full rounded-[12px] border border-[rgba(255,255,255,0.12)] bg-[rgba(15,23,42,0.6)] px-4 py-3 text-sm text-white placeholder:text-[rgba(190,219,255,0.5)] focus:border-[#3B82F6] focus:outline-none focus:ring-2 focus:ring-[#3B82F6]/30"
                        placeholder="email@institusi.ac.id">
                    @error('email')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Telepon --}}
                <div>
                    <label for="phone" class="mb-2 block text-sm font-medium text-[rgba(219,234,254,0.9)]">
                        Nomor Telepon <span class="text-red-400">*</span>
                    </label>
                    <input type="tel" id="phone" name="phone" required value="{{ old('phone') }}"
                        class="w-full rounded-[12px] border border-[rgba(255,255,255,0.12)] bg-[rgba(15,23,42,0.6)] px-4 py-3 text-sm text-white placeholder:text-[rgba(190,219,255,0.5)] focus:border-[#3B82F6] focus:outline-none focus:ring-2 focus:ring-[#3B82F6]/30"
                        placeholder="08xxxxxxxxxx">
                    @error('phone')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Institusi --}}
                <div>
                    <label for="institution" class="mb-2 block text-sm font-medium text-[rgba(219,234,254,0.9)]">
                        Nama Institusi <span class="text-red-400">*</span>
                    </label>
                    <input type="text" id="institution" name="institution" required value="{{ old('institution') }}"
                        class="w-full rounded-[12px] border border-[rgba(255,255,255,0.12)] bg-[rgba(15,23,42,0.6)] px-4 py-3 text-sm text-white placeholder:text-[rgba(190,219,255,0.5)] focus:border-[#3B82F6] focus:outline-none focus:ring-2 focus:ring-[#3B82F6]/30"
                        placeholder="Universitas / Perusahaan">
                    @error('institution')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Pesan --}}
                <div>
                    <label for="message" class="mb-2 block text-sm font-medium text-[rgba(219,234,254,0.9)]">
                        Kebutuhan Anda <span class="text-red-400">*</span>
                    </label>
                    <textarea id="message" name="message" rows="4" required
                        class="w-full rounded-[12px] border border-[rgba(255,255,255,0.12)] bg-[rgba(15,23,42,0.6)] px-4 py-3 text-sm text-white placeholder:text-[rgba(190,219,255,0.5)] focus:border-[#3B82F6] focus:outline-none focus:ring-2 focus:ring-[#3B82F6]/30 resize-none"
                        placeholder="Jelaskan kebutuhan sertifikasi digital Anda...">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full rounded-[12px] bg-gradient-to-b from-[#1E3A8F] to-[#3B82F6] px-6 py-3.5 text-sm font-semibold text-white shadow-[0_20px_40px_-20px_rgba(37,99,235,0.9)] hover:brightness-110 transition">
                    Kirim Permintaan
                </button>
            </form>

            {{-- Features --}}
            <div class="mt-8 border-t border-[rgba(255,255,255,0.1)] pt-8">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-[rgba(190,219,255,0.7)]">
                    Fitur Enterprise
                </h3>
                <ul class="mt-4 grid gap-3 text-xs text-[rgba(219,234,254,0.9)] sm:grid-cols-2">
                    <li class="flex items-center gap-2">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="none">
                            <path
                                d="M18.1673 8.33332C18.5479 10.2011 18.2767 12.1428 17.3989 13.8348C16.5211 15.5268 15.0897 16.8667 13.3436 17.6311C11.5975 18.3955 9.64203 18.5381 7.80342 18.0353C5.96482 17.5325 4.35417 16.4145 3.24007 14.8678C2.12597 13.3212 1.57577 11.4394 1.68123 9.53615C1.78668 7.63294 2.5414 5.8234 3.81955 4.4093C5.09769 2.9952 6.82199 2.06202 8.70489 1.76537C10.5878 1.46872 12.5155 1.82654 14.1665 2.77916"
                                stroke="#05DF72" stroke-width="1.66667" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M7.5 9.16659L10 11.6666L18.3333 3.33325" stroke="#05DF72" stroke-width="1.66667"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        10.000 sertifikat/bulan
                    </li>
                    <li class="flex items-center gap-2">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="none">
                            <path
                                d="M18.1673 8.33332C18.5479 10.2011 18.2767 12.1428 17.3989 13.8348C16.5211 15.5268 15.0897 16.8667 13.3436 17.6311C11.5975 18.3955 9.64203 18.5381 7.80342 18.0353C5.96482 17.5325 4.35417 16.4145 3.24007 14.8678C2.12597 13.3212 1.57577 11.4394 1.68123 9.53615C1.78668 7.63294 2.5414 5.8234 3.81955 4.4093C5.09769 2.9952 6.82199 2.06202 8.70489 1.76537C10.5878 1.46872 12.5155 1.82654 14.1665 2.77916"
                                stroke="#05DF72" stroke-width="1.66667" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M7.5 9.16659L10 11.6666L18.3333 3.33325" stroke="#05DF72" stroke-width="1.66667"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        10.000 Blockchain/bulan
                    </li>
                    <li class="flex items-center gap-2">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="none">
                            <path
                                d="M18.1673 8.33332C18.5479 10.2011 18.2767 12.1428 17.3989 13.8348C16.5211 15.5268 15.0897 16.8667 13.3436 17.6311C11.5975 18.3955 9.64203 18.5381 7.80342 18.0353C5.96482 17.5325 4.35417 16.4145 3.24007 14.8678C2.12597 13.3212 1.57577 11.4394 1.68123 9.53615C1.78668 7.63294 2.5414 5.8234 3.81955 4.4093C5.09769 2.9952 6.82199 2.06202 8.70489 1.76537C10.5878 1.46872 12.5155 1.82654 14.1665 2.77916"
                                stroke="#05DF72" stroke-width="1.66667" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M7.5 9.16659L10 11.6666L18.3333 3.33325" stroke="#05DF72" stroke-width="1.66667"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        10.000 IPFS/bulan
                    </li>
                    <li class="flex items-center gap-2">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="none">
                            <path
                                d="M18.1673 8.33332C18.5479 10.2011 18.2767 12.1428 17.3989 13.8348C16.5211 15.5268 15.0897 16.8667 13.3436 17.6311C11.5975 18.3955 9.64203 18.5381 7.80342 18.0353C5.96482 17.5325 4.35417 16.4145 3.24007 14.8678C2.12597 13.3212 1.57577 11.4394 1.68123 9.53615C1.78668 7.63294 2.5414 5.8234 3.81955 4.4093C5.09769 2.9952 6.82199 2.06202 8.70489 1.76537C10.5878 1.46872 12.5155 1.82654 14.1665 2.77916"
                                stroke="#05DF72" stroke-width="1.66667" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M7.5 9.16659L10 11.6666L18.3333 3.33325" stroke="#05DF72" stroke-width="1.66667"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Custom integration
                    </li>
                    <li class="flex items-center gap-2">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="none">
                            <path
                                d="M18.1673 8.33332C18.5479 10.2011 18.2767 12.1428 17.3989 13.8348C16.5211 15.5268 15.0897 16.8667 13.3436 17.6311C11.5975 18.3955 9.64203 18.5381 7.80342 18.0353C5.96482 17.5325 4.35417 16.4145 3.24007 14.8678C2.12597 13.3212 1.57577 11.4394 1.68123 9.53615C1.78668 7.63294 2.5414 5.8234 3.81955 4.4093C5.09769 2.9952 6.82199 2.06202 8.70489 1.76537C10.5878 1.46872 12.5155 1.82654 14.1665 2.77916"
                                stroke="#05DF72" stroke-width="1.66667" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M7.5 9.16659L10 11.6666L18.3333 3.33325" stroke="#05DF72" stroke-width="1.66667"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Dedicated account manager
                    </li>
                    <li class="flex items-center gap-2">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="none">
                            <path
                                d="M18.1673 8.33332C18.5479 10.2011 18.2767 12.1428 17.3989 13.8348C16.5211 15.5268 15.0897 16.8667 13.3436 17.6311C11.5975 18.3955 9.64203 18.5381 7.80342 18.0353C5.96482 17.5325 4.35417 16.4145 3.24007 14.8678C2.12597 13.3212 1.57577 11.4394 1.68123 9.53615C1.78668 7.63294 2.5414 5.8234 3.81955 4.4093C5.09769 2.9952 6.82199 2.06202 8.70489 1.76537C10.5878 1.46872 12.5155 1.82654 14.1665 2.77916"
                                stroke="#05DF72" stroke-width="1.66667" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M7.5 9.16659L10 11.6666L18.3333 3.33325" stroke="#05DF72" stroke-width="1.66667"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        24/7 phone support
                    </li>
                    <li class="flex items-center gap-2">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="none">
                            <path
                                d="M18.1673 8.33332C18.5479 10.2011 18.2767 12.1428 17.3989 13.8348C16.5211 15.5268 15.0897 16.8667 13.3436 17.6311C11.5975 18.3955 9.64203 18.5381 7.80342 18.0353C5.96482 17.5325 4.35417 16.4145 3.24007 14.8678C2.12597 13.3212 1.57577 11.4394 1.68123 9.53615C1.78668 7.63294 2.5414 5.8234 3.81955 4.4093C5.09769 2.9952 6.82199 2.06202 8.70489 1.76537C10.5878 1.46872 12.5155 1.82654 14.1665 2.77916"
                                stroke="#05DF72" stroke-width="1.66667" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M7.5 9.16659L10 11.6666L18.3333 3.33325" stroke="#05DF72" stroke-width="1.66667"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        SLA guarantee
                    </li>
                </ul>
            </div>
        </div>
    </section>

</x-layouts.app>