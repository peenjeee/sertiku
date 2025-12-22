@extends('errors.layout')

@section('code', '404')
@section('title', 'Halaman Tidak Ditemukan')
@section('message', 'Halaman yang Anda cari tidak ditemukan atau telah dipindahkan. Pastikan URL yang Anda masukkan benar.')

@section('icon')
    <div class="error-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
            stroke-linejoin="round">
            <circle cx="11" cy="11" r="8" />
            <path d="m21 21-4.35-4.35" />
            <path d="M11 8v2" />
            <path d="M11 14h.01" />
        </svg>
    </div>
@endsections