{{-- resources/views/pages/kontak.blade.php --}}
<x-layouts.app title="SertiKu – Hubungi Kami">


    {{-- Hero --}}
    <section class="relative overflow-hidden py-10 md:py-16 lg:py-20">
        <div class="mx-auto max-w-4xl px-4 text-center relative z-10">
            <h1
                class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-3 md:mb-4 animate-fade-in-up">
                Hubungi Kami</h1>
            <p class="text-sm sm:text-base lg:text-lg text-[#BEDBFF]/80 max-w-2xl mx-auto animate-fade-in-up stagger-1">
                Ada pertanyaan atau butuh bantuan? Kami siap membantu Anda
            </p>
        </div>
    </section>

    {{-- Content --}}
    <section class="relative py-8 md:py-12 px-4 overflow-hidden">
        <div class="mx-auto max-w-5xl">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
                {{-- Contact Form - First on mobile --}}
                <div
                    class="w-full rounded-xl lg:rounded-2xl bg-white/5 border border-white/10 p-4 sm:p-6 lg:p-8 order-1 lg:order-2 md:animate-fade-in-up hover-lift">
                    <h2 class="text-lg sm:text-xl font-bold text-white mb-5 lg:mb-6">Kirim Pesan</h2>


                    <form action="{{ route('kontak.send') }}" method="POST" class="space-y-4 lg:space-y-5">
                        @csrf

                        <div class="space-y-1.5 lg:space-y-2">
                            <label class="text-xs lg:text-sm text-white">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full max-w-full rounded-lg bg-white/5 border border-white/20 px-3 lg:px-4 py-2.5 lg:py-3 text-white text-sm placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]"
                                placeholder="Nama Anda">
                            @error('name')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="space-y-1.5 lg:space-y-2">
                            <label class="text-xs lg:text-sm text-white">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full max-w-full rounded-lg bg-white/5 border border-white/20 px-3 lg:px-4 py-2.5 lg:py-3 text-white text-sm placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]"
                                placeholder="email@contoh.com">
                            @error('email')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="space-y-1.5 lg:space-y-2">
                            <label class="text-xs lg:text-sm text-white">Subjek</label>
                            <input type="text" name="subject" value="{{ old('subject') }}" required
                                class="w-full max-w-full rounded-lg bg-white/5 border border-white/20 px-3 lg:px-4 py-2.5 lg:py-3 text-white text-sm placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]"
                                placeholder="Subjek pesan">
                            @error('subject')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="space-y-1.5 lg:space-y-2">
                            <label class="text-xs lg:text-sm text-white">Pesan</label>
                            <textarea name="message" rows="4" required
                                class="w-full max-w-full rounded-lg bg-white/5 border border-white/20 px-3 lg:px-4 py-2.5 lg:py-3 text-white text-sm placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] resize-none"
                                placeholder="Tulis pesan Anda...">{{ old('message') }}</textarea>
                            @error('message')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <button type="submit"
                            class="w-full rounded-lg bg-[#2563EB] px-5 lg:px-6 py-2.5 lg:py-3 text-sm font-medium text-white shadow-md shadow-blue-500/20 hover:bg-[#3B82F6] transition">
                            Kirim Pesan
                        </button>
                    </form>
                </div>

                {{-- Contact Info - Second on mobile --}}
                <div class="space-y-6 lg:space-y-8 order-2 lg:order-1 md:animate-slide-in-left">
                    <div>
                        <h2 class="text-xl lg:text-2xl font-bold text-white mb-4 lg:mb-6">Informasi Kontak</h2>
                        <div class="space-y-4 lg:space-y-6">
                            {{-- Email --}}
                            <div class="flex items-start gap-3 lg:gap-4">
                                <div
                                    class="w-10 h-10 lg:w-12 lg:h-12 rounded-lg lg:rounded-xl bg-[#3B82F6]/20 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-[#3B82F6]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-white font-medium text-sm lg:text-base">Email</h3>
                                    <p class="text-[#BEDBFF]/70 text-sm mt-0.5 lg:mt-1">support@sertiku.web.id</p>
                                    <p class="text-[#BEDBFF]/50 text-xs mt-0.5 lg:mt-1">Respon dalam 24 jam</p>
                                </div>
                            </div>

                            {{-- Phone --}}
                            <div class="flex items-start gap-3 lg:gap-4">
                                <div
                                    class="w-10 h-10 lg:w-12 lg:h-12 rounded-lg lg:rounded-xl bg-[#10B981]/20 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-[#10B981]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-white font-medium text-sm lg:text-base">WhatsApp</h3>
                                    <p class="text-[#BEDBFF]/70 text-sm mt-0.5 lg:mt-1">+62 857 7741 9874</p>
                                    <p class="text-[#BEDBFF]/50 text-xs mt-0.5 lg:mt-1">Senin - Jumat, 09:00 - 17:00 WIB
                                    </p>
                                </div>
                            </div>

                            {{-- Location --}}
                            <div class="flex items-start gap-3 lg:gap-4">
                                <div
                                    class="w-10 h-10 lg:w-12 lg:h-12 rounded-lg lg:rounded-xl bg-[#F59E0B]/20 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-[#F59E0B]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-white font-medium text-sm lg:text-base">Alamat</h3>
                                    <p class="text-[#BEDBFF]/70 text-sm mt-0.5 lg:mt-1">Sleman, Daerah Istimewa
                                        Yogyakarta</p>
                                    <p class="text-[#BEDBFF]/50 text-xs mt-0.5 lg:mt-1">Indonesia</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Social Media --}}
                    <div>
                        <h3 class="text-base lg:text-lg font-semibold text-white mb-3 lg:mb-4">Ikuti Kami</h3>
                        <div class="flex gap-2 lg:gap-3">
                            <!-- <a href="https://github.com/peenjeee/sertiku" target="_blank"
                                class="w-9 h-9 lg:w-10 lg:h-10 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-[#BEDBFF] hover:border-[#3B82F6] hover:text-white transition">
                                <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
                                </svg>
                            </a> -->
                            <a href="https://instagram.com/sertiku.web.id" target="_blank"
                                class="w-9 h-9 lg:w-10 lg:h-10 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-[#BEDBFF] hover:border-[#3B82F6] hover:text-white transition">
                                <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073z" />
                                </svg>
                            </a>
                            <a href="https://x.com/sertiku_web_id" target="_blank"
                                class="w-9 h-9 lg:w-10 lg:h-10 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-[#BEDBFF] hover:border-[#3B82F6] hover:text-white transition">
                                <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                                </svg>
                            </a>
                            <a href="https://linkedin.com/company/sertiku" target="_blank"
                                class="w-9 h-9 lg:w-10 lg:h-10 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-[#BEDBFF] hover:border-[#3B82F6] hover:text-white transition">
                                <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


</x-layouts.app>