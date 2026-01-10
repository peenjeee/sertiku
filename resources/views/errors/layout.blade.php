<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Error') - SertiKu</title>
    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700,800" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #0A1628;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #F9FAFB;
            overflow: hidden;
        }

        .container {
            text-align: center;
            padding: 2rem;
            max-width: 600px;
            position: relative;
            z-index: 10;
        }

        .error-code {
            font-size: 10rem;
            font-weight: 800;
            color: #8B5CF6;
            line-height: 1;
            text-shadow: 0 0 80px rgba(43, 127, 255, 0.4);
            animation: pulse 3s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.9;
                transform: scale(1.02);
            }
        }

        .error-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 1rem;
            background: rgba(139, 92, 246, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid rgba(43, 127, 255, 0.3);
            animation: float 3s ease-in-out infinite;
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

        .error-icon svg {
            width: 60px;
            height: 60px;
            color: #3B82F6;
        }

        .error-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: #F9FAFB;
        }

        .error-message {
            font-size: 1rem;
            color: rgba(190, 219, 255, 0.7);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .btn-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 1.75rem;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #3B82F6;
            color: white;
            box-shadow: 0 20px 40px -20px rgba(37, 99, 235, 0.5);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 25px 50px -20px rgba(37, 99, 235, 0.7);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: #F9FAFB;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        /* Background decorations */
        .bg-decoration {
            position: fixed;
            pointer-events: none;
        }

        .bg-circle-1 {
            top: -200px;
            right: -200px;
            width: 500px;
            height: 500px;

            display: none;
            border-radius: 50%;
        }

        .bg-circle-2 {
            bottom: -150px;
            left: -150px;
            width: 400px;
            height: 400px;

            display: none;
            border-radius: 50%;
        }

        .bg-grid {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;

            background: transparent;
            background-size: 50px 50px;
            pointer-events: none;
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 2rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: #F9FAFB;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: #3B82F6;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (max-width: 640px) {
            .error-code {
                font-size: 6rem;
            }

            .error-icon {
                width: 80px;
                height: 80px;
            }

            .error-icon svg {
                width: 40px;
                height: 40px;
            }

            .error-title {
                font-size: 1.25rem;
            }

            .error-message {
                font-size: 0.875rem;
            }

            .btn-group {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <div class="bg-grid"></div>
    <div class="bg-decoration bg-circle-1"></div>
    <div class="bg-decoration bg-circle-2"></div>

    <div class="container">
        <div class="logo">
            <div class="logo-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 12L11 14L15 10M12 3L4 7V11C4 16.55 7.16 21.74 12 23C16.84 21.74 20 16.55 20 11V7L12 3Z"
                        stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            SertiKu
        </div>

        @yield('icon')

        <h1 class="error-code">@yield('code', '???')</h1>
        <h2 class="error-title">@yield('title', 'Terjadi Kesalahan')</h2>
        <p class="error-message">
            @yield('message', 'Maaf, terjadi kesalahan yang tidak terduga. Silakan coba lagi nanti.')</p>

        <div class="btn-group">
            <a href="{{ url('/') }}" class="btn btn-primary">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    <polyline points="9 22 9 12 15 12 15 22" />
                </svg>
                Kembali ke Beranda
            </a>
            <a href="javascript:history.back()" class="btn btn-secondary">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12" />
                    <polyline points="12 19 5 12 12 5" />
                </svg>
                Kembali
            </a>
        </div>
    </div>
</body>

</html>