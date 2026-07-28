<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin' }} — SIM Magang UPTD Pelatihan Kesehatan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/2.47.0/iconfont/tabler-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-heading { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#F6F7F4] text-[#1E2A24] antialiased" x-data="{ sidebarOpen: false }">

    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside
            class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full border-r border-[#E3E5DE] bg-white transition-transform duration-200 lg:static lg:translate-x-0"
            :class="{ 'translate-x-0': sidebarOpen }"
        >
            <div class="flex h-16 items-center gap-3 border-b border-[#E3E5DE] px-5">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#0F6E56] text-white">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3 5 6v5c0 4.5 2.9 8.4 7 10 4.1-1.6 7-5.5 7-10V6l-7-3Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4" />
                    </svg>
                </div>
                <div class="leading-tight">
                    <p class="font-heading text-sm font-semibold text-[#1E2A24]">SIM Magang</p>
                    <p class="text-[11px] text-[#64705F]">UPTD Pelatihan Kesehatan</p>
                </div>
            </div>

            <nav class="space-y-1 px-3 py-4">
                @php
                    $navItems = [
                        ['route' => 'admin.dashboard', 'icon' => 'ti-layout-dashboard', 'label' => 'Dashboard'],
                        ['route' => 'admin.applications.index', 'icon' => 'ti-file-description', 'label' => 'Kelola Pengajuan'],
                        ['route' => 'admin.interns.index', 'icon' => 'ti-users', 'label' => 'Mahasiswa Magang'],
                        ['route' => 'admin.departments.index', 'icon' => 'ti-building', 'label' => 'Bidang Magang'],
                    ];
                @endphp

                @foreach ($navItems as $item)
                    @php $active = request()->routeIs($item['route'].'*'); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 rounded-lg border-l-[3px] px-3 py-2.5 text-sm font-medium transition-colors
                       {{ $active ? 'border-[#C99A3B] bg-[#F6F7F4] text-[#0F6E56]' : 'border-transparent text-[#4B564B] hover:bg-[#F6F7F4]' }}">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ $active ? 'bg-[#E7F2ED] text-[#0F6E56]' : 'bg-[#F6F7F4] text-[#64705F]' }}">
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
            </nav>
        </aside>

        {{-- Overlay mobile --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-30 bg-black/30 lg:hidden" style="display: none;"></div>

        {{-- Main content --}}
        <div class="flex min-h-screen flex-1 flex-col">
            <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-[#E3E5DE] bg-white/90 px-4 backdrop-blur lg:px-8">
                <button @click="sidebarOpen = true" class="text-[#4B564B] lg:hidden" aria-label="Buka menu">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                <h1 class="font-heading hidden text-lg font-semibold text-[#1E2A24] lg:block">{{ $title ?? 'Dashboard' }}</h1>

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2 rounded-lg px-2 py-1.5 hover:bg-[#F6F7F4]">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-[#E7F2ED] text-sm font-semibold text-[#0B5443]">
                            {{ Str::upper(Str::substr(auth('admin')->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <span class="hidden text-sm font-medium text-[#1E2A24] sm:block">{{ auth('admin')->user()->name ?? 'Admin' }}</span>
                        <i class="ti ti-chevron-down text-sm text-[#64705F]"></i>
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
            </header>

            <main class="flex-1 px-4 py-6 lg:px-8">
                <x-admin.alert />
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
