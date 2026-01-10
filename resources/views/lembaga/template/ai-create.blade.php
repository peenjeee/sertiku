<x-layouts.lembaga>
    <div class="space-y-6" x-data="{
            description: '',
            selectedStyle: 'modern',
            orientation: 'landscape',
            primaryColor: '#3b82f6',
            accentColor: '#fbbf24',
            variations: 2,
            loading: false,
            generatedImages: [],
            showSaveModal: false,
            selectedImage: null,
            async generateTemplates() {
                if (!this.description) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Deskripsi Kosong',
                        text: 'Mohon isi deskripsi template terlebih dahulu',
                        background: '#1e293b',
                        color: '#fff'
                    });
                    return;
                }

                this.loading = true;
                this.generatedImages = [];

                try {
                    const response = await fetch('{{ route('lembaga.template.ai.generate') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            description: this.description,
                            style: this.selectedStyle,
                            orientation: this.orientation,
                            primary_color: this.primaryColor,
                            accent_color: this.accentColor,
                            variations: this.variations
                        })
                    });

                    const text = await response.text();
                    let data;
                    try {
                        data = JSON.parse(text);
                    } catch (e) {
                        console.error('Raw Server Error:', text);
                        if (text.includes('api-inference.huggingface.co is no longer supported')) {
                            throw new Error('API Hugging Face Deprecated. Silakan import workflow n8n baru.');
                        }
                        const tmp = document.createElement('div');
                        tmp.innerHTML = text;
                        const plainText = tmp.textContent || tmp.innerText || '';
                        throw new Error('Server Error: ' + plainText.substring(0, 300));
                    }

                    if (data.success) {
                        this.generatedImages = data.images;
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            background: '#1e293b',
                            color: '#fff'
                        });
                        Toast.fire({
                            icon: 'success',
                            title: data.message || 'Template berhasil dibuat'
                        });
                    } else {
                        throw new Error(data.message || 'Gagal generate template');
                    }

                } catch (error) {
                    console.error(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: error.message || 'Terjadi kesalahan sistem',
                        background: '#1e293b',
                        color: '#fff'
                    });
                } finally {
                    this.loading = false;
                }
            }
        }">

        <div class="info-box rounded-2xl p-6">
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">AI Template Generator</h3>
                    <p class="text-white/60">Buat template sertifikat unik dengan bantuan AI</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Sidebar Controls -->
            <div class="md:col-span-1">
                <div class="bg-slate-800/50 border border-white/10 rounded-2xl p-6 overflow-hidden">
                    <form @submit.prevent="generateTemplates" class="space-y-6">
                        <!-- Description -->
                        <div class="mb-6">
                            <label class="block text-white text-sm font-medium mb-2">Deskripsi Template</label>
                            <textarea x-model="description" rows="4"
                                class="w-full px-4 py-3 bg-slate-700/50 border border-white/20 rounded-xl text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                                placeholder="Contoh: Sertifikat pelatihan IT profesional"></textarea>
                        </div>

                        <!-- Orientation -->
                        <div class="mb-6">
                            <label class="block text-white text-sm font-medium mb-2">Orientasi</label>
                            <div class="flex gap-4">
                                <label class="cursor-pointer flex-1">
                                    <input type="radio" value="landscape" x-model="orientation" class="sr-only">
                                    <div class="p-4 rounded-xl border-2 text-center transition-all duration-200"
                                        :class="orientation === 'landscape' ? 'border-blue-500 bg-blue-500/20' : 'border-white/20 hover:border-white/40'">
                                        <div class="w-16 h-10 bg-white/20 rounded mx-auto mb-2"></div>
                                        <p class="text-white text-sm">Landscape</p>
                                    </div>
                                </label>
                                <label class="cursor-pointer flex-1">
                                    <input type="radio" value="portrait" x-model="orientation" class="sr-only">
                                    <div class="p-4 rounded-xl border-2 text-center transition-all duration-200"
                                        :class="orientation === 'portrait' ? 'border-blue-500 bg-blue-500/20' : 'border-white/20 hover:border-white/40'">
                                        <div class="w-10 h-16 bg-white/20 rounded mx-auto mb-2"></div>
                                        <p class="text-white text-sm">Portrait</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Gaya Desain -->
                        <div class="mb-6">
                            <label class="block text-white text-sm font-medium mb-2">Gaya Desain</label>
                            <select x-model="selectedStyle"
                                class="w-full px-4 py-3 bg-slate-700/50 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="modern" class="bg-gray-800">Modern</option>
                                <option value="classic" class="bg-gray-800">Classic</option>
                                <option value="elegant" class="bg-gray-800">Elegant</option>
                                <option value="minimalist" class="bg-gray-800">Minimalist</option>
                                <option value="futuristic" class="bg-gray-800">Futuristic</option>
                            </select>
                        </div>

                        <!-- Warna Utama -->
                        <div class="mb-6">
                            <label class="block text-white text-sm font-medium mb-2">Warna Utama</label>
                            <div class="flex items-center gap-3">
                                <input type="color" x-model="primaryColor"
                                    class="w-12 h-12 rounded-lg cursor-pointer border-2 border-white/20 bg-slate-700/50">
                                <input type="text" x-model="primaryColor" placeholder="#3b82f6"
                                    class="flex-1 px-4 py-3 bg-slate-700/50 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm uppercase">
                            </div>
                        </div>

                        <!-- Variations -->
                        <div class="mb-6">
                            <label class="block text-white text-sm font-medium mb-2">Jumlah Variasi</label>
                            <div class="flex gap-3">
                                <button type="button" @click="variations = 1"
                                    class="w-12 h-12 rounded-xl border-2 text-white font-bold transition-all duration-200"
                                    :class="variations === 1 ? 'border-blue-500 bg-blue-500/30' : 'border-white/20 hover:border-white/40'">1</button>
                                <button type="button" @click="variations = 2"
                                    class="w-12 h-12 rounded-xl border-2 text-white font-bold transition-all duration-200"
                                    :class="variations === 2 ? 'border-blue-500 bg-blue-500/30' : 'border-white/20 hover:border-white/40'">2</button>
                                <button type="button" @click="variations = 3"
                                    class="w-12 h-12 rounded-xl border-2 text-white font-bold transition-all duration-200"
                                    :class="variations === 3 ? 'border-blue-500 bg-blue-500/30' : 'border-white/20 hover:border-white/40'">3</button>
                                <button type="button" @click="variations = 4"
                                    class="w-12 h-12 rounded-xl border-2 text-white font-bold transition-all duration-200"
                                    :class="variations === 4 ? 'border-blue-500 bg-blue-500/30' : 'border-white/20 hover:border-white/40'">4</button>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full py-4 rounded-xl font-bold text-white flex items-center justify-center gap-2 transition-all duration-300"
                            :class="loading ? 'bg-slate-700 cursor-not-allowed opacity-70' : 'bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50'">
                            <svg x-show="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                style="display: none;">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span x-text="loading ? 'Generating Template...' : 'Generate dengan AI'"></span>
                        </button>
                        <p class="text-white/40 text-xs text-center mt-3">Experimental Features</p>
                    </form>
                </div>
            </div>

            <!-- Preview Area -->
            <div class="md:col-span-2">
                <div class="bg-slate-800/50 border border-white/10 rounded-2xl p-6 min-h-[600px]">
                    <template x-if="generatedImages.length === 0 && !loading">
                        <div class="h-full flex flex-col items-center justify-center text-white/30">
                            <svg class="w-20 h-20 mb-4 opacity-50" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-lg font-medium">Preview template akan muncul di sini</p>
                            <p class="text-sm">Isi deskripsi dan klik Generate untuk memulai</p>
                        </div>
                    </template>

                    <!-- Loading State for Preview -->
                    <template x-if="loading">
                        <div class="h-full flex flex-col items-center justify-center text-white/50">
                            <svg class="animate-spin w-12 h-12 text-blue-500 mb-4" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <p>Sedang membuat template...</p>
                        </div>
                    </template>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-show="generatedImages.length > 0 && !loading">
                        <template x-for="(image, index) in generatedImages" :key="index">
                            <div
                                class="group relative rounded-xl overflow-hidden bg-black/50 border border-white/10 hover:border-blue-500/50 transition-all duration-300">
                                <img :src="image.url"
                                    class="w-full h-auto object-cover opacity-80 group-hover:opacity-100 transition duration-300"
                                    alt="Generated Template">
                                <div
                                    class="absolute inset-0 bg-black/70 opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-end p-4">
                                    <div class="w-full">
                                        <p class="text-white/80 text-xs mb-3 line-clamp-2" x-text="image.prompt">
                                        </p>
                                        <button type="button" @click="selectedImage = image; showSaveModal = true"
                                            class="w-full py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-white text-sm font-medium transition shadow-lg shadow-blue-500/20">
                                            Gunakan Template ini
                                        </button>
                                    </div>
                                </div>
                                <div class="absolute top-2 right-2 px-2 py-1 bg-black/60 rounded text-xs text-white"
                                    x-text="'#' + (index + 1)"></div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Modal -->
        <div x-show="showSaveModal" x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80" style="display: none;">
            <div
                class="bg-slate-800 border border-white/10 rounded-2xl w-full max-w-md overflow-hidden transform transition-all shadow-2xl">
                <div class="p-6 border-b border-white/10 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-white">Simpan Template</h3>
                    <button @click="showSaveModal = false" class="text-white/40 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form action="{{ route('lembaga.template.ai.store') }}" method="POST">
                    @csrf
                    <div class="p-6 space-y-4">
                        <div
                            class="aspect-video rounded-xl overflow-hidden border border-white/10 bg-black/50 relative group">
                            <img :src="selectedImage?.url" class="w-full h-full object-contain" alt="Selected Template">
                            <input type="hidden" name="image_url" :value="selectedImage?.url">
                            <input type="hidden" name="orientation" :value="orientation">
                            <input type="hidden" name="style" :value="selectedStyle">
                        </div>
                        <div>
                            <label class="block text-white text-sm font-medium mb-2">Nama Template</label>
                            <input type="text" name="name" required
                                class="w-full px-4 py-3 bg-slate-700/50 border border-white/20 rounded-xl text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Nama template">
                        </div>
                    </div>
                    <div class="p-6 border-t border-white/10 flex gap-3">
                        <button type="button" @click="showSaveModal = false"
                            class="flex-1 py-3 bg-white/10 hover:bg-white/20 rounded-xl text-white font-medium transition">Batal</button>
                        <button type="submit"
                            class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 rounded-xl text-white font-medium transition shadow-lg shadow-blue-500/30">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.lembaga>