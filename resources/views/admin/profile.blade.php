<x-layouts.admin>
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-8 animate-fade-in">
        <div>
            <h1 class="text-white text-2xl lg:text-3xl font-bold">Profil Admin</h1>
            <p class="text-white/60 text-sm mt-1">Kelola informasi akun dan keamanan</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="glass-card rounded-2xl p-6 animate-fade-in-up stagger-1">
            <div class="text-center">
                <div
                    class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center mx-auto mb-4 overflow-hidden">
                    @if($admin->avatar && (str_starts_with($admin->avatar, '/storage/') || str_starts_with($admin->avatar, 'http')))
                        <img src="{{ $admin->avatar }}" alt="Avatar" class="w-full h-full object-cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($admin->name) }}&email={{ urlencode($admin->email) }}&background=3B82F6&color=fff&bold=true&size=96"
                            alt="Avatar" class="w-full h-full object-cover">
                    @endif
                </div>
                <h2 class="text-gray-800 font-bold text-xl">{{ $admin->name }}</h2>
                <p class="text-gray-500 text-sm">{{ $admin->email }}</p>
                <span
                    class="inline-block mt-3 px-3 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">Administrator</span>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-100 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-gray-500 text-sm">Bergabung</span>
                    <span class="text-gray-800 text-sm">{{ $admin->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500 text-sm">Last Update</span>
                    <span class="text-gray-800 text-sm">{{ $admin->updated_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>

        <!-- Update Profile Form -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Profile Info -->
            <div class="glass-card rounded-2xl p-6 animate-fade-in-up stagger-2">
                <h3 class="text-gray-800 font-bold text-lg mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Informasi Profil
                </h3>

                <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data"
                    class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-600 text-sm font-medium mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $admin->name) }}"
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-gray-600 text-sm font-medium mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email', $admin->email) }}"
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-gray-600 text-sm font-medium mb-2">Foto Profil</label>
                        <input type="file" name="avatar" accept="image/*"
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-gray-400 text-xs mt-1">Format: JPG, PNG. Maksimal 2MB</p>
                    </div>
                    <button type="submit"
                        class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                        Simpan Perubahan
                    </button>
                </form>
            </div>

            <!-- Change Password (Hidden for Google login users) -->
            @if(!$admin->google_id)
                <div class="glass-card rounded-2xl p-6 animate-fade-in-up stagger-3">
                    <h3 class="text-gray-800 font-bold text-lg mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Ubah Password
                    </h3>

                    <form method="POST" action="{{ route('admin.profile.password') }}" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-gray-600 text-sm font-medium mb-2">Password Saat Ini</label>
                            <input type="password" name="current_password"
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('current_password') border-red-500 @enderror">
                            @error('current_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-600 text-sm font-medium mb-2">Password Baru</label>
                                <input type="password" name="password"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror">
                                @error('password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-gray-600 text-sm font-medium mb-2">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        <button type="submit"
                            class="w-full py-3 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition">
                            Ubah Password
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div
            class="fixed bottom-6 right-6 bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg animate-fade-in-up z-50">
            {{ session('success') }}
        </div>
    @endif
</x-layouts.admin>