@props([
    'title' => 'SertiKu',
    'description' => 'Platform terdepan untuk menerbitkan, mengelola, dan memverifikasi sertifikat digital dengan teknologi QR Code dan blockchain.',
    'keywords' => 'sertifikat digital, verifikasi sertifikat, QR code, blockchain, e-sertifikat',
    'image' => null
])

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- SEO Meta Tags --}}
    <x-seo-meta :title="$title" :description="$description" :keywords="$keywords" :image="$image" />

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    {{-- PWA Manifest --}}
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#3B82F6">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="SertiKu">

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
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
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

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
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

        /* Hide scrollbar for Chrome, Safari and Opera */
        ::-webkit-scrollbar {
            display: none;
        }

        /* Hide scrollbar for IE, Edge and Firefox */
        html {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #0f172a;
        }
    </style>
</head>

<body class="bg-[#050C1F] text-[#DBEAFE] antialiased font-['Poppins',sans-serif] overflow-x-hidden w-screen">

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
    {{-- SweetAlert untuk session messages --}}
    <x-sweetalert-session />

    {{-- Scroll Animation Script --}}
    <style>
        /* Scroll Animation - Hidden by default */
        .scroll-animate {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        .scroll-animate.animate-left {
            transform: translateX(-30px);
        }

        .scroll-animate.animate-right {
            transform: translateX(30px);
        }

        .scroll-animate.animate-scale {
            transform: scale(0.9);
        }

        .scroll-animate.animate-rotate {
            transform: rotate(-5deg) scale(0.95);
        }

        /* When element is visible */
        .scroll-animate.is-visible {
            opacity: 1;
            transform: translateY(0) translateX(0) scale(1) rotate(0);
        }

        /* Stagger delays for scroll animations */
        .scroll-delay-1 {
            transition-delay: 0.1s;
        }

        .scroll-delay-2 {
            transition-delay: 0.2s;
        }

        .scroll-delay-3 {
            transition-delay: 0.3s;
        }

        .scroll-delay-4 {
            transition-delay: 0.4s;
        }

        .scroll-delay-5 {
            transition-delay: 0.5s;
        }

        .scroll-delay-6 {
            transition-delay: 0.6s;
        }

        /* Counter Animation */
        .count-up {
            transition: all 0.3s ease;
        }

        /* Parallax Effect */
        .parallax {
            will-change: transform;
        }

        /* Bounce Animation */
        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-15px);
            }

            60% {
                transform: translateY(-8px);
            }
        }

        .animate-bounce-slow {
            animation: bounce 2s ease-in-out infinite;
        }

        /* Shimmer Effect for loading states */
        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }

            100% {
                background-position: 200% 0;
            }
        }

        .shimmer {
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }

        /* Glow Pulse */
        @keyframes glow-pulse {

            0%,
            100% {
                box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
            }

            50% {
                box-shadow: 0 0 40px rgba(59, 130, 246, 0.6);
            }
        }

        .animate-glow-pulse {
            animation: glow-pulse 2s ease-in-out infinite;
        }

        /* Text Gradient Animate */
        @keyframes gradient-shift {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        .animate-gradient-text {
            background: linear-gradient(90deg, #3B82F6, #8B5CF6, #EC4899, #3B82F6);
            background-size: 300% 100%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradient-shift 3s ease infinite;
        }

        /* Card 3D Tilt on Hover */
        .card-3d {
            transform-style: preserve-3d;
            perspective: 1000px;
            transition: transform 0.3s ease;
        }

        .card-3d:hover {
            transform: rotateY(5deg) rotateX(5deg);
        }

        /* Ripple Effect */
        .ripple {
            position: relative;
            overflow: hidden;
        }

        .ripple::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .ripple:active::after {
            width: 300px;
            height: 300px;
        }
    </style>

    <script>
        // Intersection Observer for Scroll Animations
        document.addEventListener('DOMContentLoaded', function () {
            const scrollElements = document.querySelectorAll('.scroll-animate');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });

            scrollElements.forEach(el => observer.observe(el));

            // Counter Animation for stats
            const counters = document.querySelectorAll('.count-up');
            const counterObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const target = entry.target;
                        const endValue = parseInt(target.getAttribute('data-count'));
                        const suffix = target.getAttribute('data-suffix') || '';
                        const duration = 2000;
                        const startTime = performance.now();

                        function updateCounter(currentTime) {
                            const elapsed = currentTime - startTime;
                            const progress = Math.min(elapsed / duration, 1);
                            const easeOut = 1 - Math.pow(1 - progress, 3);
                            const currentValue = Math.floor(endValue * easeOut);
                            target.textContent = currentValue.toLocaleString() + suffix;

                            if (progress < 1) {
                                requestAnimationFrame(updateCounter);
                            }
                        }

                        requestAnimationFrame(updateCounter);
                        counterObserver.unobserve(target);
                    }
                });
            }, { threshold: 0.5 });

            counters.forEach(counter => counterObserver.observe(counter));

            // Smooth parallax on scroll
            const parallaxElements = document.querySelectorAll('.parallax');
            window.addEventListener('scroll', () => {
                const scrollY = window.scrollY;
                parallaxElements.forEach(el => {
                    const speed = el.getAttribute('data-speed') || 0.5;
                    el.style.transform = `translateY(${scrollY * speed}px)`;
                });
            });
        });
    </script>

    @stack('scripts')

    {{-- Cookie Consent Popup --}}
    <x-cookie-consent />

    {{-- PWA Install Prompt --}}
    <x-pwa-install-prompt />

    {{-- Notification Permission Prompt --}}
    <x-notification-prompt />

    {{-- Service Worker Registration --}}
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then((registration) => {
                        console.log('[SW] Registered:', registration.scope);
                    })
                    .catch((error) => {
                        console.log('[SW] Registration failed:', error);
                    });
            });
         }
    </script>
    <script>
        // Global Form Validation with SweetAlert2
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                // Add novalidate to prevent native browser tooltips
                form.setAttribute('novalidate', true);

                form.addEventListener('submit', function(e) {
                    if (!this.checkValidity()) {
                        e.preventDefault();
                        e.stopImmediatePropagation();

                        const firstInvalid = this.querySelector(':invalid');
                        if (firstInvalid) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Mohon Lengkapi Data',
                                text: firstInvalid.validationMessage,
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Global SweetAlert Confirmation
        window.confirmAction = function(e, message) {
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