<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sistem Magang Upelkes Jabar')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#EEF1F5] text-gray-800 font-sans antialiased">

    <header class="bg-navy text-white sticky top-0 z-50 shadow-md">
        <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
    <img src="{{ asset('images/logo.jpg') }}" alt="Logo UPTD Pelatihan Kesehatan" class="w-9 h-9 rounded-full object-cover ring-2 ring-gold/50">
    <div class="leading-tight">
                    <p class="font-display font-semibold text-sm">UPTD Pelatihan Kesehatan</p>
                    <p class="text-[11px] text-gray-300">Dinas Kesehatan Provinsi Jawa Barat</p>
                </div>
            </a>
           <nav class="hidden md:flex items-center gap-6 text-sm font-medium">
    <a href="{{ route('home') }}" class="hover:text-gold-light transition">Beranda</a>
    <a href="{{ route('persyaratan') }}" class="hover:text-gold-light transition">Persyaratan</a>
    <a href="{{ route('pengajuan.create') }}" class="hover:text-gold-light transition">Ajukan Magang</a>
    <a href="{{ route('cek-status') }}" class="hover:text-gold-light transition">Cek Status</a>
    <a href="{{ route('faq') }}" class="hover:text-gold-light transition">FAQ</a>
    <a href="{{ route('kontak') }}" class="hover:text-gold-light transition">Kontak</a>
</nav>
            @unless(request()->routeIs('pengajuan.create'))
    <a href="{{ route('pengajuan.create') }}"
       class="hidden md:inline-block bg-gold text-navy font-semibold text-sm px-4 py-2 rounded-md hover:bg-gold-light transition">
        Ajukan Sekarang
    </a>
@endunless
        </div>
    </header>

    <main>
        @yield('content')
        @stack('scripts')
    </main>

    <footer class="bg-navy text-gray-300 mt-24">
        <div class="max-w-6xl mx-auto px-6 py-12 grid md:grid-cols-3 gap-10 text-sm">
            <div>
                <p class="font-display font-semibold text-white mb-2">UPTD Pelatihan Kesehatan</p>
                <p>Dinas Kesehatan Provinsi Jawa Barat</p>
                <p class="mt-3">Jl. Pasteur No.31, Pasir Kaliki, Kec. Cicendo, Kota Bandung, Jawa Barat 40171</p>
            </div>
            <div>
                <p class="font-display font-semibold text-white mb-2">Kontak</p>
                <p>upelkes@jabarprov.go.id</p>
                <p>0224238422</p>
            </div>
            <div>
                <p class="font-display font-semibold text-white mb-2">Tautan</p>
               <ul class="space-y-1">
    <li><a href="{{ route('persyaratan') }}" class="hover:text-gold-light">Persyaratan Magang</a></li>
    <li><a href="{{ route('pengajuan.create') }}" class="hover:text-gold-light">Ajukan Magang</a></li>
    <li><a href="{{ route('cek-status') }}" class="hover:text-gold-light">Cek Status Pengajuan</a></li>
    <li><a href="{{ route('faq') }}" class="hover:text-gold-light">FAQ</a></li>
    <li><a href="{{ route('kontak') }}" class="hover:text-gold-light">Kontak</a></li>
</ul>
            </div>
        </div>
        <div class="border-t border-white/10 text-center text-xs py-4 text-gray-400">
            © {{ date('Y') }} UPTD Pelatihan Kesehatan Dinas Kesehatan Provinsi Jawa Barat
        </div>
    </footer>
</body>
</html>