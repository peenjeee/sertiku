@extends('errors.layout')

@section('code', '401')
@section('title', 'Tidak Terautentikasi')
@section('message', 'Anda harus login terlebih dahulu untuk mengakses halaman ini. Silakan login atau daftar untuk melanjutkan.')

@section('icon')
    <div class="error-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
            stroke-linejoin="round">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
            <circle cx="12" cy="7" r="4" />
            <line x1="18" y1="8" x2="23" y2="13" />
            <line x1="23" y1="8" x2="18" y2="13" />
        </svg>
    </div>
@endsection