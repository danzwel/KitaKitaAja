@extends('layouts.app')
@section('title', 'FAQ')
@section('content')

    {{-- HEADER --}}
    <section class="bg-navy text-white">
        <div class="max-w-5xl mx-auto px-6 py-16 text-center">
            <p class="inline-flex items-center gap-1.5 text-xs font-semibold tracking-[0.2em] text-gold-light uppercase mb-3">
                <span class="w-1.5 h-1.5 rounded-full bg-gold-light"></span> Pertanyaan Umum
            </p>
            <h1 class="font-display font-bold text-3xl md:text-4xl">FAQ</h1>
            <p class="mt-4 text-gray-300 text-sm max-w-lg mx-auto">
                Kumpulan pertanyaan yang sering ditanyakan seputar pengajuan magang.
            </p>
        </div>
    </section>

    <div class="max-w-3xl mx-auto px-6 -mt-8 pb-24">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm divide-y divide-gray-100" x-data="{ open: null }">

            @php
                $faqs = [
                    [
                        'q' => 'Apakah pengajuan magang dikenakan biaya?',
                        'a' => 'Tidak. Seluruh proses pengajuan magang di UPTD Pelatihan Kesehatan Dinas Kesehatan Provinsi Jawa Barat tidak dipungut biaya apa pun.',
                    ],
                    [
                        'q' => 'Berapa lama proses verifikasi pengajuan?',
                        'a' => 'Proses verifikasi umumnya memakan waktu 3-7 hari kerja setelah formulir dan dokumen lengkap diterima admin.',
                    ],
                    [
                        'q' => 'Bagaimana jika dokumen yang diunggah salah atau kurang lengkap?',
                        'a' => 'Silakan hubungi kontak yang tersedia di halaman Kontak untuk melakukan perbaikan data sebelum proses verifikasi selesai.',
                    ],
                    [
                        'q' => 'Apakah bisa mengajukan magang secara berkelompok?',
                        'a' => 'Bisa, selama setiap anggota kelompok tetap mengisi formulir pengajuan secara individu sesuai data masing-masing.',
                    ],
                    [
                        'q' => 'Bagaimana cara mengetahui hasil pengajuan?',
                        'a' => 'Gunakan menu Cek Status dengan memasukkan Nomor Pengajuan dan email yang telah didaftarkan sebelumnya.',
                    ],
                    [
                        'q' => 'Apakah portofolio wajib diunggah?',
                        'a' => 'Portofolio hanya wajib untuk bidang tertentu seperti Desain/Editor. Sistem akan menampilkan kolom unggah portofolio secara otomatis apabila bidang yang dipilih membutuhkannya.',
                    ],
                ];
            @endphp

            @foreach ($faqs as $i => $faq)
                <div>
                    <button type="button" @click="open === {{ $i }} ? open = null : open = {{ $i }}"
                            class="w-full flex items-center justify-between gap-4 text-left px-6 py-5 hover:bg-gray-50 transition">
                        <span class="font-display font-medium text-navy text-sm md:text-base">{{ $faq['q'] }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0 text-health transition-transform"
                             :class="open === {{ $i }} ? 'rotate-45' : ''"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </button>
                    <div x-show="open === {{ $i }}" x-collapse class="px-6 pb-5 text-sm text-gray-600 leading-relaxed">
                        {{ $faq['a'] }}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-10">
            <p class="text-sm text-gray-500 mb-3">Masih ada pertanyaan lain?</p>
            <a href="{{ route('kontak') }}"
               class="inline-block bg-navy hover:bg-navy-light transition text-white font-semibold px-6 py-3 rounded-lg">
                Hubungi Kami
            </a>
        </div>
    </div>

@endsection