<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Lembaga - SertiKu</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arimo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { font-family: 'Arimo', sans-serif; }

        .institution-bg {
            background: linear-gradient(180deg, #0F172A 0%, #1E293B 50%, #0F172A 100%);
            min-height: 100vh;
        }

        .sidebar {
            background: #0F172A;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
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

        /* Light/White cards for content */
        .glass-card {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            box-shadow: 0px 4px 6px -1px rgba(0, 0, 0, 0.1);
            border-radius: 16px;
        }

        /* Quick action cards with colored backgrounds */
        .gradient-card-blue {
            background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
            border: 1px solid #BFDBFE;
        }

        .gradient-card-green {
            background: linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%);
            border: 1px solid #A7F3D0;
        }

        .gradient-card-purple {
            background: linear-gradient(135deg, #FAF5FF 0%, #F3E8FF 100%);
            border: 1px solid #E9D5FF;
        }

        .welcome-banner {
            background: linear-gradient(90deg, #155DFC 0%, #1447E6 50%, #372AAC 100%);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0px 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .welcome-banner::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(0, 0, 0, 0) 100%);
            border-radius: 24px;
            pointer-events: none;
        }

        .btn-primary-gradient {
            background: linear-gradient(90deg, #155DFC 0%, #1447E6 100%);
            box-shadow: 0px 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .btn-primary-gradient:hover {
            opacity: 0.9;
        }

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

        /* Light stats cards */
        .stat-card-blue {
            background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
            border: 1px solid #BFDBFE;
        }

        .stat-card-green {
            background: linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%);
            border: 1px solid #A7F3D0;
        }

        .stat-card-orange {
            background: linear-gradient(135deg, #FFF7ED 0%, #FFEDD5 100%);
            border: 1px solid #FED7AA;
        }

        .stat-card-purple {
            background: linear-gradient(135deg, #FAF5FF 0%, #F3E8FF 100%);
            border: 1px solid #E9D5FF;
        }

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

        .header-gradient {
            background: linear-gradient(90deg, rgba(43, 127, 255, 0.08) 0%, rgba(97, 95, 255, 0.08) 100%);
            border-bottom: 1px solid #E2E8F0;
        }

        .info-box {
            background: linear-gradient(90deg, rgba(43, 127, 255, 0.1) 0%, rgba(97, 95, 255, 0.1) 100%);
            border: 1px solid rgba(43, 127, 255, 0.3);
            box-shadow: 0px 10px 20px -5px rgba(0, 0, 0, 0.1);
        }

        .chat-widget {
            background: linear-gradient(180deg, #1E3A8A 0%, #3B82F6 100%);
            box-shadow: 0px 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .blur-circle-1 {
            position: fixed;
            width: 384px;
            height: 384px;
            left: 155px;
            top: 87px;
            background: linear-gradient(90deg, rgba(0, 184, 219, 0.15) 0%, rgba(43, 127, 255, 0.15) 100%);
            filter: blur(64px);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        .blur-circle-2 {
            position: fixed;
            width: 384px;
            height: 384px;
            right: 100px;
            bottom: 50px;
            background: linear-gradient(90deg, rgba(43, 127, 255, 0.1) 0%, rgba(97, 95, 255, 0.1) 100%);
            filter: blur(64px);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }
    </style>
</head>
<body class="antialiased">
    <div class="institution-bg flex relative">
        <!-- Blur decorations -->
        <div class="blur-circle-1"></div>
        <div class="blur-circle-2"></div>

        <!-- Sidebar -->
        <aside class="sidebar w-72 min-h-screen flex flex-col fixed left-0 top-0 z-40">
            <!-- Logo Section -->
            <div class="p-4 border-b border-white/10">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <!-- SertiKu Logo -->
                        <img src="{{ asset('favicon.svg') }}" alt="SertiKu" class="w-10 h-10">
                        <div>
                            <h1 class="text-white font-bold text-xl tracking-tight drop-shadow-lg">SertiKu</h1>
                            <p class="text-[#BEDBFF] text-xs">Dashboard Lembaga</p>
                        </div>
                    </div>
                    <button class="text-white/70 hover:text-white p-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <!-- Dashboard -->
                <a href="{{ route('lembaga.dashboard') }}"
                   class="nav-item flex items-center gap-3 px-4 py-4 rounded-xl {{ request()->routeIs('lembaga.dashboard') ? 'active' : '' }}">
                    <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 13a1 1 0 011-1h4a1 1 0 011 1v6a1 1 0 01-1 1h-4a1 1 0 01-1-1v-6z"/>
                        </svg>
                    </div>
                    <span class="text-white/70 text-base">Dashboard</span>
                </a>

                <!-- Terbitkan Sertifikat -->
                <a href="{{ route('lembaga.sertifikat.create') }}"
                   class="nav-item flex items-center gap-3 px-4 py-4 rounded-xl {{ request()->routeIs('lembaga.sertifikat.create') ? 'active' : '' }}">
                    <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="text-white/70 text-base">Terbitkan Sertifikat</span>
                </a>

                <!-- Daftar Sertifikat -->
                <a href="{{ route('lembaga.sertifikat.index') }}"
                   class="nav-item flex items-center gap-3 px-4 py-4 rounded-xl {{ request()->routeIs('lembaga.sertifikat.index') ? 'active' : '' }}">
                    <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                    </div>
                    <span class="text-white/70 text-base">Daftar Sertifikat</span>
                </a>

                <!-- Galeri Template -->
                <a href="{{ route('lembaga.template.index') }}"
                   class="nav-item flex items-center gap-3 px-4 py-4 rounded-xl {{ request()->routeIs('lembaga.template.index') ? 'active' : '' }}">
                    <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-white/70 text-base">Galeri Template</span>
                </a>

                <!-- Upload Template -->
                <a href="{{ route('lembaga.template.upload') }}"
                   class="nav-item flex items-center gap-3 px-4 py-4 rounded-xl {{ request()->routeIs('lembaga.template.upload') ? 'active' : '' }}">
                    <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                    </div>
                    <span class="text-white/70 text-base">Upload Template</span>
                </a>
            </nav>

            <!-- Profile Section -->
            <div class="p-4 border-t border-white/10">
                <div class="flex items-center gap-3 mb-4">
                    @if(Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover">
                    @else
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr(Auth::user()->institution_name ?? Auth::user()->name ?? 'L', 0, 1)) }}
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
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

                <!-- Logout Button -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-red-500/10 border border-red-500/30 rounded-lg text-red-400 hover:bg-red-500/20 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span class="text-sm">Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-72 p-8 relative z-10">
            {{ $slot }}
        </main>

        <!-- Chat Widget -->
        <button class="chat-widget fixed bottom-6 right-6 w-14 h-14 rounded-full flex items-center justify-center text-white z-50 hover:scale-105 transition group">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <span class="absolute bottom-full right-0 mb-2 px-3 py-2 bg-gray-900 text-white text-sm rounded-lg opacity-0 group-hover:opacity-100 transition whitespace-nowrap">
                Butuh bantuan? Chat dengan kami! 💬
            </span>
        </button>
    </div>
</body>
</html>
