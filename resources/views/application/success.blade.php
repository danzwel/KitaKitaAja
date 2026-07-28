@extends('layouts.app')
@section('title', 'Pengajuan Berhasil')
@section('content')
<div class="text-center py-16">
    <h1 class="text-2xl font-bold text-green-700">Pengajuan berhasil dikirim!</h1>
    <p class="mt-4">Nomor Pengajuan kamu:</p>
    <p class="text-3xl font-bold mt-2">{{ $application->application_code }}</p>
    <p class="mt-4 text-gray-600">Silakan simpan nomor ini untuk mengecek status pengajuan.</p>
    <a href="{{ route('cek-status') }}" class="inline-block mt-6 text-blue-700 underline">Cek Status Sekarang</a>
</div>
@endsection