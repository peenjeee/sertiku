@props(['title' => 'Dashboard User – SertiKu'])

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#3B82F6">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>{{ $title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    {{-- Google Fonts - Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #0F172A;
            min-height: 100vh;
        }

        /* Hide scrollbar for Chrome, Safari and Opera */
        ::-webkit-scrollbar {
            display: none;
        }

        /* Hide scrollbar for IE, Edge and Firefox */
        html {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }

        .sidebar {
            background: #0F172A;
            border-right: 1px solid rgba(255, 255, 255, 0.06);
            width: 240px;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar.collapsed .nav-text,
        .sidebar.collapsed .logo-text,
        .sidebar.collapsed .user-info {
            display: none;
        }

        .sidebar.collapsed .nav-item {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }

        /* Nav Items */
        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            color: rgba(255, 255, 255, 0.6);
            transition: all 0.2s ease;
            margin-bottom: 4px;
        }

        .nav-item:hover {
            background: rgba(59, 130, 246, 0.1);
            color: rgba(255, 255, 255, 0.9);
        }

        .nav-item.active {
            background: rgba(59, 130, 246, 0.15);
            color: #fff;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .nav-item.active .nav-icon {
            color: #3B82F6;
        }

        /* Badge */
        .notification-badge {
            background: #EF4444;
            color: white;
            font-size: 10px;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: 240px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
            padding: 24px;
        }

        .sidebar.collapsed+.main-content {
            margin-left: 80px;
        }

        /* Welcome Banner */
        .welcome-banner {
            background: #1E40AF;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        /* Stat Cards */
        .stat-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .stat-card-blue {
            background: rgba(59, 130, 246, 0.12);
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .stat-card-green {
            background: rgba(34, 197, 94, 0.12);
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .stat-card-yellow {
            background: rgba(234, 179, 8, 0.12);
            border: 1px solid rgba(234, 179, 8, 0.2);
        }

        .stat-card-red {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .stat-card-purple {
            background: rgba(168, 85, 247, 0.12);
            border: 1px solid rgba(168, 85, 247, 0.2);
        }

        /* Glass Card */
        .glass-card {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        /* Activity Card */
        .activity-card {
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }

        .stagger-1 {
            animation-delay: 0.1s;
        }

        .stagger-2 {
            animation-delay: 0.2s;
        }

        .stagger-3 {
            animation-delay: 0.3s;
        }

        .stagger-4 {
            animation-delay: 0.4s;
        }

        /* Mobile */
        @media (max-width: 1024px) {
            .sidebar {
                position: fixed;
                left: -240px;
                z-index: 50;
                height: 100vh;
            }

            .sidebar.open {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 40;
            }

            .sidebar.open+.sidebar-overlay {
                display: block;
            }
        }

        /* User Profile Section */
        .user-profile-section {
            background: rgba(255, 255, 255, 0.03);
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }
    </style>
</head>

<body class="antialiased text-white overflow-x-hidden">
    {{-- Sidebar Overlay (Mobile) --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    {{-- Sidebar --}}
    <aside class="sidebar fixed left-0 top-0 h-screen flex flex-col z-50" id="sidebar">
        {{-- Logo --}}
        <div class="flex items-center justify-between px-5 py-5 border-b border-white/10">
            <a href="{{ route('home') }}" class="flex items-center gap-3 hover:opacity-80 transition group">
                <img src="{{ asset('favicon.ico') }}" alt="SertiKu"
                    class="w-10 h-10 flex-shrink-0 group-hover:scale-105 transition"
                    style="filter: brightness(0) invert(1);">
                <div class="logo-text">
                    <p class="text-white font-bold text-lg">SertiKu</p>
                    <p class="text-white/50 text-xs">Dashboard User</p>
                </div>
            </a>
            <button onclick="toggleSidebar()" class="ml-auto text-white/50 hover:text-white lg:hidden">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <a href="{{ route('user.dashboard') }}"
                class="nav-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5 nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 13a1 1 0 011-1h4a1 1 0 011 1v6a1 1 0 01-1 1h-4a1 1 0 01-1-1v-6z" />
                </svg>
                <span class="nav-text">Dashboard</span>
            </a>

            <a href="{{ route('user.sertifikat') }}"
                class="nav-item {{ request()->routeIs('user.sertifikat*') ? 'active' : '' }}">
                <svg class="w-5 h-5 nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
                <span class="nav-text">Sertifikat Saya</span>
            </a>

            <a href="{{ route('user.profil') }}"
                class="nav-item {{ request()->routeIs('user.profil*') ? 'active' : '' }}">
                <svg class="w-5 h-5 nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="nav-text">Profil</span>
            </a>

            <a href="{{ route('user.notifikasi') }}"
                class="nav-item {{ request()->routeIs('user.notifikasi*') ? 'active' : '' }}">
                <svg class="w-5 h-5 nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span class="nav-text">Notifikasi</span>
                @php $unreadCount = auth()->user()->unreadNotifications->count() ?? 0; @endphp
                @if($unreadCount > 0)
                    <span class="notification-badge">{{ $unreadCount }}</span>
                @endif
            </a>


            <!-- Beri Feedback -->
            <a href="{{ route('feedback.create') }}"
                class="nav-item {{ request()->routeIs('feedback*') ? 'active' : '' }}">
                <svg class="w-5 h-5 nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
                <span class="nav-text">Beri Feedback</span>
            </a>
        </nav>

        {{-- User Profile Section --}}
        <div class="user-profile-section mt-auto p-4">
            <div class="flex items-center gap-3 mb-3">
                <div
                    class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0 overflow-hidden">
                    @if(auth()->user()->avatar && (str_starts_with(auth()->user()->avatar, '/storage/') || str_starts_with(auth()->user()->avatar, 'http')))
                        <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="w-full h-full object-cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&email={{ urlencode(auth()->user()->email) }}&background=3B82F6&color=fff&bold=true&size=40"
                            alt="Avatar" class="w-full h-full object-cover">
                    @endif
                </div>
                <div class="user-info min-w-0">
                    <p class="text-white font-medium text-sm truncate">{{ auth()->user()->name ?? 'User' }}</p>
                    <p class="text-white/50 text-xs">User</p>
                </div>
            </div>
            <a href="{{ route('user.profil.edit') }}"
                class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-lg border border-white/20 text-white/70 text-sm hover:bg-white/5 transition mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="nav-text">Pengaturan</span>
            </a>
            <a href="{{ route('contact.admin') }}"
                class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-lg border border-green-500/30 text-green-400 text-sm hover:bg-green-500/10 transition mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span class="nav-text">Hubungi Admin</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-lg border border-red-500/30 text-red-400 text-sm hover:text-red-400 hover:bg-red-500/10 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="nav-text">Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- Mobile Menu Button --}}
    <button onclick="toggleSidebar()" class="fixed top-4 left-4 z-40 lg:hidden p-2 rounded-lg bg-white/10 text-white">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    {{-- Main Content --}}
    <main class="main-content pt-16 lg:pt-0 px-4 lg:px-6 pb-6 overflow-x-hidden max-w-full">
        <div class="max-w-full overflow-x-hidden">
            {{ $slot }}
        </div>
    </main>

    {{-- Chat Widget Component --}}
    <x-chat-widget role="user" />

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth < 1024) {
                sidebar.classList.toggle('open');
            } else {
                sidebar.classList.toggle('collapsed');
            }
        }
    </script>

    @stack('scripts')
    {{-- SweetAlert Session --}}
    <x-sweetalert-session />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Global Form Validation with SweetAlert2
        document.addEventListener('DOMContentLoaded', function () {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.setAttribute('novalidate', true);
                form.addEventListener('submit', function (e) {
                    if (!this.checkValidity()) {
                        e.preventDefault();
                        e.stopImmediatePropagation();
                        const firstInvalid = this.querySelector(':invalid');
                        if (firstInvalid) {
                            const label = firstInvalid.closest('.space-y-2')?.querySelector('label')?.textContent?.replace('*', '').trim()
                                || firstInvalid.name || 'Field';
                            Swal.fire({
                                icon: 'warning',
                                title: 'Mohon Lengkapi Data',
                                text: `${label} wajib diisi`,
                                confirmButtonColor: '#3B82F6',
                                background: '#0f172a',
                                color: '#fff'
                            }).then(() => setTimeout(() => firstInvalid.focus(), 300));
                        }
                    }
                });
            });
        });
    </script>
    <script>
        // Global SweetAlert Confirmation
        window.confirmAction = function (e, message) {
            e.preventDefault();
            let form = e.target;

            // If the element is a link (a tag), not a form
            if (form.tagName === 'A') {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#6366f1', // Indigo
                    cancelButtonColor: '#ef4444', // Red
                    confirmButtonText: 'Ya, lanjutkan!',
                    cancelButtonText: 'Batal',
                    background: '#1f2937', // Dark mode bg
                    color: '#fff' // Dark mode text
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = form.href;
                    }
                });
                return false;
            }

            // If it's a form
            Swal.fire({
                title: 'Konfirmasi',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6366f1', // Indigo
                cancelButtonColor: '#ef4444', // Red
                confirmButtonText: 'Ya, lanjutkan!',
                cancelButtonText: 'Batal',
                background: '#1f2937', // Dark mode bg
                color: '#fff' // Dark mode text
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            return false;
        }
    </script>
</body>

</html>