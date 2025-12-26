<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#3B82F6">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - SertiKu</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arimo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Chart.js for Analytics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0c1829 0%, #0f1f35 100%);
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

        .admin-bg {
            background: linear-gradient(180deg, #0F172A 0%, #1E293B 50%, #0F172A 100%);
            min-height: 100vh;
        }

        /* Sidebar styles */
        .sidebar {
            background: #0F172A;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            width: 288px;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar.collapsed .nav-text,
        .sidebar.collapsed .profile-text,
        .sidebar.collapsed .logo-text {
            display: none;
        }

        .sidebar.collapsed .logo-section {
            justify-content: center;
        }

        .sidebar.collapsed .nav-item {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }

        /* Mobile sidebar overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 35;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }

        /* Mobile styles */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -100%;
                top: 0;
                bottom: 0;
                z-index: 40;
                width: 288px;
                height: 100vh;
                height: 100dvh;
                height: -webkit-fill-available;
                overflow-y: auto;
            }

            .sidebar.open {
                left: 0;
            }

            .main-content {
                margin-left: 0 !important;
            }
        }

        /* Desktop main content adjustment */
        .main-content {
            transition: margin-left 0.3s ease;
            margin-left: 288px;
        }

        .main-content.expanded {
            margin-left: 80px;
        }

        .nav-item {
            transition: all 0.3s ease;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .nav-item.active {
            background: linear-gradient(180deg, #1E3A8F 0%, #3B82F6 100%);
            box-shadow: 0px 10px 15px -3px rgba(43, 127, 255, 0.5);
        }

        /* Glass Card */
        .glass-card {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            box-shadow: 0px 4px 6px -1px rgba(0, 0, 0, 0.1);
            border-radius: 16px;
        }

        /* Stat Cards */
        .stat-card-blue {
            background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
            border: 1px solid #BFDBFE;
        }

        .stat-card-green {
            background: linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%);
            border: 1px solid #A7F3D0;
        }

        .stat-card-purple {
            background: linear-gradient(135deg, #FAF5FF 0%, #F3E8FF 100%);
            border: 1px solid #E9D5FF;
        }

        .stat-card-red {
            background: linear-gradient(135deg, #FEF2F2 0%, #FECACA 100%);
            border: 1px solid #FCA5A5;
        }

        .stat-card-orange {
            background: linear-gradient(135deg, #FFF7ED 0%, #FFEDD5 100%);
            border: 1px solid #FDBA74;
        }

        .stat-card-cyan {
            background: linear-gradient(135deg, #ECFEFF 0%, #CFFAFE 100%);
            border: 1px solid #67E8F9;
        }

        /* Icon Circles */
        .icon-circle-blue {
            background: linear-gradient(135deg, #2B7FFF 0%, #1447E6 100%);
            box-shadow: 0px 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .icon-circle-green {
            background: linear-gradient(135deg, #00C950 0%, #008236 100%);
            box-shadow: 0px 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .icon-circle-purple {
            background: linear-gradient(135deg, #AD46FF 0%, #8200DB 100%);
            box-shadow: 0px 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .icon-circle-red {
            background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
            box-shadow: 0px 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .icon-circle-orange {
            background: linear-gradient(135deg, #F97316 0%, #EA580C 100%);
            box-shadow: 0px 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .icon-circle-cyan {
            background: linear-gradient(135deg, #06B6D4 0%, #0891B2 100%);
            box-shadow: 0px 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        /* Chat Widget */
        .chat-widget {
            background: linear-gradient(135deg, #2B7FFF 0%, #1447E6 100%);
            box-shadow: 0 10px 25px -5px rgba(43, 127, 255, 0.5);
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

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse-dot {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out;
        }

        .animate-slide-in-left {
            animation: slideInLeft 0.3s ease-out;
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

        .stagger-5 {
            animation-delay: 0.5s;
        }

        .stagger-6 {
            animation-delay: 0.6s;
        }

        .live-dot {
            animation: pulse-dot 2s ease-in-out infinite;
        }

        /* Hover animations */
        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>

<body class="admin-bg overflow-x-hidden">
    <div class="flex min-h-screen">
        <!-- Mobile Menu Button -->
        <button id="mobileMenuBtn" onclick="toggleSidebar()"
            class="md:hidden fixed top-4 left-4 z-50 w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Sidebar Overlay (mobile) -->
        <div id="sidebarOverlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>

        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar fixed left-0 top-0 h-screen flex flex-col z-40">
            <!-- Logo Section -->
            <div class="logo-section flex items-center gap-3 px-6 py-6 border-b border-white/10">
                <a href="{{ route('home') }}" class="flex items-center gap-3 hover:opacity-80 transition group">
                    <img src="{{ asset('favicon.svg') }}" alt="SertiKu"
                        class="w-10 h-10 flex-shrink-0 group-hover:scale-105 transition">
                    <div class="logo-text">
                        <p class="text-white font-bold text-lg">SertiKu</p>
                        <p class="text-white/50 text-xs">Admin Dashboard</p>
                    </div>
                </a>
                <button id="toggleBtn" onclick="toggleSidebar()"
                    class="ml-auto w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition hidden md:flex">
                    <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 py-6 px-4 space-y-2 overflow-y-auto">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-item flex items-center gap-3 px-4 py-4 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    title="Dashboard">
                    <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <span class="text-white/70 text-base nav-text">Dashboard</span>
                </a>

                <!-- Analytics -->
                <a href="{{ route('admin.analytics') }}"
                    class="nav-item flex items-center gap-3 px-4 py-4 rounded-xl {{ request()->routeIs('admin.analytics') ? 'active' : '' }}"
                    title="Analytics">
                    <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <span class="text-white/70 text-base nav-text">Analytics</span>
                </a>

                <!-- Kelola Pengguna -->
                <a href="{{ route('admin.users') }}"
                    class="nav-item flex items-center gap-3 px-4 py-4 rounded-xl {{ request()->routeIs('admin.users*') ? 'active' : '' }}"
                    title="Kelola Pengguna">
                    <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <span class="text-white/70 text-base nav-text">Kelola Pengguna</span>
                </a>

                <!-- Backup & Restore -->
                <a href="{{ route('admin.backup') }}"
                    class="nav-item flex items-center gap-3 px-4 py-4 rounded-xl {{ request()->routeIs('admin.backup*') ? 'active' : '' }}"
                    title="Backup & Restore">
                    <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                    </div>
                    <span class="text-white/70 text-base nav-text">Backup & Restore</span>
                </a>

                <div class="border-t border-white/10 my-4"></div>

                <!-- Settings -->
                <a href="{{ route('admin.settings') }}"
                    class="nav-item flex items-center gap-3 px-4 py-4 rounded-xl {{ request()->routeIs('admin.settings') ? 'active' : '' }}"
                    title="Settings">
                    <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <span class="text-white/70 text-base nav-text">Settings</span>
                </a>

                <!-- Profile -->
                <a href="{{ route('admin.profile') }}"
                    class="nav-item flex items-center gap-3 px-4 py-4 rounded-xl {{ request()->routeIs('admin.profile') ? 'active' : '' }}"
                    title="Profile">
                    <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <span class="text-white/70 text-base nav-text">Profile</span>
                </a>
            </nav>

            <!-- Logout Button -->
            <div class="p-4 border-t border-white/10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="logout-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-white/5 hover:bg-white/10 transition text-white/70">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="nav-text">Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content flex-1 p-4 pt-16 md:pt-6 lg:p-8 relative z-10 overflow-x-hidden max-w-full"
            id="mainContent">
            {{ $slot }}
        </main>
    </div>

    <script>
        let isCollapsed = false;
        let isMobile = window.innerWidth < 768;

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const overlay = document.getElementById('sidebarOverlay');
            const toggleBtn = document.getElementById('toggleBtn');

            if (isMobile) {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('active');
            } else {
                isCollapsed = !isCollapsed;
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
                if (toggleBtn) toggleBtn.classList.toggle('rotated');
            }
        }

        // Handle window resize
        window.addEventListener('resize', () => {
            isMobile = window.innerWidth < 768;
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const overlay = document.getElementById('sidebarOverlay');

            if (isMobile) {
                sidebar.classList.remove('collapsed');
                mainContent.classList.remove('expanded');
            } else {
                sidebar.classList.remove('open');
                overlay.classList.remove('active');
            }
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (isMobile) {
                const sidebar = document.getElementById('sidebar');
                const mobileMenuBtn = document.getElementById('mobileMenuBtn');

                if (!sidebar.contains(e.target) && !mobileMenuBtn.contains(e.target) && sidebar.classList.contains('open')) {
                    toggleSidebar();
                }
            }
        });
    </script>

    <!-- SweetAlert2 Toast Notifications -->
    {{-- SweetAlert Session --}}
    <x-sweetalert-session />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Global SweetAlert Confirmation
        // Global SweetAlert Confirmation
        window.confirmAction = function (e, message) {
            e.preventDefault();
            let trigger = e.target;

            // Check if it's a link
            let link = trigger.tagName === 'A' ? trigger : trigger.closest('a');
            if (link) {
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
                        window.location.href = link.href;
                    }
                });
                return false;
            }

            // Check if it's a form (or button inside form)
            let form = trigger.tagName === 'FORM' ? trigger : trigger.closest('form');
            if (form) {
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
        }
    </script>
</body>

</html>