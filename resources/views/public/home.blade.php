@extends('layouts.app')
@section('title', 'Beranda')
@section('content')

    {{-- HERO --}}
    <section class="relative bg-navy text-white overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/uptd.jpg') }}');"></div>
<div class="absolute inset-0 bg-navy/40 mix-blend-multiply"></div>
<div class="absolute inset-0 bg-gradient-to-r from-navy via-navy/60 to-transparent"></div>
<div class="absolute inset-0 bg-gradient-to-t from-navy/90 via-transparent to-navy/20"></div>
        <div class="absolute -right-24 -top-24 w-96 h-96 rounded-full bg-health/20 blur-3xl"></div>
        <div class="absolute -left-24 bottom-0 w-72 h-72 rounded-full bg-gold/10 blur-3xl"></div>

        <div class="relative max-w-6xl mx-auto px-6 py-24">
            <p class="inline-block text-xs font-semibold tracking-wider text-gold-light uppercase border border-gold-light/40 rounded-full px-3 py-1 mb-5 animate-fade-up">
                Program Magang Resmi
            </p>
            <h1 class="font-display font-extrabold text-4xl md:text-5xl leading-tight max-w-2xl animate-fade-up delay-1">
                Mulai Langkah <span class="text-gradient-animated">Profesionalmu</span> di Dunia Kesehatan
            </h1>
            <p class="mt-5 text-gray-300 text-base leading-relaxed max-w-xl animate-fade-up delay-2">
                Ajukan magang di UPTD Pelatihan Kesehatan Dinas Kesehatan Provinsi Jawa Barat
                secara online. Cukup isi formulir dan unggah dokumen, tanpa perlu datang langsung.
            </p>
            <div class="mt-8 flex flex-wrap gap-3 animate-fade-up delay-3">
                <a href="{{ route('pengajuan.create') }}"
                   class="bg-health hover:bg-health-dark transition text-white font-semibold px-6 py-3 rounded-md">
                    Ajukan Magang Sekarang
                </a>
                <a href="{{ route('cek-status') }}"
                   class="border border-white/30 hover:bg-white/10 transition font-semibold px-6 py-3 rounded-md">
                    Cek Status Pengajuan
                </a>
            </div>

            <div class="mt-14 flex gap-10 animate-fade-up delay-3">
                <div>
                    <p class="font-display font-extrabold text-3xl text-gold-light">100%</p>
                    <p class="text-sm text-gray-300 mt-1">Proses Online</p>
                </div>
                <div>
                    <p class="font-display font-extrabold text-3xl text-gold-light">6</p>
                    <p class="text-sm text-gray-300 mt-1">Bidang Magang Tersedia</p>
                </div>
                <div>
                    <p class="font-display font-extrabold text-3xl text-gold-light">24/7</p>
                    <p class="text-sm text-gray-300 mt-1">Cek Status Kapan Saja</p>
                </div>
            </div>
        </div>
    </section>

    {{-- TAHAPAN --}}
    <section class="max-w-6xl mx-auto px-6 py-24">
        <div class="grid md:grid-cols-2 gap-14 items-center">

            {{-- ILUSTRASI --}}
            <div class="relative flex items-center justify-center">
                <div class="absolute w-64 h-64 rounded-full bg-health/5 border border-health/10"></div>
                <div class="absolute w-44 h-44 rounded-full bg-gold/5 border border-gold/10 -translate-x-10 translate-y-8"></div>

                <svg viewBox="0 0 240 240" class="relative w-64 h-64 animate-float">
                    <rect x="60" y="30" width="120" height="160" rx="14" fill="white" stroke="#0B1F3D" stroke-width="3"/>
                    <line x1="82" y1="66" x2="158" y2="66" stroke="#B8912F" stroke-width="4" stroke-linecap="round"/>
                    <line x1="82" y1="88" x2="158" y2="88" stroke="#E5E7EB" stroke-width="4" stroke-linecap="round"/>
                    <line x1="82" y1="108" x2="140" y2="108" stroke="#E5E7EB" stroke-width="4" stroke-linecap="round"/>
                    <circle cx="170" cy="150" r="34" fill="#1F7A4D"/>
                    <path d="M156 150l10 10 20-20" stroke="white" stroke-width="5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>

            {{-- KONTEN TAHAPAN --}}
            <div>
                <p class="text-xs font-semibold tracking-[0.2em] text-health uppercase mb-2">Tahapan</p>
                <h2 class="font-display font-bold text-2xl md:text-3xl text-navy mb-3">
                    Tahapan Mengikuti Program Magang
                </h2>
                <p class="text-gray-500 text-sm mb-8 max-w-md">
                    Ikuti empat langkah berikut untuk menyelesaikan proses pengajuan magangmu.
                </p>

                <div class="grid sm:grid-cols-2 gap-6">
                    <div class="flex gap-3">
                        <span class="w-8 h-8 shrink-0 rounded-lg bg-navy text-white text-sm font-display font-bold flex items-center justify-center">1</span>
                        <div>
                            <p class="font-display font-semibold text-navy text-sm mb-1">Pendaftaran</p>
                            <p class="text-xs text-gray-500 leading-relaxed">Isi formulir pengajuan magang secara online.</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <span class="w-8 h-8 shrink-0 rounded-lg bg-health text-white text-sm font-display font-bold flex items-center justify-center">2</span>
                        <div>
                            <p class="font-display font-semibold text-navy text-sm mb-1">Unggah Dokumen</p>
                            <p class="text-xs text-gray-500 leading-relaxed">Lampirkan surat pengantar kampus dalam format PDF.</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <span class="w-8 h-8 shrink-0 rounded-lg bg-gold text-navy text-sm font-display font-bold flex items-center justify-center">3</span>
                        <div>
                            <p class="font-display font-semibold text-navy text-sm mb-1">Verifikasi</p>
                            <p class="text-xs text-gray-500 leading-relaxed">Pengajuan diperiksa oleh admin UPTD Pelatihan Kesehatan.</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <span class="w-8 h-8 shrink-0 rounded-lg bg-navy text-white text-sm font-display font-bold flex items-center justify-center">4</span>
                        <div>
                            <p class="font-display font-semibold text-navy text-sm mb-1">Cek Status</p>
                            <p class="text-xs text-gray-500 leading-relaxed">Pantau hasil pengajuan kapan saja secara online.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- PERSYARATAN --}}
    <section class="max-w-6xl mx-auto px-6 pb-24">
        <div class="text-center max-w-xl mx-auto mb-14">
            <p class="text-xs font-semibold tracking-wider text-health uppercase mb-2">Persyaratan</p>
            <h2 class="font-display font-bold text-2xl md:text-3xl text-navy">Yang Perlu Disiapkan</h2>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition p-6">
                <div class="w-12 h-12 rounded-lg bg-navy/5 flex items-center justify-center text-navy mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <p class="font-display font-semibold text-navy mb-1">Surat Pengantar Kampus</p>
                <p class="text-sm text-gray-600">Wajib diunggah dalam format PDF, maksimal 2MB.</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition p-6">
                <div class="w-12 h-12 rounded-lg bg-health/10 flex items-center justify-center text-health mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <p class="font-display font-semibold text-navy mb-1">CV Terbaru</p>
                <p class="text-sm text-gray-600">Bersifat opsional, membantu proses verifikasi.</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition p-6">
                <div class="w-12 h-12 rounded-lg bg-gold/10 flex items-center justify-center text-gold mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="font-display font-semibold text-navy mb-1">Proposal Magang</p>
                <p class="text-sm text-gray-600">Opsional, sesuai ketentuan program studi masing-masing.</p>
            </div>
        </div>
    </section>

    {{-- CTA BAWAH --}}
    <section class="bg-health text-white">
        <div class="max-w-6xl mx-auto px-6 py-14 text-center">
            <h2 class="font-display font-bold text-2xl mb-3">Siap Mengajukan Magang?</h2>
            <p class="text-white/90 mb-6">Prosesnya cepat, cukup 5-10 menit untuk mengisi formulir.</p>
            <a href="{{ route('pengajuan.create') }}"
               class="inline-block bg-white text-health font-semibold px-6 py-3 rounded-md hover:bg-gray-100 transition">
                Ajukan Magang Sekarang
            </a>
        </div>
    </section>

@endsection