{{-- resources/views/lembaga/pengaturan.blade.php --}}
<x-layouts.lembaga>
    <x-slot name="title">Pengaturan Akun – SertiKu</x-slot>

    {{-- Header --}}
    <div class="mb-8 animate-fade-in">
        <h1 class="text-2xl lg:text-3xl font-bold text-white">Pengaturan Akun</h1>
        <p class="text-white/60 mt-2">Kelola informasi profil dan keamanan akun lembaga Anda</p>
    </div>

    {{-- Session Messages --}}
    @if(session('success'))
        <div
            class="mb-6 p-4 rounded-xl bg-green-500/20 border border-green-500/30 text-green-400 text-sm flex items-center gap-2 animate-fade-in-up">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('password_success'))
        <div
            class="mb-6 p-4 rounded-xl bg-green-500/20 border border-green-500/30 text-green-400 text-sm flex items-center gap-2 animate-fade-in-up">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            {{ session('password_success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-500/20 border border-red-500/30 text-red-400 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ============================================= --}}
    {{-- SECTION 1: Foto Profil --}}
    {{-- ============================================= --}}
    <div x-data="photoUpload()" class="bg-white rounded-2xl shadow-lg p-5 lg:p-6 mb-6 animate-fade-in-up">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Foto Profil
        </h3>

        <div class="flex flex-col sm:flex-row items-center gap-6">
            {{-- Avatar Preview --}}
            <div class="relative group">
                <div
                    class="w-24 h-24 lg:w-28 lg:h-28 rounded-full overflow-hidden bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                    @if($user->avatar && str_starts_with($user->avatar, '/storage/'))
                        <img src="{{ $user->avatar }}" alt="Avatar" class="w-full h-full object-cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&email={{ urlencode($user->email) }}&background=3B82F6&color=fff&bold=true&size=128"
                            alt="Avatar" class="w-full h-full object-cover">
                    @endif
                </div>

                {{-- Change Photo Button Overlay --}}
                <button type="button" @click="showModal = true"
                    class="absolute inset-0 rounded-full bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
            </div>

            {{-- Upload Info --}}
            <div class="text-center sm:text-left">
                <p class="text-gray-500 text-sm">JPG, PNG atau GIF. Maksimal 2MB</p>
                <div class="mt-3 flex flex-wrap gap-2 justify-center sm:justify-start">
                    <button type="button" @click="showModal = true"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-500 text-white text-sm hover:bg-blue-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Ubah Foto
                    </button>
                    @if($user->avatar)
                        <form action="{{ route('lembaga.settings.avatar.remove') }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-500 text-white text-sm hover:bg-red-600 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- Upload Modal - Teleported to body --}}
        <template x-teleport="body">
            <div x-show="showModal" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
                style="display: none;">

                <div @click.away="showModal = false" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    class="relative w-full max-w-lg bg-[#0F172A] rounded-2xl border border-white/10 shadow-2xl">

                    {{-- Modal Header --}}
                    <div class="flex items-center justify-between p-5 border-b border-white/10">
                        <h3 class="text-lg font-semibold text-white">Upload Foto Profil</h3>
                        <button type="button" @click="showModal = false"
                            class="p-1 rounded-lg hover:bg-white/10 transition">
                            <svg class="w-5 h-5 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <form action="{{ route('lembaga.settings.avatar') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="p-5">
                            {{-- Drag & Drop Zone --}}
                            <div @dragover.prevent="isDragging = true" @dragleave.prevent="isDragging = false"
                                @drop.prevent="handleDrop($event)"
                                :class="isDragging ? 'border-blue-500 bg-blue-500/10' : 'border-white/20'"
                                class="relative p-6 border-2 border-dashed rounded-xl text-center transition cursor-pointer hover:border-blue-500/50"
                                @click="$refs.fileInput.click()">

                                <input type="file" name="avatar" x-ref="fileInput" @change="handleFileSelect($event)"
                                    accept="image/jpeg,image/png,image/gif" class="hidden">

                                <template x-if="!previewUrl">
                                    <div>
                                        <div
                                            class="w-16 h-16 mx-auto mb-4 rounded-full bg-blue-500/20 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                        </div>
                                        <p class="text-white font-medium">Drag & drop foto di sini</p>
                                        <p class="text-white/50 text-sm mt-1">atau klik untuk memilih file</p>
                                        <p class="text-white/30 text-xs mt-3">JPG, PNG, GIF • Maks 2MB</p>
                                    </div>
                                </template>

                                <template x-if="previewUrl">
                                    <div>
                                        <img :src="previewUrl" alt="Preview"
                                            class="w-32 h-32 mx-auto rounded-full object-cover border-4 border-blue-500/30">
                                        <p class="text-white font-medium mt-4" x-text="fileName"></p>
                                        <p class="text-white/50 text-sm" x-text="fileSize"></p>
                                        <button type="button" @click.stop="clearFile()"
                                            class="mt-3 text-red-400 text-sm hover:text-red-300">
                                            Pilih foto lain
                                        </button>
                                    </div>
                                </template>
                            </div>

                            {{-- Error Message --}}
                            <template x-if="error">
                                <div class="mt-4 p-3 rounded-lg bg-red-500/20 border border-red-500/30 text-red-400 text-sm"
                                    x-text="error"></div>
                            </template>
                        </div>

                        {{-- Modal Footer --}}
                        <div class="flex items-center justify-end gap-3 p-5 border-t border-white/10">
                            <button type="button" @click="showModal = false; clearFile()"
                                class="px-4 py-2 rounded-lg bg-white/10 text-white font-medium hover:bg-white/20 transition">
                                Batal
                            </button>
                            <button type="submit" :disabled="!previewUrl || uploading"
                                :class="previewUrl && !uploading ? 'bg-gradient-to-r from-blue-500 to-indigo-600 hover:brightness-110' : 'bg-white/10 cursor-not-allowed'"
                                class="px-4 py-2 rounded-lg text-white font-medium transition flex items-center gap-2">
                                <template x-if="uploading">
                                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </template>
                                <span x-text="uploading ? 'Mengupload...' : 'Upload Foto'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>

    {{-- ============================================= --}}
    {{-- SECTION 2: Informasi Profil --}}
    {{-- ============================================= --}}
    <div class="bg-white rounded-2xl shadow-lg p-5 lg:p-6 mb-6 animate-fade-in-up">
        <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Informasi Profil
        </h3>

        <form action="{{ route('lembaga.settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Nama Lengkap --}}
                <div>
                    <label class="block text-sm text-gray-600 font-medium mb-2">Nama Lengkap <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="w-full rounded-xl bg-gray-50 border border-gray-200 px-4 py-3 text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500">
                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nama Lembaga --}}
                <div>
                    <label class="block text-sm text-gray-600 font-medium mb-2">Nama Lembaga</label>
                    <input type="text" name="institution_name"
                        value="{{ old('institution_name', $user->institution_name) }}"
                        class="w-full rounded-xl bg-gray-50 border border-gray-200 px-4 py-3 text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500"
                        placeholder="Nama institusi/perusahaan">
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm text-gray-600 font-medium mb-2">Email</label>
                    <input type="email" value="{{ $user->email }}" disabled
                        class="w-full rounded-xl bg-gray-100 border border-gray-200 px-4 py-3 text-gray-500 cursor-not-allowed">
                    <p class="mt-1 text-xs text-gray-400">Email tidak dapat diubah</p>
                </div>

                {{-- No. Telepon --}}
                <div>
                    <label class="block text-sm text-gray-600 font-medium mb-2">No. Telepon</label>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                        class="w-full rounded-xl bg-gray-50 border border-gray-200 px-4 py-3 text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500"
                        placeholder="08123456789">
                </div>

                {{-- Pekerjaan --}}
                <div>
                    <label class="block text-sm text-gray-600 font-medium mb-2">Bidang/Industri</label>
                    <input type="text" name="occupation" value="{{ old('occupation', $user->occupation) }}"
                        class="w-full rounded-xl bg-gray-50 border border-gray-200 px-4 py-3 text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500"
                        placeholder="Contoh: Pendidikan">
                </div>

                {{-- Kota --}}
                <div>
                    <label class="block text-sm text-gray-600 font-medium mb-2">Kota</label>
                    <input type="text" name="city" value="{{ old('city', $user->city) }}"
                        class="w-full rounded-xl bg-gray-50 border border-gray-200 px-4 py-3 text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500"
                        placeholder="Contoh: Jakarta">
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="mt-6 flex justify-end">
                <button type="submit"
                    class="px-6 py-3 rounded-xl bg-gradient-to-r from-green-500 to-emerald-600 text-white font-medium hover:brightness-110 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- ============================================= --}}
    {{-- SECTION 3: Keamanan (Hidden for Google users) --}}
    {{-- ============================================= --}}
    @if(!$user->google_id)
        <div class="bg-white rounded-2xl shadow-lg p-5 lg:p-6 animate-fade-in-up">
            <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                Keamanan
            </h3>

            <form action="{{ route('lembaga.settings.password') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Password Saat Ini --}}
                    <div>
                        <label class="block text-sm text-gray-600 font-medium mb-2">Password Saat Ini</label>
                        <input type="password" name="current_password"
                            class="w-full rounded-xl bg-gray-50 border border-gray-200 px-4 py-3 text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500"
                            placeholder="Masukkan password saat ini">
                    </div>

                    {{-- Password Baru --}}
                    <div>
                        <label class="block text-sm text-gray-600 font-medium mb-2">Password Baru</label>
                        <input type="password" name="password"
                            class="w-full rounded-xl bg-gray-50 border border-gray-200 px-4 py-3 text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500"
                            placeholder="Minimal 8 karakter">
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <label class="block text-sm text-gray-600 font-medium mb-2">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation"
                            class="w-full rounded-xl bg-gray-50 border border-gray-200 px-4 py-3 text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500"
                            placeholder="Ulangi password baru">
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-between">
                    {{-- Security Tips --}}
                    <div class="hidden lg:flex items-center gap-2 text-gray-400 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Gunakan kombinasi huruf, angka, dan simbol untuk password yang kuat
                    </div>
                    <button type="submit"
                        class="px-6 py-3 rounded-xl bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-medium hover:brightness-110 transition">
                        Ubah Password
                    </button>
                </div>
            </form>
        </div>
    @endif

    @push('scripts')
        <script>
            function photoUpload() {
                return {
                    showModal: false,
                    isDragging: false,
                    previewUrl: null,
                    fileName: '',
                    fileSize: '',
                    error: null,
                    uploading: false,

                    handleFileSelect(event) {
                        const file = event.target.files[0];
                        this.processFile(file);
                    },

                    handleDrop(event) {
                        this.isDragging = false;
                        const file = event.dataTransfer.files[0];
                        this.processFile(file);
                    },

                    processFile(file) {
                        this.error = null;

                        if (!file) return;

                        // Validate file type
                        const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
                        if (!validTypes.includes(file.type)) {
                            this.error = 'Format file tidak didukung. Gunakan JPG, PNG, atau GIF.';
                            return;
                        }

                        // Validate file size (2MB)
                        if (file.size > 2 * 1024 * 1024) {
                            this.error = 'Ukuran file terlalu besar. Maksimal 2MB.';
                            return;
                        }

                        // Create preview
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.previewUrl = e.target.result;
                        };
                        reader.readAsDataURL(file);

                        // Set file info
                        this.fileName = file.name;
                        this.fileSize = this.formatFileSize(file.size);
                    },

                    formatFileSize(bytes) {
                        if (bytes < 1024) return bytes + ' B';
                        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
                        return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
                    },

                    clearFile() {
                        this.previewUrl = null;
                        this.fileName = '';
                        this.fileSize = '';
                        this.error = null;
                        this.$refs.fileInput.value = '';
                    }
                }
            }
        </script>
    @endpush

</x-layouts.lembaga>