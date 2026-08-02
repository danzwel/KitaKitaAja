<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard' }} — Portal Mahasiswa</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-heading { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#f0f2f5] text-gray-800 antialiased" x-data="{ sidebarOpen: false }">

    <div class="flex min-h-screen">
        {{-- ===================== SIDEBAR ===================== --}}
        <aside
            class="fixed inset-y-0 left-0 z-40 flex w-[260px] -translate-x-full flex-col bg-[#0B1F3D] transition-transform duration-300 lg:static lg:translate-x-0"
            :class="{ 'translate-x-0': sidebarOpen }"
        >
            {{-- Brand --}}
            <div class="flex items-center gap-3 px-5 py-5">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gold/20">
                    <svg class="h-5 w-5 text-gold" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3 5 6v5c0 4.5 2.9 8.4 7 10 4.1-1.6 7-5.5 7-10V6l-7-3Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4" />
                    </svg>
                </div>
                <div class="leading-tight">
                    <p class="font-heading text-sm font-bold text-white">Portal Mahasiswa</p>
                    <p class="text-[11px] text-blue-300/60">SIM Magang</p>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="mt-2 flex-1 space-y-1 px-3">
                @php
                    $navItems = [
                        ['route' => 'intern.dashboard', 'icon' => 'ti-layout-dashboard', 'label' => 'Dashboard'],
                        ['route' => 'intern.profile.edit', 'icon' => 'ti-user-circle', 'label' => 'Profil Saya'],
                    ];
                    $comingSoon = [
                        ['icon' => 'ti-fingerprint', 'label' => 'Absen Datang'],
                        ['icon' => 'ti-logout', 'label' => 'Absen Pulang'],
                        ['icon' => 'ti-history', 'label' => 'Riwayat Absensi'],
                    ];
                @endphp

                <p class="mb-2 px-3 text-[10px] font-semibold uppercase tracking-widest text-blue-300/40">Menu Utama</p>

                @foreach ($navItems as $item)
                    @php $active = request()->routeIs($item['route'].'*'); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-[13px] font-medium transition-all duration-150
                       {{ $active
                           ? 'bg-white/10 text-white shadow-sm shadow-black/10'
                           : 'text-blue-200/60 hover:bg-white/5 hover:text-white' }}">
                        <i class="ti {{ $item['icon'] }} text-lg {{ $active ? 'text-gold' : '' }}"></i>
                        {{ $item['label'] }}
                    </a>
                @endforeach

                <p class="mt-6 mb-2 px-3 text-[10px] font-semibold uppercase tracking-widest text-blue-300/40">Absensi</p>

                @foreach ($comingSoon as $item)
                    <span class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-[13px] font-medium text-blue-200/30 cursor-not-allowed">
                        <i class="ti {{ $item['icon'] }} text-lg"></i>
                        {{ $item['label'] }}
                        <span class="ml-auto rounded-full bg-white/5 px-2 py-0.5 text-[9px] font-semibold text-blue-300/40">Soon</span>
                    </span>
                @endforeach
            </nav>

            {{-- Sidebar Footer --}}
            <div class="border-t border-white/10 p-4">
                <form method="POST" action="{{ route('intern.logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-[13px] font-medium text-red-300/70 transition hover:bg-red-500/10 hover:text-red-300">
                        <i class="ti ti-power text-lg"></i>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- Mobile Overlay --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak class="fixed inset-0 z-30 bg-black/50 backdrop-blur-sm lg:hidden"></div>

        {{-- ===================== MAIN ===================== --}}
        <div class="flex min-h-screen flex-1 flex-col">
            {{-- Top Bar --}}
            <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-gray-200/80 bg-white/80 px-4 backdrop-blur-md lg:px-8">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="rounded-lg p-1.5 text-gray-500 transition hover:bg-gray-100 lg:hidden" aria-label="Buka menu">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <h1 class="font-heading text-base font-bold text-gray-900 lg:text-lg">{{ $title ?? 'Dashboard' }}</h1>
                </div>

                {{-- User Dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2.5 rounded-xl px-2 py-1.5 transition hover:bg-gray-100">
                        @if(auth('intern')->user()->photo)
                            <img src="{{ Storage::url(auth('intern')->user()->photo) }}" alt="Foto" class="h-8 w-8 rounded-full object-cover ring-2 ring-gray-100">
                        @else
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-navy text-xs font-bold text-white">
                                {{ Str::upper(Str::substr(auth('intern')->user()->name ?? 'M', 0, 1)) }}
                            </div>
                        @endif
                        <div class="hidden text-left sm:block">
                            <p class="text-sm font-semibold text-gray-900 leading-tight">{{ auth('intern')->user()->name ?? 'Mahasiswa' }}</p>
                            <p class="text-[11px] text-gray-400">{{ auth('intern')->user()->username }}</p>
                        </div>
                        <svg class="h-4 w-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
                    </button>

                    <div x-show="open" @click.outside="open = false" x-cloak x-transition
                         class="absolute right-0 mt-2 w-52 rounded-xl border border-gray-100 bg-white p-1.5 shadow-xl shadow-gray-200/50">
                        <a href="{{ route('intern.profile.edit') }}" class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-700 transition hover:bg-gray-50">
                            <i class="ti ti-user-circle text-base text-gray-400"></i> Profil Saya
                        </a>
                        <hr class="my-1 border-gray-100">
                        <form method="POST" action="{{ route('intern.logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-red-600 transition hover:bg-red-50">
                                <i class="ti ti-power text-base"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 px-4 py-6 lg:px-8">
                {{-- Flash Messages --}}
                @if (session('status'))
                    <div class="mb-5 flex items-center gap-3 rounded-xl bg-emerald-50 p-4 ring-1 ring-emerald-200/60" x-data="{ show: true }" x-show="show" x-transition>
                        <svg class="h-5 w-5 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
                        <p class="flex-1 text-sm font-medium text-emerald-800">{{ session('status') }}</p>
                        <button @click="show = false" class="text-emerald-400 hover:text-emerald-600"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
                    </div>
                @endif
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
