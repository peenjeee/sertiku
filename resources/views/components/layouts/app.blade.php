@props(['title' => 'SertiKu'])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Alpine JS dari CDN --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#050C1F] text-[#DBEAFE] antialiased">

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
</body>
</html>
