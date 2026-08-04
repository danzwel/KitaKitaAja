@extends('layouts.app')
@section('title', 'Kontak')
@section('content')

    {{-- HEADER --}}
    <section class="bg-navy text-white">
        <div class="max-w-5xl mx-auto px-6 py-16 text-center">
            <p class="inline-flex items-center gap-1.5 text-xs font-semibold tracking-[0.2em] text-gold-light uppercase mb-3">
                <span class="w-1.5 h-1.5 rounded-full bg-gold-light"></span> Hubungi Kami
            </p>
            <h1 class="font-display font-bold text-3xl md:text-4xl">Kontak</h1>
            <p class="mt-4 text-gray-300 text-sm max-w-lg mx-auto">
                Ada pertanyaan seputar magang? Hubungi kami melalui kontak resmi berikut.
            </p>
        </div>
    </section>

    <div class="max-w-5xl mx-auto px-6 -mt-8 pb-24">
        <div class="grid md:grid-cols-3 gap-5">

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 flex flex-col">
                <div class="w-11 h-11 rounded-lg bg-navy/5 flex items-center justify-center text-navy mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <p class="font-display font-semibold text-navy text-sm mb-1">Alamat</p>
                <p class="text-sm text-gray-600 leading-relaxed flex-1">
                    Jl. Pasteur No.31, Pasir Kaliki, Kec. Cicendo, Kota Bandung, Jawa Barat 40171
                </p>
                <a href="https://www.google.com/maps/search/?api=1&query=UPTD+Pelatihan+Kesehatan+Dinas+Kesehatan+Provinsi+Jawa+Barat+Jl+Pasteur+No+31+Bandung"
                   target="_blank" rel="noopener"
                   class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-navy hover:text-health transition">
                    Buka di Google Maps
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 flex flex-col">
                <div class="w-11 h-11 rounded-lg bg-health/10 flex items-center justify-center text-health mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <p class="font-display font-semibold text-navy text-sm mb-1">Email</p>
                <p class="text-sm text-gray-600 flex-1">upelkes@jabarprov.go.id</p>
                <a href="https://mail.google.com/mail/?view=cm&fs=1&to=upelkes@jabarprov.go.id&su=Pertanyaan%20Seputar%20Magang"
                   target="_blank" rel="noopener"
                   class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-navy hover:text-health transition">
                    Kirim Email
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 flex flex-col">
                <div class="w-11 h-11 rounded-lg bg-gold/10 flex items-center justify-center text-gold mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.05 11.05 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </div>
                <p class="font-display font-semibold text-navy text-sm mb-1">Telepon</p>
                <p class="text-sm text-gray-600 flex-1">0224238422</p>
                <a href="tel:0224238422"
                   class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-navy hover:text-health transition">
                    Telepon Sekarang
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>
        </div>

        {{-- JAM LAYANAN --}}
        <div class="bg-navy text-white rounded-2xl p-8 mt-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-gold-light">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h2 class="font-display font-semibold text-lg">Jam Layanan</h2>
            </div>
            <div class="grid sm:grid-cols-2 gap-4 text-sm text-gray-300">
                <div class="flex justify-between border-b border-white/10 pb-2">
                    <span>Senin - Kamis</span>
                    <span class="font-medium text-white">08.00 - 15.30 WIB</span>
                </div>
                <div class="flex justify-between border-b border-white/10 pb-2">
                    <span>Jumat</span>
                    <span class="font-medium text-white">08.00 - 16.00 WIB</span>
                </div>
                <div class="flex justify-between">
                    <span>Sabtu - Minggu</span>
                    <span class="font-medium text-white">Tutup</span>
                </div>
            </div>
        </div>

        <div class="text-center mt-10">
            <a href="{{ route('pengajuan.create') }}"
               class="inline-block bg-health hover:bg-health-dark transition text-white font-semibold px-8 py-3 rounded-lg shadow-md shadow-health/30">
                Ajukan Magang Sekarang
            </a>
        </div>
    </div>

@endsection