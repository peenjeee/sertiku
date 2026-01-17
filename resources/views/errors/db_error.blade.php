@extends('errors.layout')

@section('code', '503')
@section('title', 'Layanan Tidak Tersedia')
@section('message', 'Maaf, saat ini sistem sedang mengalami gangguan koneksi. Mohon coba beberapa saat lagi.')

@section('icon')
    <div class="error-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
            stroke-linejoin="round">
            <path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
            <path d="M3 3 21 21" />
            <path d="M12 21a9 9 0 0 0 9-9 9.75 9.75 0 0 0-6.74-2.74L12 12" />
            <path d="M3 16a9 9 0 0 0 3 5" />
            <path d="M8 12 12 8" />
        </svg>
    </div>
@endsection