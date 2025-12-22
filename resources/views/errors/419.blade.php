@extends('errors.layout')

@section('code', '419')
@section('title', 'Sesi Kedaluwarsa')
@section('message', 'Sesi Anda telah kedaluwarsa. Silakan refresh halaman dan coba lagi.')

@section('icon')
    <div class="error-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
            stroke-linejoin="round">
            <circle cx="12" cy="12" r="10" />
            <polyline points="12 6 12 12 16 14" />
        </svg>
    </div>
@endsection