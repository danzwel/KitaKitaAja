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
<body class="bg-[#EEF1F5] text-gray-800 font-sans antialiased" x-data="{ mobileMenuOpen: false }">

    @php
        $publicNavItems = [
            ['route' => 'home', 'label' => 'Beranda'],
            ['route' => 'persyaratan', 'label' => 'Persyaratan'],
            ['route' => 'pengajuan.create', 'active' => 'pengajuan.*', 'label' => 'Ajukan Magang'],
            ['route' => 'cek-status', 'active' => 'cek-status*', 'label' => 'Cek Status'],
            ['route' => 'faq', 'label' => 'FAQ'],
            ['route' => 'kontak', 'label' => 'Kontak'],
        ];
    @endphp

    <header class="sticky top-0 z-50 isolate bg-navy text-white shadow-md">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3 sm:py-4">
          <div class="flex justify-between items-center gap-3">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
    <img src="{{ asset('images/logo.jpg') }}" alt="Logo UPTD Pelatihan Kesehatan" class="w-9 h-9 rounded-full object-cover ring-2 ring-gold/50">
    <div class="leading-tight">
                    <p class="font-display font-semibold text-sm">UPTD Pelatihan Kesehatan</p>
                    <p class="text-[11px] text-gray-300">Dinas Kesehatan Provinsi Jawa Barat</p>
                </div>
            </a>
           <nav class="hidden items-center gap-4 text-sm font-medium md:flex lg:gap-6" aria-label="Navigasi utama">
                @foreach ($publicNavItems as $item)
                    @php $active = request()->routeIs($item['active'] ?? $item['route']); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="border-b-2 py-2 transition {{ $active ? 'border-gold text-gold' : 'border-transparent text-white hover:border-gold-light hover:text-gold-light' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
           </nav>
            <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden inline-flex h-10 w-10 items-center justify-center rounded-lg text-white hover:bg-white/10" :aria-expanded="mobileMenuOpen" aria-label="Buka menu navigasi">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
          </div>
          <nav x-show="mobileMenuOpen" x-cloak x-transition class="md:hidden mt-3 border-t border-white/10 pt-3 pb-1">
            <div class="grid gap-1 text-sm font-medium">
                @foreach ($publicNavItems as $item)
                    @php $active = request()->routeIs($item['active'] ?? $item['route']); @endphp
                    <a @click="mobileMenuOpen = false" href="{{ route($item['route']) }}"
                       class="rounded-lg px-3 py-2 transition {{ $active ? 'bg-white/10 font-semibold text-gold' : 'text-white hover:bg-white/10 hover:text-gold-light' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>
          </nav>
        </div>
    </header>

    <main>
        @yield('content')
        @stack('scripts')
    </main>

    <footer class="bg-navy text-gray-300 mt-24">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-10 sm:py-12 grid md:grid-cols-3 gap-8 lg:gap-10 text-sm">
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
