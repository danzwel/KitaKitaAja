@extends('layouts.app')
@section('title', 'Pengajuan Berhasil')
@section('content')
    <section class="min-h-[calc(100vh-18rem)] px-4 pb-16 pt-32 sm:px-6 sm:pb-24">
        <div class="mx-auto max-w-2xl">
            <div class="overflow-hidden rounded-2xl bg-white shadow-xl shadow-navy/10 ring-1 ring-gray-100">
                <div class="relative overflow-hidden bg-navy px-6 py-9 text-center text-white sm:px-10">
                    <div class="absolute -right-16 -top-20 h-48 w-48 rounded-full bg-gold/20"></div>
                    <div class="absolute -bottom-16 -left-10 h-32 w-32 rounded-full bg-health/20"></div>
                    <div class="relative mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-health text-white shadow-lg shadow-black/15 ring-4 ring-white/15">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4.2 4.2L19 6.5" />
                        </svg>
                    </div>
                    <p class="relative mt-4 text-xs font-semibold uppercase tracking-[0.2em] text-gold-light">Pengajuan Magang</p>
                    <h1 class="relative mt-2 font-display text-2xl font-bold sm:text-3xl">Pengajuan berhasil dikirim</h1>
                    <p class="relative mx-auto mt-2 max-w-md text-sm text-gray-300">Data dan dokumen Anda telah kami terima untuk proses verifikasi.</p>
                </div>

                <div class="px-6 py-7 sm:px-10 sm:py-9">
                    <p class="text-center text-sm font-medium text-gray-500">Nomor pengajuan Anda</p>
                    <div class="mt-3 rounded-xl border border-gold/30 bg-gold/5 px-4 py-4 text-center sm:px-6">
                        <p class="select-all break-all font-display text-3xl font-bold tracking-[0.08em] text-navy sm:text-4xl">{{ $application->application_code }}</p>
                    </div>

                    <div class="mt-6 flex gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4 text-left text-sm text-amber-950">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 shrink-0 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM12 16.5h.008v.008H12V16.5Z" />
                        </svg>
                        <div>
                            <p class="font-semibold">Simpan nomor pengajuan ini</p>
                            <p class="mt-1 leading-relaxed text-amber-800">Ambil tangkapan layar atau catat nomor tersebut. Nomor ini diperlukan untuk melihat status pengajuan Anda.</p>
                        </div>
                    </div>

                    <div class="mt-7 grid gap-3 sm:grid-cols-2">
                        <a href="{{ route('cek-status') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-health px-5 py-3 text-sm font-semibold text-white shadow-sm shadow-health/30 transition hover:bg-health-dark">
                            Cek Status Pengajuan
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                        </a>
                        <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 px-5 py-3 text-sm font-semibold text-navy transition hover:border-navy/30 hover:bg-gray-50">
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>

            <p class="mt-6 text-center text-xs leading-relaxed text-gray-500">Status pengajuan dapat diperbarui setelah dokumen diperiksa oleh admin.</p>
        </div>
    </section>
@endsection
