{{-- resources/views/pages/kontak.blade.php --}}
<x-layouts.app title="SertiKu – Hubungi Kami">

    
        {{-- Hero --}}
        <section class="relative overflow-hidden py-16 md:py-20">
            <div class="pointer-events-none absolute -right-32 top-0 h-96 w-96 rounded-full bg-gradient-to-r from-[#2B7FFF4D] to-[#00B8DB4D] blur-3xl opacity-60"></div>
            <div class="mx-auto max-w-4xl px-4 text-center relative z-10">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Hubungi Kami</h1>
                <p class="text-lg text-[#BEDBFF]/80 max-w-2xl mx-auto">
                    Ada pertanyaan atau butuh bantuan? Kami siap membantu Anda
                </p>
            </div>
        </section>

        {{-- Content --}}
        <section class="py-12 px-4">
            <div class="mx-auto max-w-5xl">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    {{-- Contact Info --}}
                    <div class="space-y-8">
                        <div>
                            <h2 class="text-2xl font-bold text-white mb-6">Informasi Kontak</h2>
                            <div class="space-y-6">
                                {{-- Email --}}
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-[#3B82F6]/20 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6 text-[#3B82F6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-white font-medium">Email</h3>
                                        <p class="text-[#BEDBFF]/70 mt-1">support@sertiku.my.id</p>
                                        <p class="text-[#BEDBFF]/50 text-sm mt-1">Respon dalam 24 jam</p>
                                    </div>
                                </div>

                                {{-- Phone --}}
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-[#10B981]/20 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-white font-medium">WhatsApp</h3>
                                        <p class="text-[#BEDBFF]/70 mt-1">+62 812 3456 7890</p>
                                        <p class="text-[#BEDBFF]/50 text-sm mt-1">Senin - Jumat, 09:00 - 17:00 WIB</p>
                                    </div>
                                </div>

                                {{-- Location --}}
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-[#F59E0B]/20 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6 text-[#F59E0B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-white font-medium">Alamat</h3>
                                        <p class="text-[#BEDBFF]/70 mt-1">Sleman, Daerah Istimewa Yogyakarta</p>
                                        <p class="text-[#BEDBFF]/50 text-sm mt-1">Indonesia</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Social Media --}}
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-4">Ikuti Kami</h3>
                            <div class="flex gap-3">
                                <a href="#" class="w-10 h-10 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-[#BEDBFF] hover:border-[#3B82F6] hover:text-white transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073z"/></svg>
                                </a>
                                <a href="#" class="w-10 h-10 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-[#BEDBFF] hover:border-[#3B82F6] hover:text-white transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                </a>
                                <a href="#" class="w-10 h-10 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-[#BEDBFF] hover:border-[#3B82F6] hover:text-white transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Contact Form --}}
                    <div class="rounded-2xl bg-white/5 border border-white/10 p-8">
                        <h2 class="text-xl font-bold text-white mb-6">Kirim Pesan</h2>
                        
                        @if(session('success'))
                        <div class="mb-6 rounded-lg bg-[#10B981]/20 border border-[#10B981]/30 p-4 text-[#10B981]">
                            {{ session('success') }}
                        </div>
                        @endif

                        <form action="{{ route('kontak.send') }}" method="POST" class="space-y-5">
                            @csrf
                            
                            <div class="space-y-2">
                                <label class="text-sm text-white">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                       class="w-full rounded-lg bg-white/5 border border-white/20 px-4 py-3 text-white text-sm placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]"
                                       placeholder="Nama Anda">
                                @error('name')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm text-white">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                       class="w-full rounded-lg bg-white/5 border border-white/20 px-4 py-3 text-white text-sm placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]"
                                       placeholder="email@contoh.com">
                                @error('email')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm text-white">Subjek</label>
                                <input type="text" name="subject" value="{{ old('subject') }}" required
                                       class="w-full rounded-lg bg-white/5 border border-white/20 px-4 py-3 text-white text-sm placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]"
                                       placeholder="Subjek pesan">
                                @error('subject')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm text-white">Pesan</label>
                                <textarea name="message" rows="5" required
                                          class="w-full rounded-lg bg-white/5 border border-white/20 px-4 py-3 text-white text-sm placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] resize-none"
                                          placeholder="Tulis pesan Anda...">{{ old('message') }}</textarea>
                                @error('message')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <button type="submit" 
                                    class="w-full rounded-lg bg-gradient-to-b from-[#1E3A8F] to-[#3B82F6] px-6 py-3 text-sm font-medium text-white shadow-lg hover:brightness-110 transition">
                                Kirim Pesan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>


</x-layouts.app>
