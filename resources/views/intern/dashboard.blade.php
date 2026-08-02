<x-intern.layouts.app title="Dashboard">

    {{-- ============ WELCOME BANNER ============ --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#0B1F3D] to-[#1a4480] p-6 text-white shadow-lg sm:p-8">
        {{-- Decorative circles --}}
        <div class="pointer-events-none absolute -right-10 -top-10 h-40 w-40 rounded-full bg-white/5"></div>
        <div class="pointer-events-none absolute -bottom-6 -right-6 h-24 w-24 rounded-full bg-gold/10"></div>

        <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                {{-- Avatar --}}
                <div class="h-16 w-16 shrink-0 overflow-hidden rounded-2xl ring-2 ring-white/20 sm:h-20 sm:w-20">
                    @if(auth('intern')->user()->photo)
                        <img src="{{ Storage::url(auth('intern')->user()->photo) }}" alt="Foto" class="h-full w-full object-cover">
                    @else
                        <div class="flex h-full w-full items-center justify-center bg-white/10 text-2xl font-bold text-white/80 sm:text-3xl">
                            {{ Str::upper(Str::substr(auth('intern')->user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                {{-- Info --}}
                <div>
                    <p class="text-xs font-medium text-blue-200/60">Selamat datang kembali 👋</p>
                    <h2 class="mt-0.5 font-heading text-xl font-bold sm:text-2xl">{{ auth('intern')->user()->name }}</h2>
                    <p class="mt-1 text-sm text-blue-200/80">{{ auth('intern')->user()->username }} &middot; {{ auth('intern')->user()->university }}</p>
                </div>
            </div>
            {{-- Meta --}}
            <div class="flex flex-wrap gap-x-6 gap-y-2 text-sm sm:flex-col sm:items-end sm:gap-y-1 sm:text-right">
                <div>
                    <p class="text-[11px] font-medium uppercase tracking-wider text-blue-200/40">Bidang</p>
                    <p class="font-semibold text-white">{{ auth('intern')->user()->department->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-medium uppercase tracking-wider text-blue-200/40">Periode</p>
                    <p class="font-semibold text-white">{{ auth('intern')->user()->period }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ============ STATISTIK ============ --}}
    <div class="mt-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
        @php
            $stats = [
                ['label' => 'Total Hadir', 'value' => '0', 'icon' => 'ti-circle-check', 'iconBg' => 'bg-emerald-100 text-emerald-600', 'border' => 'border-l-emerald-500'],
                ['label' => 'Total Izin', 'value' => '0', 'icon' => 'ti-mail-forward', 'iconBg' => 'bg-blue-100 text-blue-600', 'border' => 'border-l-blue-500'],
                ['label' => 'Total Sakit', 'value' => '0', 'icon' => 'ti-heart-rate-monitor', 'iconBg' => 'bg-amber-100 text-amber-600', 'border' => 'border-l-amber-500'],
                ['label' => 'Total Terlambat', 'value' => '0', 'icon' => 'ti-alarm', 'iconBg' => 'bg-rose-100 text-rose-600', 'border' => 'border-l-rose-500'],
            ];
        @endphp

        @foreach ($stats as $stat)
            <div class="rounded-xl border-l-4 {{ $stat['border'] }} bg-white p-4 shadow-sm transition hover:shadow-md sm:p-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg {{ $stat['iconBg'] }}">
                        <i class="ti {{ $stat['icon'] }} text-xl"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $stat['value'] }}</p>
                        <p class="text-xs font-medium text-gray-400">{{ $stat['label'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ============ QUICK MENU ============ --}}
    <div class="mt-8">
        <h3 class="mb-4 font-heading text-base font-bold text-gray-900">Menu Cepat</h3>
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
            @php
                $menus = [
                    ['label' => 'Absen Datang', 'desc' => 'Catat kehadiran', 'icon' => 'ti-fingerprint', 'gradient' => 'from-emerald-500 to-teal-600', 'route' => '#'],
                    ['label' => 'Absen Pulang', 'desc' => 'Catat kepulangan', 'icon' => 'ti-logout', 'gradient' => 'from-rose-500 to-pink-600', 'route' => '#'],
                    ['label' => 'Riwayat', 'desc' => 'Lihat rekap absensi', 'icon' => 'ti-history', 'gradient' => 'from-blue-500 to-indigo-600', 'route' => '#'],
                    ['label' => 'Profil', 'desc' => 'Edit data diri', 'icon' => 'ti-user-circle', 'gradient' => 'from-violet-500 to-purple-600', 'route' => route('intern.profile.edit')],
                ];
            @endphp

            @foreach ($menus as $menu)
                <a href="{{ $menu['route'] }}" class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:ring-gray-200">
                    <div class="mb-3 flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br {{ $menu['gradient'] }} shadow-lg shadow-gray-300/30 transition-transform duration-200 group-hover:scale-110">
                        <i class="ti {{ $menu['icon'] }} text-xl text-white"></i>
                    </div>
                    <p class="text-sm font-bold text-gray-900">{{ $menu['label'] }}</p>
                    <p class="mt-0.5 text-xs text-gray-400">{{ $menu['desc'] }}</p>
                </a>
            @endforeach
        </div>
    </div>

</x-intern.layouts.app>
