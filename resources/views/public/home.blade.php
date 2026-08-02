@extends('layouts.app')
@section('title', 'Beranda')
@section('content')

  {{-- HERO --}}
    <section class="relative bg-white overflow-hidden">
       <div class="absolute inset-0 bg-cover bg-center opacity-[0.35]" style="background-image: url('{{ asset('images/uptd2.jpg') }}');"></div>
       <div class="absolute inset-0 bg-navy/80 mix-blend-multiply"></div>
       <div class="absolute inset-0 bg-gradient-to-b from-white/10 via-transparent to-white"></div>

        <div class="relative z-10 max-w-5xl mx-auto px-6 pt-16 pb-10 text-center">
            <p class="inline-flex items-center gap-1.5 text-xs font-semibold tracking-[0.2em] text-health uppercase mb-4 bg-white/80 backdrop-blur-sm px-3 py-1.5 rounded-full shadow-sm">
                <span class="w-1.5 h-1.5 rounded-full bg-health"></span> Program Magang Resmi
            </p>
           <h1 class="font-display font-extrabold text-3xl md:text-5xl leading-tight text-white drop-shadow-sm">
             Mulai Langkah <span class="text-gradient-animated">Profesionalmu</span> di Dunia Kesehatan
            </h1>

            <div class="flex flex-wrap justify-center gap-x-4 gap-y-3 mt-8">
                <div class="flex items-center gap-3 text-left bg-white/85 backdrop-blur-sm px-4 py-2.5 rounded-xl shadow-sm">
                    <span class="w-9 h-9 shrink-0 rounded-full bg-health/10 flex items-center justify-center text-health">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                    <p class="text-sm text-gray-700 leading-snug">
                        Proses pengajuan<br class="hidden sm:block"> 100% online
                    </p>
                </div>
                <div class="flex items-center gap-3 text-left bg-white/85 backdrop-blur-sm px-4 py-2.5 rounded-xl shadow-sm">
                    <span class="w-9 h-9 shrink-0 rounded-full bg-gold/10 flex items-center justify-center text-gold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    <p class="text-sm text-gray-700 leading-snug">
                        Instansi resmi Dinas<br class="hidden sm:block"> Kesehatan Jawa Barat
                    </p>
                </div>
            </div>
        </div>

        {{-- BANNER FOTO --}}
        <div class="relative z-10 max-w-5xl mx-auto px-6 pb-14">
            <div class="relative rounded-2xl overflow-hidden shadow-lg">
                <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/uptd.jpg') }}');"></div>
                <div class="absolute inset-0 bg-navy/30 mix-blend-multiply"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-navy via-navy/40 to-transparent"></div>

                <div class="relative px-6 md:px-10 py-12 md:py-16">
                    <div class="flex items-center gap-3 mb-8">
                        <img src="{{ asset('images/logo.jpg') }}" alt="Logo UPTD" class="w-10 h-10 rounded-full object-cover ring-2 ring-gold-light/60">
                        <span class="font-display font-bold text-white tracking-wide">UPELKES</span>
                    </div>

                    <h2 class="font-display font-bold text-2xl md:text-3xl text-white leading-snug max-w-md">
                        Selamat Datang<br>
                        di <span class="text-orange">UPTD Pelatihan Kesehatan</span>
                    </h2>

                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('pengajuan.create') }}"
                           class="bg-health hover:bg-health-dark transition text-white font-semibold px-6 py-3 rounded-md text-sm">
                            Ajukan Magang Sekarang
                        </a>
                        <a href="{{ route('cek-status') }}"
                           class="border border-white/40 hover:bg-white/10 transition font-semibold px-6 py-3 rounded-md text-sm text-white">
                            Cek Status Pengajuan
                        </a>
                    </div>
                </div>
            </div>

            <div class="flex justify-center gap-10 mt-8">
                <div class="text-center">
                    <p class="font-display font-extrabold text-2xl text-navy">100%</p>
                    <p class="text-xs text-gray-500 mt-1">Proses Online</p>
                </div>
                <div class="text-center">
                    <p class="font-display font-extrabold text-2xl text-navy">6</p>
                    <p class="text-xs text-gray-500 mt-1">Bidang Tersedia</p>
                </div>
                <div class="text-center">
                    <p class="font-display font-extrabold text-2xl text-navy">24/7</p>
                    <p class="text-xs text-gray-500 mt-1">Cek Status</p>
                </div>
            </div>
        </div>
    </section>

{{-- TAHAPAN --}}
    <section class="max-w-6xl mx-auto px-6 pt-12 pb-24">
        <div class="text-center max-w-xl mx-auto mb-14">
            <p class="text-xs font-semibold tracking-[0.2em] text-health uppercase mb-2">Tahapan</p>
            <h2 class="font-display font-bold text-2xl md:text-3xl text-navy mb-3">
                Tahapan Mengikuti Program Magang
            </h2>
            <p class="text-gray-500 text-sm">
                Ikuti empat langkah berikut untuk menyelesaikan proses pengajuan magangmu.
            </p>
        </div>

        <div class="relative grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- garis penghubung, cuma muncul di layar besar --}}
            <div class="hidden lg:block absolute top-9 left-[12.5%] right-[12.5%] h-px bg-gradient-to-r from-navy via-health to-gold"></div>

            <div class="relative bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all p-6">
                <div class="w-11 h-11 rounded-xl bg-navy text-white font-display font-bold flex items-center justify-center mb-5 shadow-md shadow-navy/20 relative z-10">
                    1
                </div>
                <p class="font-display font-semibold text-navy text-sm mb-2">Pendaftaran</p>
                <p class="text-xs text-gray-500 leading-relaxed">Isi formulir pengajuan magang secara online, tanpa perlu datang langsung.</p>
            </div>

            <div class="relative bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all p-6">
                <div class="w-11 h-11 rounded-xl bg-health text-white font-display font-bold flex items-center justify-center mb-5 shadow-md shadow-health/20 relative z-10">
                    2
                </div>
                <p class="font-display font-semibold text-navy text-sm mb-2">Unggah Dokumen</p>
                <p class="text-xs text-gray-500 leading-relaxed">Lampirkan surat pengantar kampus, pas foto, dan dokumen pendukung lain.</p>
            </div>

            <div class="relative bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all p-6">
                <div class="w-11 h-11 rounded-xl bg-gold text-navy font-display font-bold flex items-center justify-center mb-5 shadow-md shadow-gold/30 relative z-10">
                    3
                </div>
                <p class="font-display font-semibold text-navy text-sm mb-2">Verifikasi</p>
                <p class="text-xs text-gray-500 leading-relaxed">Pengajuan diperiksa oleh admin UPTD Pelatihan Kesehatan.</p>
            </div>

            <div class="relative bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all p-6">
                <div class="w-11 h-11 rounded-xl bg-navy text-white font-display font-bold flex items-center justify-center mb-5 shadow-md shadow-navy/20 relative z-10">
                    4
                </div>
                <p class="font-display font-semibold text-navy text-sm mb-2">Cek Status</p>
                <p class="text-xs text-gray-500 leading-relaxed">Pantau hasil pengajuan kapan saja lewat menu Cek Status.</p>
            </div>
        </div>
    </section>
    
   {{-- PERSYARATAN --}}
    <section class="max-w-6xl mx-auto px-6 pb-24">
        <div class="text-center max-w-xl mx-auto mb-14">
            <p class="text-xs font-semibold tracking-wider text-health uppercase mb-2">Persyaratan</p>
            <h2 class="font-display font-bold text-2xl md:text-3xl text-navy">Yang Perlu Disiapkan</h2>
        </div>

        <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-5">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition p-5 text-center">
                <div class="w-11 h-11 rounded-lg bg-navy/5 flex items-center justify-center text-navy mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <p class="font-display font-semibold text-navy text-sm mb-1">Surat Pengantar</p>
                <p class="text-xs text-gray-500">Wajib, dari kampus, format PDF</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition p-5 text-center">
                <div class="w-11 h-11 rounded-lg bg-health/10 flex items-center justify-center text-health mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M4 8h16M4 8a2 2 0 00-2 2v8a2 2 0 002 2h16a2 2 0 002-2v-8a2 2 0 00-2-2M4 8V6a2 2 0 012-2h12a2 2 0 012 2v2" />
                    </svg>
                </div>
                <p class="font-display font-semibold text-navy text-sm mb-1">Pas Foto</p>
                <p class="text-xs text-gray-500">Wajib, JPG/PNG, terbaru</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition p-5 text-center">
                <div class="w-11 h-11 rounded-lg bg-gold/10 flex items-center justify-center text-gold mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <p class="font-display font-semibold text-navy text-sm mb-1">CV</p>
                <p class="text-xs text-gray-500">Opsional, format PDF</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition p-5 text-center">
                <div class="w-11 h-11 rounded-lg bg-navy/5 flex items-center justify-center text-navy mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="font-display font-semibold text-navy text-sm mb-1">Proposal</p>
                <p class="text-xs text-gray-500">Opsional, sesuai kampus</p>
            </div>
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('persyaratan') }}" class="text-sm font-medium text-navy hover:text-health underline underline-offset-4">
                Lihat Persyaratan Lengkap →
            </a>
        </div>
    </section>
    

@endsection