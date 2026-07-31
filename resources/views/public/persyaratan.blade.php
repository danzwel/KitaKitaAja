@extends('layouts.app')
@section('title', 'Persyaratan Magang')
@section('content')

    {{-- HEADER --}}
    <section class="bg-navy text-white">
        <div class="max-w-5xl mx-auto px-6 py-16 text-center">
            <p class="inline-flex items-center gap-1.5 text-xs font-semibold tracking-[0.2em] text-gold-light uppercase mb-3">
                <span class="w-1.5 h-1.5 rounded-full bg-gold-light"></span> Sebelum Mengajukan
            </p>
            <h1 class="font-display font-bold text-3xl md:text-4xl">Persyaratan Magang</h1>
            <p class="mt-4 text-gray-300 text-sm max-w-lg mx-auto">
                Pastikan kamu sudah memenuhi semua ketentuan berikut sebelum mengisi formulir pengajuan.
            </p>
        </div>
    </section>

    <div class="max-w-5xl mx-auto px-6 -mt-8 pb-24 space-y-14">

        {{-- PERSYARATAN ADMINISTRASI --}}
        <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-navy/5 ring-1 ring-navy/10 flex items-center justify-center text-navy">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h2 class="font-display font-semibold text-navy text-lg">Persyaratan Administrasi</h2>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                @foreach ([
                    'Mahasiswa aktif dibuktikan dengan Kartu Tanda Mahasiswa (KTM)',
                    'Minimal telah menempuh semester 4',
                    'Berasal dari perguruan tinggi negeri/swasta terakreditasi',
                    'Mengajukan secara berkelompok maupun individu sesuai ketentuan program studi',
                    'Bersedia mengikuti tata tertib dan jam kerja instansi',
                    'Tidak sedang menjalani magang di instansi lain pada periode yang sama',
                ] as $item)
                    <div class="flex items-start gap-3">
                        <span class="w-5 h-5 shrink-0 rounded-full bg-health/10 text-health flex items-center justify-center mt-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <p class="text-sm text-gray-600">{{ $item }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- DOKUMEN YANG HARUS DISIAPKAN --}}
        <section>
            <div class="text-center max-w-lg mx-auto mb-8">
                <p class="text-xs font-semibold tracking-wider text-health uppercase mb-2">Dokumen</p>
                <h2 class="font-display font-bold text-2xl text-navy">Yang Harus Disiapkan</h2>
            </div>

            <div class="grid md:grid-cols-4 gap-5">
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 text-center">
                    <div class="w-11 h-11 rounded-lg bg-navy/5 flex items-center justify-center text-navy mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <p class="font-display font-semibold text-navy text-sm mb-1">Surat Pengantar</p>
                    <p class="text-xs text-gray-500">Wajib, dari kampus, format PDF</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 text-center">
                    <div class="w-11 h-11 rounded-lg bg-health/10 flex items-center justify-center text-health mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M4 8h16M4 8a2 2 0 00-2 2v8a2 2 0 002 2h16a2 2 0 002-2v-8a2 2 0 00-2-2M4 8V6a2 2 0 012-2h12a2 2 0 012 2v2" />
                        </svg>
                    </div>
                    <p class="font-display font-semibold text-navy text-sm mb-1">Pas Foto</p>
                    <p class="text-xs text-gray-500">Wajib, JPG/PNG, terbaru</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 text-center">
                    <div class="w-11 h-11 rounded-lg bg-gold/10 flex items-center justify-center text-gold mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <p class="font-display font-semibold text-navy text-sm mb-1">CV</p>
                    <p class="text-xs text-gray-500">Opsional, format PDF</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 text-center">
                    <div class="w-11 h-11 rounded-lg bg-navy/5 flex items-center justify-center text-navy mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="font-display font-semibold text-navy text-sm mb-1">Proposal</p>
                    <p class="text-xs text-gray-500">Opsional, sesuai kampus</p>
                </div>
            </div>
            <p class="text-center text-xs text-gray-400 mt-5">
                * Portofolio tambahan diwajibkan untuk bidang tertentu seperti Desain/Editor, dan hanya muncul di formulir jika relevan.
            </p>
        </section>

        {{-- ALUR PENGAJUAN --}}
        <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-xl bg-health/10 ring-1 ring-health/20 flex items-center justify-center text-health">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <h2 class="font-display font-semibold text-navy text-lg">Alur Pengajuan Magang</h2>
            </div>

            <div class="grid sm:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="w-10 h-10 rounded-full bg-navy text-white font-display font-bold flex items-center justify-center mx-auto mb-3">1</div>
                    <p class="text-sm font-medium text-gray-700">Isi formulir pengajuan online</p>
                </div>
                <div class="text-center">
                    <div class="w-10 h-10 rounded-full bg-health text-white font-display font-bold flex items-center justify-center mx-auto mb-3">2</div>
                    <p class="text-sm font-medium text-gray-700">Unggah dokumen persyaratan</p>
                </div>
                <div class="text-center">
                    <div class="w-10 h-10 rounded-full bg-gold text-navy font-display font-bold flex items-center justify-center mx-auto mb-3">3</div>
                    <p class="text-sm font-medium text-gray-700">Verifikasi oleh admin UPTD</p>
                </div>
                <div class="text-center">
                    <div class="w-10 h-10 rounded-full bg-navy text-white font-display font-bold flex items-center justify-center mx-auto mb-3">4</div>
                    <p class="text-sm font-medium text-gray-700">Cek status kapan saja</p>
                </div>
            </div>
        </section>

        {{-- KETENTUAN MAGANG --}}
        <section class="bg-navy text-white rounded-2xl p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-gold-light">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M4.93 4.93a10 10 0 1114.14 14.14A10 10 0 014.93 4.93z" />
                    </svg>
                </div>
                <h2 class="font-display font-semibold text-lg">Ketentuan Magang</h2>
            </div>

            <ul class="space-y-3 text-sm text-gray-300">
                <li class="flex gap-3">
                    <span class="text-gold-light">•</span>
                    Durasi magang minimal 2 bulan dan maksimal 6 bulan, sesuai kebutuhan program studi.
                </li>
                <li class="flex gap-3">
                    <span class="text-gold-light">•</span>
                    Mahasiswa wajib mengikuti jadwal dan tata tertib yang berlaku di UPTD Pelatihan Kesehatan.
                </li>
                <li class="flex gap-3">
                    <span class="text-gold-light">•</span>
                    Penempatan bidang disesuaikan dengan kebutuhan instansi dan minat yang diajukan.
                </li>
                <li class="flex gap-3">
                    <span class="text-gold-light">•</span>
                    Hasil verifikasi pengajuan akan diinformasikan melalui menu Cek Status.
                </li>
            </ul>
        </section>

        {{-- CTA --}}
        <div class="text-center pt-4">
            <a href="{{ route('pengajuan.create') }}"
               class="inline-block bg-health hover:bg-health-dark transition text-white font-semibold px-8 py-3 rounded-lg shadow-md shadow-health/30">
                Sudah Siap? Ajukan Magang Sekarang
            </a>
        </div>
    </div>

@endsection