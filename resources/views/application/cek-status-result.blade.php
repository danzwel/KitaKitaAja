@extends('layouts.app')
@section('title', 'Hasil Status Pengajuan')
@section('content')
<div class="max-w-md mx-auto px-6 py-16">

    <div class="text-center mb-8">
        <p class="text-xs font-semibold tracking-wider text-health uppercase mb-2">Status Pengajuan</p>
        <h1 class="font-display font-bold text-2xl md:text-3xl text-navy">Hasil Pengecekan</h1>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
        <div class="flex justify-between items-center pb-4 border-b border-gray-100">
            <span class="text-sm text-gray-500">Nomor Pengajuan</span>
            <span class="font-display font-semibold text-navy">{{ $application->application_code }}</span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-sm text-gray-500">Nama</span>
            <span class="text-sm font-medium text-gray-800">{{ $application->nama }}</span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-sm text-gray-500">Universitas</span>
            <span class="text-sm font-medium text-gray-800">{{ $application->universitas }}</span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-sm text-gray-500">Periode Magang</span>
            <span class="text-sm font-medium text-gray-800">
                {{ $application->periode_mulai->format('d M Y') }} - {{ $application->periode_selesai->format('d M Y') }}
            </span>
        </div>

        @php
            $statusStyle = match($application->status) {
                'diterima' => 'bg-health/10 text-health',
                'ditolak' => 'bg-red-100 text-red-700',
                'diproses' => 'bg-gold/10 text-gold',
                default => 'bg-gray-100 text-gray-600',
            };
        @endphp
        <div class="pt-4 border-t border-gray-100 text-center">
            <span class="inline-block px-4 py-1.5 rounded-full text-sm font-semibold {{ $statusStyle }}">
                {{ str_replace('_', ' ', ucwords($application->status)) }}
            </span>
        </div>

        @if ($application->status === 'diterima' && $application->intern)
            <div class="mt-6 rounded-lg bg-health/10 p-4 text-sm text-gray-700">
                <p class="font-semibold text-health">Akun Dashboard Mahasiswa</p>
                <div class="mt-3 space-y-2">
                    <p><span class="text-gray-500">Username:</span> <strong>{{ $application->intern->username }}</strong></p>
                    <p><span class="text-gray-500">Password awal:</span> <strong>{{ $application->intern->temporary_initial_password }}</strong></p>
                </div>
                <p class="mt-3">Gunakan akun berikut untuk login ke Dashboard Mahasiswa. Setelah login pertama, segera ubah password.</p>
                <a href="{{ route('intern.login') }}" class="mt-4 inline-block font-semibold text-health underline">Login Mahasiswa</a>
            </div>
        @endif
    </div>

    <div class="text-center mt-6">
        <a href="{{ route('cek-status') }}" class="text-sm text-navy underline hover:text-health">Cek pengajuan lain</a>
    </div>
</div>
@endsection
