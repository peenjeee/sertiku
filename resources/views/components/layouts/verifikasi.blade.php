<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SertiKu – Verifikasi Sertifikat' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F8FAFC] font-['Arimo']">

    {{-- konten halaman --}}
    {{ $slot }}

</body>
</html>
