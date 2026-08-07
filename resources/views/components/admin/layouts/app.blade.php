<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin' }} — SIM Magang UPTD Pelatihan Kesehatan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-heading { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#F4F6FB] text-[#1E2A24] antialiased" x-data="{ sidebarOpen: false }">

    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside
            class="fixed inset-y-0 left-0 z-40 flex w-[250px] shrink-0 -translate-x-full flex-col border-r border-[#E7EAF1] bg-white transition-transform duration-200 lg:static lg:translate-x-0"
            :class="{ 'translate-x-0': sidebarOpen }"
        >
            <div class="flex h-[82px] items-center gap-3 border-b border-[#EEF0F5] px-6">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#0C2340] text-white shadow-sm shadow-[#0C2340]/20">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3 5 6v5c0 4.5 2.9 8.4 7 10 4.1-1.6 7-5.5 7-10V6l-7-3Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4" />
                    </svg>
                </div>
                <div class="leading-tight">
                    <p class="font-heading text-base font-bold text-[#1E2A24]">SIM Magang</p>
                    <p class="text-[10px] text-[#8A94A6]">UPTD Pelatihan Kesehatan</p>
                </div>
            </div>

            <nav class="flex-1 space-y-1 px-4 py-7">
                @php
                    $navItems = [
                        ['route' => 'admin.dashboard', 'icon' => 'ti-layout-dashboard', 'label' => 'Dashboard'],
                        ['route' => 'admin.applications.index', 'icon' => 'ti-file-description', 'label' => 'Kelola Pengajuan'],
                        ['route' => 'admin.interns.index', 'icon' => 'ti-users', 'label' => 'Mahasiswa Magang'],
                        ['route' => 'admin.departments.index', 'icon' => 'ti-building', 'label' => 'Bidang Magang'],
                    ];
                @endphp

                <p class="mb-3 px-3 text-[10px] font-semibold uppercase tracking-[0.18em] text-[#A0A8B8]">Menu Utama</p>

                @foreach ($navItems as $item)
                    @php $active = request()->routeIs($item['route'].'*'); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="group flex items-center gap-3 rounded-xl px-3 py-3 text-[13px] font-medium transition-colors
                       {{ $active ? 'bg-[#E8EEF5] font-semibold text-[#0C2340]' : 'text-[#687386] hover:bg-[#F7F8FB] hover:text-[#0C2340]' }}">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ $active ? 'bg-[#0C2340] text-white' : 'bg-[#F5F6FA] text-[#8A94A6] group-hover:text-[#0C2340]' }}">
                            @switch($item['icon'])
                                @case('ti-layout-dashboard')
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                                    @break
                                @case('ti-file-description')
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9l-6-6Z"/><path stroke-linecap="round" d="M14 3v6h6M8 13h8M8 17h6"/></svg>
                                    @break
                                @case('ti-users')
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2M9.5 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM21 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                    @break
                                @case('ti-building')
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16M2 21h20M8 7h2M8 11h2M8 15h2M12 7h2M12 11h2M19 21v-7a2 2 0 0 0-2-2h-1"/></svg>
                                    @break
                            @endswitch
                        </span>
                        {{ $item['label'] }}
                    </a>
                @endforeach

                <p class="mb-3 mt-8 px-3 text-[10px] font-semibold uppercase tracking-[0.18em] text-[#A0A8B8]">Pengaturan</p>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-[13px] font-medium text-[#687386] transition-colors hover:bg-[#F7F8FB] hover:text-[#0C2340]">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#F5F6FA] text-[#8A94A6]"><i class="ti ti-settings text-base"></i></span>
                    Preferensi Sistem
                </a>
            </nav>

            <div class="border-t border-[#EEF0F5] p-4">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-3 text-[13px] font-medium text-[#9B3A3A] transition hover:bg-[#FBEAEA]">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#FFF3F3]"><i class="ti ti-logout text-base"></i></span>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- Overlay mobile --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-30 bg-black/30 lg:hidden" style="display: none;"></div>

        {{-- Main content --}}
        <div class="flex min-h-screen min-w-0 flex-1 flex-col">
            <header class="sticky top-0 z-20 flex h-[82px] items-center justify-between border-b border-[#E7EAF1] bg-white/90 px-4 backdrop-blur lg:px-9">
                <div class="flex min-w-0 items-center gap-4">
                <button @click="sidebarOpen = true" class="rounded-lg p-2 text-[#4B564B] hover:bg-[#F4F6FB] lg:hidden" aria-label="Buka menu">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                <h1 class="font-heading min-w-0 truncate text-lg font-semibold text-[#1E2A24]">{{ $title ?? 'Dashboard' }}</h1>
                </div>

                <div class="flex items-center gap-3">
                    <button class="hidden h-10 w-10 items-center justify-center rounded-full text-[#687386] hover:bg-[#F4F6FB] sm:inline-flex" aria-label="Notifikasi"><i class="ti ti-bell text-lg"></i></button>
                    <div class="hidden h-7 w-px bg-[#E7EAF1] sm:block"></div>
                    <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2 rounded-xl px-2 py-1.5 hover:bg-[#F4F6FB]">
                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-[#E8EEF5] text-sm font-semibold text-[#0C2340] ring-2 ring-white shadow-sm">
                            {{ Str::upper(Str::substr(auth('admin')->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <span class="hidden text-sm font-semibold text-[#1E2A24] sm:block">{{ auth('admin')->user()->name ?? 'Admin' }}</span>
                        <i class="ti ti-chevron-down text-sm text-[#8A94A6]"></i>
                    </button>

                    <div x-show="open" @click.outside="open = false" x-cloak
                         class="absolute right-0 mt-2 w-48 rounded-xl border border-[#E3E5DE] bg-white p-1.5 shadow-lg">
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-sm text-[#9B3A3A] hover:bg-[#FBEAEA]">
                                <i class="ti ti-logout"></i> Keluar
                            </button>
                        </form>
                    </div>
                    </div>
                </div>
            </header>

            <main class="min-w-0 flex-1 px-4 py-6 sm:px-6 lg:px-9 lg:py-8">
                <x-admin.alert />
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>

