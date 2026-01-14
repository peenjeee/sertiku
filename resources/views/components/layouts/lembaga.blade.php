<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#3B82F6">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>Dashboard Lembaga - SertiKu</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

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

        .institution-bg {
            background: #0F172A;
            min-height: 100vh;
        }

        /* Sidebar styles */
        .sidebar {
            background: #0F172A;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            width: 288px;
            overflow-y: auto;
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* IE and Edge */
        }

        .sidebar nav {
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* IE and Edge */
        }

        .sidebar nav::-webkit-scrollbar {
            display: none;
            /* Chrome, Safari and Opera */
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

        .sidebar.collapsed .profile-section {
            flex-direction: column;
            align-items: center;
        }

        .sidebar.collapsed .logout-btn {
            padding-left: 12px;
            padding-right: 12px;
        }

        .sidebar.collapsed .logout-btn span {
            display: none;
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
                z-index: 40;
                width: 288px;
                height: 100vh;
                height: 100dvh;
                /* Dynamic viewport height for mobile */
                overflow: hidden;
            }

            .sidebar.open {
                left: 0;
            }

            .sidebar.collapsed {
                width: 288px;
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
            background: #3B82F6;
            box-shadow: 0px 10px 15px -3px rgba(59, 130, 246, 0.4);
        }

        /* Light/White cards for content */
        .glass-card {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            box-shadow: 0px 4px 6px -1px rgba(0, 0, 0, 0.1);
            border-radius: 16px;
        }

        /* Solid Cards */
        .card-solid-blue {
            background: #EFF6FF;
            border: 1px solid #BFDBFE;
        }

        .card-solid-green {
            background: #ECFDF5;
            border: 1px solid #A7F3D0;
        }

        .card-solid-purple {
            background: #FAF5FF;
            border: 1px solid #E9D5FF;
        }

        .welcome-banner {
            background: #3B82F6;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0px 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .welcome-banner::before {
            display: none;
        }

        /* Primary Button */
        .btn-primary-solid {
            background: #3B82F6;
            box-shadow: 0px 10px 15px -3px rgba(59, 130, 246, 0.3);
        }

        .btn-primary-solid:hover {
            background: #2563EB;
        }

        /* Icon Circles */
        .icon-circle-blue {
            background: #3B82F6;
            box-shadow: 0px 10px 15px -3px rgba(59, 130, 246, 0.3);
        }

        .icon-circle-green {
            background: #22C55E;
            box-shadow: 0px 10px 15px -3px rgba(34, 197, 94, 0.3);
        }

        .icon-circle-purple {
            background: #8B5CF6;
            box-shadow: 0px 10px 15px -3px rgba(139, 92, 246, 0.3);
        }

        /* Stat Cards */
        .stat-card-blue {
            background: #EFF6FF;
            border: 1px solid #BFDBFE;
        }

        .stat-card-green {
            background: #ECFDF5;
            border: 1px solid #A7F3D0;
        }

        .stat-card-orange {
            background: #FFF7ED;
            border: 1px solid #FED7AA;
        }

        .stat-card-purple {
            background: #FAF5FF;
            border: 1px solid #E9D5FF;
        }

        /* Stat Icons (Transparent backgrounds) */
        .stat-icon-blue {
            background: rgba(59, 130, 246, 0.2);
            border: 1px solid rgba(59, 130, 246, 0.4);
        }

        .stat-icon-green {
            background: rgba(34, 197, 94, 0.2);
            border: 1px solid rgba(34, 197, 94, 0.4);
        }

        .stat-icon-orange {
            background: rgba(249, 115, 22, 0.2);
            border: 1px solid rgba(249, 115, 22, 0.4);
        }

        .stat-icon-purple {
            background: rgba(168, 85, 247, 0.2);
            border: 1px solid rgba(168, 85, 247, 0.4);
        }

        .header-solid {
            background: rgba(59, 130, 246, 0.08);
            border-bottom: 1px solid #E2E8F0;
        }

        .info-box {
            background: #1E40AF;
            border: 1px solid rgba(59, 130, 246, 0.3);
            box-shadow: 0px 4px 20px -4px rgba(30, 58, 138, 0.5);
        }

        .chat-widget {
            background: #3B82F6;
            box-shadow: 0px 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        /* Toggle button styles */
        .toggle-btn {
            transition: transform 0.3s ease;
        }

        .toggle-btn.rotated {
            transform: rotate(180deg);
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

<body class="antialiased overflow-x-hidden">
    <div class="institution-bg flex relative overflow-x-hidden">
        <!-- Blur decorations removed -->
        <div class="hidden md:block"></div>
        <div class="hidden md:block"></div>

        <!-- Mobile Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

        <!-- Sidebar -->
        <aside class="sidebar h-screen flex flex-col fixed left-0 top-0 z-40 overflow-hidden" id="sidebar">
            <!-- Logo Section -->
            <div class="p-4 border-b border-white/10">
                <div class="flex items-center justify-between logo-section">
                    <a href="{{ route('home') }}"
                        class="flex items-center gap-3 hover:opacity-80 transition group flex-1 min-w-0">
                        <!-- SertiKu Logo -->
                        <img src="{{ asset('favicon.ico') }}" alt="SertiKu"
                            class="w-10 h-10 flex-shrink-0 group-hover:scale-105 transition"
                            style="filter: brightness(0) invert(1);">
                        <div class="logo-text min-w-0">
                            <h1 class="text-white font-bold text-xl tracking-tight drop-shadow-lg truncate">SertiKu</h1>
                            <p class="text-[#BEDBFF] text-xs truncate">Dashboard Lembaga</p>
                        </div>
                    </a>
                    <!-- Desktop collapse button -->
                    <button onclick="toggleSidebar()"
                        class="hidden lg:flex text-white/70 hover:text-white p-2 toggle-btn" id="toggleBtn">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                        </svg>
                    </button>
                    <!-- Mobile close button -->
                    <button onclick="toggleSidebar()" class="lg:hidden text-white/70 hover:text-white p-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <!-- Dashboard -->
                <a href="{{ route('lembaga.dashboard') }}"
                    class="nav-item flex items-center gap-3 px-4 py-4 rounded-xl {{ request()->routeIs('lembaga.dashboard') ? 'active' : '' }}"
                    title="Dashboard">
                    <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                                d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 13a1 1 0 011-1h4a1 1 0 011 1v6a1 1 0 01-1 1h-4a1 1 0 01-1-1v-6z" />
                        </svg>
                    </div>
                    <span class="text-white/70 text-base nav-text">Dashboard</span>
                </a>

                <!-- Terbitkan Sertifikat -->
                @if(Auth::user()->canIssueCertificate())
                    <a href="{{ route('lembaga.sertifikat.create') }}"
                        class="nav-item flex items-center gap-3 px-4 py-4 rounded-xl {{ request()->routeIs('lembaga.sertifikat.create') ? 'active' : '' }}"
                        title="Terbitkan Sertifikat">
                        <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span class="text-white/70 text-base nav-text">Terbitkan Sertifikat</span>
                    </a>
                @else
                    <div class="nav-item flex items-center gap-3 px-4 py-4 rounded-xl opacity-50 cursor-not-allowed group relative"
                        title="Kuota sertifikat habis">
                        <div class="w-9 h-9 rounded-lg bg-red-500/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                        </div>
                        <span class="text-red-400/70 text-base nav-text">Kuota Habis</span>
                        <!-- Tooltip -->
                        <div class="absolute left-full ml-2 hidden group-hover:block z-50">
                            <div class="bg-gray-900 text-white text-xs rounded-lg px-3 py-2 whitespace-nowrap shadow-lg">
                                Kuota melebihi limit, perpanjang langganan
                                <a href="{{ url('/#harga') }}" class="text-blue-400 underline ml-1">Upgrade</a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Daftar Sertifikat -->
                <a href="{{ route('lembaga.sertifikat.index') }}"
                    class="nav-item flex items-center gap-3 px-4 py-4 rounded-xl {{ request()->routeIs('lembaga.sertifikat.index') ? 'active' : '' }}"
                    title="Daftar Sertifikat">
                    <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                    </div>
                    <span class="text-white/70 text-base nav-text">Daftar Sertifikat</span>
                </a>

                <!-- AI Template Generator - Professional Only -->
                @if(Auth::user()->isProfessionalPlan() || Auth::user()->isEnterprisePlan())
                    <!-- <a href="{{ route('lembaga.template.ai') }}"
                        class="nav-item flex items-center gap-3 px-4 py-4 rounded-xl {{ request()->routeIs('lembaga.template.ai') ? 'active' : '' }}"
                        title="AI Template">
                        <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <span class="text-white/70 text-base nav-text">AI Template</span>
                    </a> -->
                @endif

                <!-- Upload Template (User) -->
                <a href="{{ route('lembaga.template.upload') }}"
                    class="nav-item flex items-center gap-3 px-4 py-4 rounded-xl {{ request()->routeIs('lembaga.template.upload') ? 'active' : '' }}"
                    title="Upload Template">
                    <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                    </div>
                    <span class="text-white/70 text-base nav-text">Upload Template</span>
                </a>

                <!-- Import Data (Bulk) - Professional Only -->
                @if(Auth::user()->isProfessionalPlan() || Auth::user()->isEnterprisePlan())
                    @if(Auth::user()->canIssueCertificate())
                        <a href="{{ route('lembaga.sertifikat.bulk') }}"
                            class="nav-item flex items-center gap-3 px-4 py-4 rounded-xl {{ request()->routeIs('lembaga.sertifikat.bulk') ? 'active' : '' }}"
                            title="Import Data (Bulk)">
                            <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                            </div>
                            <span class="text-white/70 text-base nav-text">Import Data (Bulk)</span>
                        </a>
                    @else
                        <div class="nav-item flex items-center gap-3 px-4 py-4 rounded-xl opacity-50 cursor-not-allowed group relative"
                            title="Kuota sertifikat habis">
                            <div class="w-9 h-9 rounded-lg bg-red-500/20 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                            </div>
                            <span class="text-red-400/70 text-base nav-text">Kuota Habis</span>
                            <!-- Tooltip -->
                            <div class="absolute left-full ml-2 hidden group-hover:block z-50">
                                <div class="bg-gray-900 text-white text-xs rounded-lg px-3 py-2 whitespace-nowrap shadow-lg">
                                    Kuota sertifikat bulan ini habis
                                    <a href="{{ url('/#harga') }}" class="text-blue-400 underline ml-1">Upgrade</a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                <!-- Galeri Sertifikat -->
                <a href="{{ route('lembaga.template.index') }}"
                    class="nav-item flex items-center gap-3 px-4 py-4 rounded-xl {{ request()->routeIs('lembaga.template.index') ? 'active' : '' }}"
                    title="Galeri Sertifikat">
                    <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <span class="text-white/70 text-base nav-text">Galeri Sertifikat</span>
                </a>



                <!-- Beri Feedback -->
                <a href="{{ route('feedback.create') }}"
                    class="nav-item flex items-center gap-3 px-4 py-4 rounded-xl {{ request()->routeIs('feedback*') ? 'active' : '' }}"
                    title="Beri Feedback">
                    <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                    <span class="text-white/70 text-base nav-text">Beri Feedback</span>
                </a>
            </nav>

            <!-- Profile Section -->
            <div class="mt-auto flex-shrink-0 p-4 border-t border-white/10">
                <div class="flex items-center gap-3 mb-4 profile-section">
                    @if(Auth::user()->avatar && (str_starts_with(Auth::user()->avatar, '/storage/') || str_starts_with(Auth::user()->avatar, 'http')))
                        <img src="{{ Auth::user()->avatar }}" alt="Avatar"
                            class="w-10 h-10 rounded-full object-cover flex-shrink-0">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->institution_name ?? Auth::user()->name) }}&email={{ urlencode(Auth::user()->email) }}&background=3B82F6&color=fff&bold=true&size=40"
                            alt="Avatar" class="w-10 h-10 rounded-full object-cover flex-shrink-0">
                    @endif
                    <div class="flex-1 min-w-0 profile-text">
                        <p class="text-white text-sm font-medium truncate">
                            {{ Auth::user()->institution_name ?? Auth::user()->name }}
                        </p>
                        <p class="text-[#94A3B8] text-xs truncate">
                            @if(Auth::user()->hasWalletLogin())
                                {{ substr(Auth::user()->email, 0, 8) }}...{{ substr(Auth::user()->email, -15) }}
                            @else
                                {{ Auth::user()->email }}
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Settings Button -->
                <a href="{{ route('lembaga.settings') }}"
                    class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white/70 hover:bg-white/10 hover:text-white transition mb-3"
                    title="Pengaturan">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="text-sm nav-text">Pengaturan</span>
                </a>

                <!-- Hubungi Admin Button -->
                <a href="{{ route('contact.admin') }}"
                    class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-green-500/10 border border-green-500/30 rounded-lg text-green-400 hover:bg-green-500/20 transition mb-3"
                    title="Hubungi Admin">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                            d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="text-sm nav-text">Hubungi Admin</span>
                </a>

                <!-- Logout Button -->
                <form method="POST" action="{{ route('logout') }}"
                    onsubmit="return confirmAction(event, 'Apakah Anda yakin ingin keluar?')">
                    @csrf
                    <button type="submit"
                        class="logout-btn w-full flex items-center justify-center gap-2 px-4 py-3 bg-red-500/10 border border-red-500/30 rounded-lg text-red-400 hover:bg-red-500/20 transition"
                        title="Keluar">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="text-sm">Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Mobile Menu Button -->
        <button onclick="toggleSidebar()"
            class="md:hidden fixed top-4 left-4 z-50 p-2 bg-[#0F172A] border border-white/10 rounded-lg text-white"
            id="mobileMenuBtn">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Main Content -->
        <main class="main-content flex-1 p-4 pt-16 md:pt-6 lg:p-8 relative z-10 overflow-x-hidden max-w-full w-full"
            id="mainContent">
            <div class="max-w-full overflow-x-hidden">
                {{ $slot }}
            </div>
        </main>

        <!-- Chat Widget -->
        <div id="chatWidget" class="fixed bottom-6 right-6 z-50">
            <!-- Chat Popup -->
            <div id="chatPopup"
                class="hidden absolute bottom-16 right-0 w-80 max-h-[480px] bg-[#0F172A] rounded-2xl shadow-2xl border border-white/10 overflow-hidden flex flex-col">
                <!-- Chat Header -->
                <div class="bg-[#3B82F6] px-4 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-white font-bold text-sm">SertiKu Support</p>
                            <p class="text-white/70 text-xs">Bantuan Cepat 24/7</p>
                        </div>
                    </div>
                    <button onclick="toggleChatPopup()" class="text-white/70 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Chat Messages -->
                <div id="chatMessages" class="flex-1 overflow-y-auto p-4 space-y-3"
                    style="min-height: 200px; max-height: 280px;">
                    <!-- Welcome Message -->
                    <div class="flex gap-2">
                        <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2L3 7l7 5 7-5-7-5z" />
                                <path d="M3 12l7 5 7-5" />
                            </svg>
                        </div>
                        <div class="bg-white/10 rounded-lg rounded-tl-none px-3 py-2 max-w-[85%]">
                            <p class="text-white text-sm">Halo! Saya asisten virtual SertiKu. Pilih topik di bawah
                                atau ketik pertanyaan Anda.</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Templates -->
                <div id="quickTemplates" class="px-4 pb-3 space-y-2">
                    <p class="text-white/50 text-xs mb-2">Pertanyaan Populer:</p>
                    <div class="flex flex-wrap gap-2">
                        <button onclick="askQuestion('cara_verifikasi')"
                            class="chat-template-btn px-3 py-1.5 bg-blue-500/20 border border-blue-500/30 rounded-lg text-blue-300 text-xs hover:bg-blue-500/30 transition">
                            Cara Verifikasi
                        </button>
                        <button onclick="askQuestion('upload_sertifikat')"
                            class="chat-template-btn px-3 py-1.5 bg-green-500/20 border border-green-500/30 rounded-lg text-green-300 text-xs hover:bg-green-500/30 transition">
                            Upload Sertifikat
                        </button>
                        <button onclick="askQuestion('upgrade_paket')"
                            class="chat-template-btn px-3 py-1.5 bg-purple-500/20 border border-purple-500/30 rounded-lg text-purple-300 text-xs hover:bg-purple-500/30 transition">
                            Upgrade Paket
                        </button>
                        <button onclick="askQuestion('qr_code')"
                            class="chat-template-btn px-3 py-1.5 bg-cyan-500/20 border border-cyan-500/30 rounded-lg text-cyan-300 text-xs hover:bg-cyan-500/30 transition">
                            QR Code
                        </button>
                        <button onclick="askQuestion('tips_keamanan')"
                            class="chat-template-btn px-3 py-1.5 bg-yellow-500/20 border border-yellow-500/30 rounded-lg text-yellow-300 text-xs hover:bg-yellow-500/30 transition">
                            Tips Keamanan
                        </button>
                        <button onclick="askQuestion('hubungi_admin')"
                            class="chat-template-btn px-3 py-1.5 bg-red-500/20 border border-red-500/30 rounded-lg text-red-300 text-xs hover:bg-red-500/30 transition">
                            Hubungi Admin
                        </button>
                    </div>
                </div>

                <!-- Chat Input -->
                <div class="p-3 border-t border-white/10 flex-shrink-0">
                    <div class="flex gap-2 items-center">
                        <input type="text" id="chatInput" placeholder="Ketik pesan..."
                            onkeypress="if(event.key==='Enter')sendCustomMessage()"
                            class="flex-1 min-w-0 bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white text-sm placeholder:text-white/40 focus:outline-none focus:border-blue-500">
                        <button onclick="sendCustomMessage()"
                            class="bg-blue-600 hover:bg-blue-700 rounded-lg p-2 text-white transition flex-shrink-0 min-w-[40px] flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Chat Button -->
            <button onclick="toggleChatPopup()" id="chatButton"
                class="chat-widget w-14 h-14 rounded-full flex items-center justify-center text-white hover:scale-105 transition group relative">
                <svg id="chatIcon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <svg id="closeIcon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span
                    class="absolute bottom-full right-0 mb-2 px-3 py-2 bg-gray-900 text-white text-sm rounded-lg opacity-0 group-hover:opacity-100 transition whitespace-nowrap pointer-events-none">
                    Butuh bantuan? Chat dengan kami!
                </span>
                <!-- Notification dot -->
                <!-- <span class="absolute top-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-[#0F172A]"></span> -->
            </button>
        </div>
    </div>

    <script>
        let isCollapsed = false;
        let isMobile = window.innerWidth < 768;

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const overlay = document.getElementById('sidebarOverlay');
            const toggleBtn = document.getElementById('toggleBtn');
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');

            if (isMobile) {
                // Mobile: slide in/out
                sidebar.classList.toggle('open');
                overlay.classList.toggle('active');
                // Hide/show hamburger menu
                if (mobileMenuBtn) {
                    mobileMenuBtn.classList.toggle('hidden');
                }
            } else {
                // Desktop: collapse/expand
                isCollapsed = !isCollapsed;
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
                toggleBtn.classList.toggle('rotated');
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

        // ========== CHAT WIDGET FUNCTIONS ==========
        let isChatOpen = false;

        const chatResponses = {
            'cara_verifikasi': {
                question: 'Bagaimana cara verifikasi sertifikat?',
                answer: '<strong>Cara Verifikasi Sertifikat:</strong><br><br>1. Buka halaman <a href="/verifikasi" class="text-blue-400 underline">Verifikasi</a><br>2. Masukkan kode hash atau nomor sertifikat (SERT-XXXXXX)<br>3. Klik tombol "Verifikasi"<br>4. Sistem akan menampilkan informasi sertifikat jika valid<br><br><em>Tip: Anda juga bisa scan QR Code pada sertifikat!</em>'
            },
            'upload_sertifikat': {
                question: 'Cara upload sertifikat baru?',
                answer: '<strong>Cara Upload Sertifikat:</strong><br><br>1. Pergi ke menu "Upload Sertifikat"<br>2. Upload gambar template sertifikat (JPG/PNG)<br>3. Isi data penerima (nama, email, dll)<br>4. Pilih tanggal terbit<br>5. Klik "Terbitkan Sertifikat"<br><br>Sertifikat akan otomatis diberi nomor unik dan QR Code!'
            },
            'upgrade_paket': {
                question: 'Bagaimana cara upgrade paket?',
                answer: '<strong>Upgrade ke Professional:</strong><br><br>Keuntungan Paket Professional:<br>• Unlimited sertifikat/bulan<br>• Template kustom<br>• Analytics lengkap<br>• Priority support<br><br>Harga: Rp 399.000/bulan<br><br>Klik tombol "Upgrade" di dashboard atau hubungi admin untuk promo khusus!'
            },
            'qr_code': {
                question: 'Tentang QR Code sertifikat',
                answer: '<strong>QR Code Sertifikat:</strong><br><br>Setiap sertifikat yang diterbitkan akan otomatis mendapat QR Code yang berisi:<br>• Link verifikasi langsung<br>• Nomor sertifikat unik<br><br>QR Code dapat discan menggunakan HP untuk memverifikasi keaslian sertifikat secara instan!<br><br>QR Code akan muncul di halaman verifikasi dan PDF download.'
            },
            'tips_keamanan': {
                question: 'Tips keamanan sertifikat',
                answer: '<strong>Tips Keamanan:</strong><br><br>1. <strong>Jaga kerahasiaan akun</strong> - Jangan bagikan password<br>2. <strong>Verifikasi rutin</strong> - Cek sertifikat Anda secara berkala<br>3. <strong>Backup data</strong> - Download PDF sertifikat penting<br>4. <strong>Laporkan pemalsuan</strong> - Hubungi admin jika menemukan sertifikat palsu<br><br>SertiKu menggunakan enkripsi untuk melindungi data Anda!'
            },
            'hubungi_admin': {
                question: 'Hubungi Admin',
                answer: '<strong>Hubungi Kami:</strong><br><br>Email: <a href="mailto:support@sertiku.web.id" class="text-blue-400 underline">support@sertiku.web.id</a><br>WhatsApp: <a href="https://wa.me/6285777419874" class="text-blue-400 underline">+62 857-7741-9874</a><br>Jam Operasional: Senin-Jumat, 09:00-17:00 WIB<br><br>Atau kirim pesan langsung di chat ini dan kami akan segera merespons!'
            }
        };

        function toggleChatPopup() {
            const popup = document.getElementById('chatPopup');
            const chatButton = document.getElementById('chatButton');
            // const chatIcon = document.getElementById('chatIcon');
            // const closeIcon = document.getElementById('closeIcon');

            isChatOpen = !isChatOpen;
            popup.classList.toggle('hidden');
            chatButton.classList.toggle('hidden'); // Hide the launcher button
            // chatIcon.classList.toggle('hidden');
            // closeIcon.classList.toggle('hidden');
        }

        function askQuestion(key) {
            const response = chatResponses[key];
            if (!response) return;

            const messagesDiv = document.getElementById('chatMessages');

            // Add user message
            const userMsg = document.createElement('div');
            userMsg.className = 'flex gap-2 justify-end';
            userMsg.innerHTML = `
                <div class="bg-blue-600 rounded-lg rounded-tr-none px-3 py-2 max-w-[85%]">
                    <p class="text-white text-sm">${response.question}</p>
                </div>
            `;
            messagesDiv.appendChild(userMsg);

            // Scroll to bottom
            messagesDiv.scrollTop = messagesDiv.scrollHeight;

            // Add typing indicator
            const typingIndicator = document.createElement('div');
            typingIndicator.className = 'flex gap-2 typing-indicator';
            typingIndicator.innerHTML = `
                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2L3 7l7 5 7-5-7-5z"/>
                    </svg>
                </div>
                <div class="bg-white/10 rounded-lg rounded-tl-none px-3 py-2">
                    <div class="flex gap-1">
                        <span class="w-2 h-2 bg-white/50 rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                        <span class="w-2 h-2 bg-white/50 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                        <span class="w-2 h-2 bg-white/50 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                    </div>
                </div>
            `;
            messagesDiv.appendChild(typingIndicator);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;

            // Simulate typing delay then show response
            setTimeout(() => {
                messagesDiv.removeChild(typingIndicator);

                const botMsg = document.createElement('div');
                botMsg.className = 'flex gap-2';
                botMsg.innerHTML = `
                    <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2L3 7l7 5 7-5-7-5z"/>
                        </svg>
                    </div>
                    <div class="bg-white/10 rounded-lg rounded-tl-none px-3 py-2 max-w-[85%]">
                        <p class="text-white text-sm leading-relaxed">${response.answer}</p>
                    </div>
                `;
                messagesDiv.appendChild(botMsg);
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            }, 1000);
        }

        async function sendCustomMessage() {
            const input = document.getElementById('chatInput');
            const message = input.value.trim();
            if (!message) return;

            const messagesDiv = document.getElementById('chatMessages');

            // Add user message
            const userMsg = document.createElement('div');
            userMsg.className = 'flex gap-2 justify-end';
            userMsg.innerHTML = `
                <div class="bg-blue-600 rounded-lg rounded-tr-none px-3 py-2 max-w-[85%]">
                    <p class="text-white text-sm">${escapeHtml(message)}</p>
                </div>
            `;
            messagesDiv.appendChild(userMsg);
            input.value = '';
            messagesDiv.scrollTop = messagesDiv.scrollHeight;

            // Show typing indicator
            const typingIndicator = document.createElement('div');
            typingIndicator.id = 'lembagaTypingIndicator';
            typingIndicator.className = 'flex gap-2';
            typingIndicator.innerHTML = `
                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2L3 7l7 5 7-5-7-5z"/>
                    </svg>
                </div>
                <div class="bg-white/10 rounded-lg rounded-tl-none px-3 py-2">
                    <div class="flex gap-1">
                        <span class="w-2 h-2 bg-white/50 rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                        <span class="w-2 h-2 bg-white/50 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                        <span class="w-2 h-2 bg-white/50 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                    </div>
                </div>
            `;
            messagesDiv.appendChild(typingIndicator);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;

            try {
                const response = await fetch('/api/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        message: message,
                        role: 'lembaga',
                    }),
                });

                // Remove typing indicator
                const indicator = document.getElementById('lembagaTypingIndicator');
                if (indicator) indicator.remove();

                if (!response.ok) {
                    console.error('Chat API error:', response.status, response.statusText);
                    throw new Error('API response not ok');
                }

                const data = await response.json();
                console.log('Chat response:', data);

                // Add bot response
                const botMsg = document.createElement('div');
                botMsg.className = 'flex gap-2';
                botMsg.innerHTML = `
                    <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2L3 7l7 5 7-5-7-5z"/>
                        </svg>
                    </div>
                    <div class="bg-white/10 rounded-lg rounded-tl-none px-3 py-2 max-w-[85%]">
                        <div class="text-white text-sm leading-relaxed">${formatMarkdown(data.reply) || 'Maaf, terjadi kesalahan.'}</div>
                    </div>
                `;
                messagesDiv.appendChild(botMsg);
                messagesDiv.scrollTop = messagesDiv.scrollHeight;

            } catch (error) {
                console.error('Chat error:', error);

                // Remove typing indicator
                const indicator = document.getElementById('lembagaTypingIndicator');
                if (indicator) indicator.remove();

                // Show error message
                const botMsg = document.createElement('div');
                botMsg.className = 'flex gap-2';
                botMsg.innerHTML = `
                    <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2L3 7l7 5 7-5-7-5z"/>
                        </svg>
                    </div>
                    <div class="bg-white/10 rounded-lg rounded-tl-none px-3 py-2 max-w-[85%]">
                        <p class="text-white text-sm">Maaf, terjadi kesalahan koneksi. Silakan coba lagi.</p>
                    </div>
                `;
                messagesDiv.appendChild(botMsg);
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            }
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Format markdown untuk output AI yang lebih rapih
        function formatMarkdown(text) {
            if (!text) return '';

            return text
                // Bold: **text** atau __text__
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/__(.*?)__/g, '<strong>$1</strong>')
                // Italic: *text* atau _text_
                .replace(/\*([^*]+)\*/g, '<em>$1</em>')
                .replace(/_([^_]+)_/g, '<em>$1</em>')
                // Numbered list: 1. item
                .replace(/^\d+\.\s+(.*)$/gm, '<li class="ml-4 list-decimal">$1</li>')
                // Bullet list: - item atau * item
                .replace(/^[\-\*]\s+(.*)$/gm, '<li class="ml-4 list-disc">$1</li>')
                // Headers: ## Header
                .replace(/^###\s+(.*)$/gm, '<strong class="text-blue-300">$1</strong>')
                .replace(/^##\s+(.*)$/gm, '<strong class="text-blue-300 text-base">$1</strong>')
                // Line breaks
                .replace(/\n\n/g, '<br><br>')
                .replace(/\n/g, '<br>')
                // Links
                .replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" class="text-blue-400 underline" target="_blank">$1</a>');
        }
    </script>
    {{-- SweetAlert Session --}}
    <x-sweetalert-session />

    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Global Form Validation with SweetAlert2
        document.addEventListener('DOMContentLoaded', function () {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                // Skip forms that have their own submit handler (like certificate-form)
                if (form.id === 'certificate-form') return;

                // Add novalidate to prevent native browser tooltips
                form.setAttribute('novalidate', true);

                form.addEventListener('submit', function (e) {
                    if (!this.checkValidity()) {
                        e.preventDefault();
                        e.stopImmediatePropagation();

                        const firstInvalid = this.querySelector(':invalid');
                        if (firstInvalid) {
                            // Get label text for better error message
                            const label = firstInvalid.closest('.space-y-2')?.querySelector('label')?.textContent?.replace('*', '').trim()
                                || firstInvalid.name || 'Field';

                            Swal.fire({
                                icon: 'warning',
                                title: 'Mohon Lengkapi Data',
                                text: `${label} wajib diisi`,
                                confirmButtonColor: '#3B82F6',
                                background: '#0f172a',
                                color: '#fff'
                            }).then(() => {
                                setTimeout(() => firstInvalid.focus(), 300);
                            });
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