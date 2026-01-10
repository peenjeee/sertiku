@props(['title' => 'Master Dashboard – SertiKu'])

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


    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #0F0F1A;
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
            background: #0F0F1A;
            border-right: 1px solid rgba(139, 92, 246, 0.1);
            width: 260px;
        }

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
            background: rgba(139, 92, 246, 0.1);
            color: rgba(255, 255, 255, 0.9);
        }

        .nav-item.active {
            background: rgba(139, 92, 246, 0.15);
            color: #fff;
            border: 1px solid rgba(139, 92, 246, 0.3);
        }

        .nav-item.active svg {
            color: #A855F7;
        }

        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            padding: 24px;
        }

        .master-badge {
            background: #8B5CF6;
        }

        .glass-card {
            background: rgba(20, 20, 30, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(139, 92, 246, 0.15);
        }

        .stat-card-purple {
            background: rgba(139, 92, 246, 0.12);
            border: 1px solid rgba(139, 92, 246, 0.2);
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

        @media (max-width: 1024px) {
            .sidebar {
                position: fixed;
                left: -260px;
                z-index: 50;
                height: 100vh;
            }

            .sidebar.open {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body class="antialiased text-white">
    {{-- Sidebar --}}
    <aside class="sidebar fixed left-0 top-0 h-screen flex flex-col z-50" id="sidebar">
        {{-- Logo --}}
        {{-- Logo --}}
        <a href="{{ route('home') }}"
            class="flex items-center gap-3 px-5 py-5 border-b border-purple-500/20 hover:bg-white/5 transition group">
            <div
                class="master-badge w-10 h-10 rounded-xl flex items-center justify-center group-hover:scale-105 transition">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <div>
                <p class="text-white font-bold">SertiKu</p>
                <p class="text-purple-400 text-xs">Master</p>
            </div>
        </a>

        {{-- Navigation --}}
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <a href="{{ route('master.dashboard') }}"
                class="nav-item {{ request()->routeIs('master.dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 13a1 1 0 011-1h4a1 1 0 011 1v6a1 1 0 01-1 1h-4a1 1 0 01-1-1v-6z" />
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('master.admins') }}"
                class="nav-item {{ request()->routeIs('master.admins*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span>Kelola Admin</span>
            </a>

            <a href="{{ route('master.settings') }}"
                class="nav-item {{ request()->routeIs('master.settings') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Pengaturan Sistem</span>
            </a>

            <a href="{{ route('master.logs') }}"
                class="nav-item {{ request()->routeIs('master.logs') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span>Log Aktivitas</span>
            </a>

            <a href="{{ route('master.blockchain') }}"
                class="nav-item {{ request()->routeIs('master.blockchain') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                <span>Blockchain Wallet</span>
            </a>

            <a href="{{ route('master.support') }}"
                class="nav-item {{ request()->routeIs('master.support*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span>Support Tickets</span>
            </a>

            <div class="border-t border-white/10 my-4 pt-4">
                <p class="text-white/40 text-xs px-3 mb-2">PANEL LAINNYA</p>
            </div>

            <a href="{{ route('admin.dashboard') }}" class="nav-item">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
                <span>Admin Panel</span>
            </a>
        </nav>

        {{-- User Profile --}}
        <div class="p-4 border-t border-purple-500/20">
            <div class="flex items-center gap-3 mb-3">
                <div
                    class="master-badge w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm overflow-hidden">
                    @if(auth()->user()->avatar && (str_starts_with(auth()->user()->avatar, '/storage/') || str_starts_with(auth()->user()->avatar, 'http')))
                        <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="w-full h-full object-cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&email={{ urlencode(auth()->user()->email) }}&background=8B5CF6&color=fff&bold=true&size=40"
                            alt="Avatar" class="w-full h-full object-cover">
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white font-medium text-sm truncate">{{ auth()->user()->name ?? 'Master' }}</p>
                    <p class="text-purple-400 text-xs">Master</p>
                </div>
            </div>
            <form method="POST" action="{{ route('master.logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-lg text-white/50 text-sm hover:text-red-400 hover:bg-red-500/10 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- Mobile Menu Button --}}
    <button onclick="document.getElementById('sidebar').classList.toggle('open')"
        class="fixed top-4 left-4 z-40 lg:hidden p-2 rounded-lg bg-purple-500/20 text-white">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    {{-- Main Content --}}
    <main class="main-content">
        {{ $slot }}
    </main>

    {{-- Admin Support Widget --}}
    <x-admin-support-widget />

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