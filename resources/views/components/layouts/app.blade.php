@props(['title' => 'SertiKu'])

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    {{-- Google Fonts - Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Alpine JS dari CDN --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Global Animation Styles --}}
    <style>
        /* Keyframe Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
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

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* Animation Classes */
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }

        .animate-fade-in-down {
            animation: fadeInDown 0.5s ease-out forwards;
        }

        .animate-slide-in-left {
            animation: slideInLeft 0.5s ease-out forwards;
        }

        .animate-slide-in-right {
            animation: slideInRight 0.5s ease-out forwards;
        }

        .animate-scale-in {
            animation: scaleIn 0.4s ease-out forwards;
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        .live-dot {
            animation: pulse-dot 2s ease-in-out infinite;
        }

        /* Stagger Delays - only add delay, parent animation handles visibility */
        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }
        .stagger-4 { animation-delay: 0.4s; }
        .stagger-5 { animation-delay: 0.5s; }
        .stagger-6 { animation-delay: 0.6s; }

        /* Hover Effects */
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px -10px rgba(0, 0, 0, 0.3);
        }

        .hover-scale {
            transition: transform 0.3s ease;
        }

        .hover-scale:hover {
            transform: scale(1.03);
        }

        .hover-glow {
            transition: box-shadow 0.3s ease;
        }

        .hover-glow:hover {
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.4);
        }

        /* Smooth Transitions */
        .transition-smooth {
            transition: all 0.3s ease;
        }

        /* Glass Card Effect */
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, #3B82F6 0%, #8B5CF6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>

<body class="bg-[#050C1F] text-[#DBEAFE] antialiased font-['Poppins',sans-serif]">

    {{-- Background glow sesuai Figma --}}
    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
        {{-- kiri atas biru --}}
        <div class="absolute -left-40 top-0 h-80 w-80 rounded-full bg-[rgba(43,127,255,0.3)] blur-3xl"></div>
        {{-- kanan tengah biru --}}
        <div class="absolute -right-32 top-1/3 h-80 w-80 rounded-full bg-[rgba(0,184,219,0.25)] blur-3xl"></div>
        {{-- kanan bawah ungu --}}
        <div class="absolute right-0 bottom-0 h-80 w-80 rounded-full bg-[rgba(173,70,255,0.2)] blur-3xl"></div>
    </div>

    <x-navbar />

    <main class="pt-[80px]">
        {{ $slot }}
    </main>

    <x-footer />

    {{-- SweetAlert untuk session messages --}}
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#3085d6'
            });
        </script>
    @endif

    @if(session('warning'))
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: '{{ session('warning') }}',
                confirmButtonColor: '#f59e0b'
            });
        </script>
    @endif

    @if(session('info'))
        <script>
            Swal.fire({
                icon: 'info',
                title: 'Informasi',
                text: '{{ session('info') }}',
                confirmButtonColor: '#3085d6'
            });
        </script>
    @endif

    @stack('scripts')
</body>

</html>
