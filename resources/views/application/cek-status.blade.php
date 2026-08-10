@extends('layouts.app')
@section('title', 'Cek Status Pengajuan')
@section('content')
<div class="relative isolate overflow-hidden bg-gradient-to-b from-[#E8EEF5] via-[#EEF1F5] to-[#EEF1F5]">
    <div class="pointer-events-none absolute -right-24 top-24 h-64 w-64 rounded-full bg-[#1F7A4D]/[0.06]"></div>
    <div class="pointer-events-none absolute -left-20 top-8 h-56 w-56 rounded-full border-[30px] border-[#0C2340]/[0.06]"></div>
<div class="relative max-w-md mx-auto px-6 pt-28 pb-24">

    {{-- HEADER --}}
    <div class="text-center mb-10">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-navy to-navy-light text-gold-light mb-5 shadow-lg shadow-navy/20">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
            </svg>
        </div>
        <p class="text-xs font-semibold tracking-[0.2em] text-health uppercase mb-2">Status Pengajuan</p>
        <h1 class="font-display font-bold text-3xl md:text-4xl text-navy">Cek Status</h1>
        <div class="w-14 h-1 bg-gradient-to-r from-gold to-health rounded-full mx-auto mt-4 mb-4"></div>
        <p class="text-gray-500 text-sm max-w-xs mx-auto">Masukkan nomor pengajuan dan email yang kamu daftarkan sebelumnya.</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg mb-6 text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="relative bg-white rounded-2xl border border-gray-100 shadow-sm p-6 pt-8">
        <div class="absolute -top-3 -left-3 w-9 h-9 rounded-full bg-health text-white text-xs font-display font-bold flex items-center justify-center shadow-md shadow-health/30">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
            </svg>
        </div>
        <div class="h-1 w-12 bg-health rounded-full mb-5"></div>
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-health/10 ring-1 ring-health/20 flex items-center justify-center text-health">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <p class="font-display font-semibold text-navy text-lg">Cari Pengajuan</p>
        </div>

        <form action="{{ route('cek-status.result') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Pengajuan</label>
                <input type="text" name="application_code" value="{{ old('application_code') }}"
                       placeholder="Contoh: MAG20260001"
                       class="w-full border border-gray-200 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-health/40 focus:border-health transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       placeholder="email@contoh.com"
                       class="w-full border border-gray-200 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-health/40 focus:border-health transition">
            </div>
            <button type="submit"
                    class="w-full bg-navy hover:bg-navy-light transition text-white font-semibold py-3 rounded-lg shadow-md shadow-navy/20">
                Cek Status
            </button>
        </form>
    </div>
</div>
</div>
@endsection
