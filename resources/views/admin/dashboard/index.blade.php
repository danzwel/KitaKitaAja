<x-admin.layouts.app title="Dashboard">

    <div class="mb-7 grid gap-5 xl:grid-cols-[minmax(0,1fr)_280px]">
        <section class="relative overflow-hidden rounded-2xl bg-[#0C2340] px-6 py-7 text-white shadow-lg shadow-[#0C2340]/10 sm:px-8">
            <div class="absolute -right-10 -top-16 h-56 w-56 rounded-full border-[28px] border-white/5"></div>
            <div class="absolute -bottom-20 right-28 h-48 w-48 rounded-full border-[22px] border-[#C99A3B]/10"></div>
            <div class="relative max-w-2xl">
                <p class="mb-3 text-[10px] font-semibold uppercase tracking-[0.22em] text-[#D9B65C]">Panel Administrasi</p>
                <h2 class="font-heading max-w-lg text-2xl font-bold leading-tight sm:text-3xl">Kelola Program Magang dengan Lebih Mudah</h2>
                <p class="mt-3 max-w-xl text-sm leading-relaxed text-blue-100/75">Pantau pengajuan, mahasiswa magang, dan bidang penempatan dari satu tempat.</p>
                <a href="{{ route('admin.applications.index') }}" class="mt-6 inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-[#0C2340] transition hover:bg-[#F4F6FB]">
                    Lihat Pengajuan <i class="ti ti-arrow-up-right"></i>
                </a>
            </div>
        </section>

        <section class="rounded-2xl border border-[#E7EAF1] bg-white p-5 shadow-[0_8px_30px_rgba(28,45,75,0.04)]">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-[#1E2A24]">Ringkasan Hari Ini</p>
                    <p class="mt-1 text-xs text-[#8A94A6]">Aktivitas sistem</p>
                </div>
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#E8EEF5] text-[#0C2340]"><i class="ti ti-chart-dots-3"></i></span>
            </div>
            <p class="mt-6 font-heading text-3xl font-bold text-[#0C2340]">{{ $stats['total_applications'] }}</p>
            <p class="mt-1 text-xs text-[#8A94A6]">Total pengajuan tercatat</p>
            <div class="mt-5 h-2 overflow-hidden rounded-full bg-[#EEF1F7]"><div class="h-full w-3/4 rounded-full bg-[#0C2340]"></div></div>
        </section>
    </div>

    {{-- Stat cards --}}
    <div class="mb-7 grid grid-cols-2 gap-4 lg:grid-cols-3 xl:grid-cols-6">
        @php
            $cards = [
                ['label' => 'Total Pengajuan', 'value' => $stats['total_applications'], 'icon' => 'ti-file-description', 'accent' => 'text-[#0C2340] bg-[#E8EEF5]'],
                ['label' => 'Menunggu Verifikasi', 'value' => $stats['pending'], 'icon' => 'ti-clock', 'accent' => 'text-[#5B6660] bg-[#EFF1EC]'],
                ['label' => 'Diproses', 'value' => $stats['processed'], 'icon' => 'ti-loader-2', 'accent' => 'text-[#8A661E] bg-[#FBF3E1]'],
                ['label' => 'Diterima', 'value' => $stats['accepted'], 'icon' => 'ti-circle-check', 'accent' => 'text-[#081A30] bg-[#E8EEF5]'],
                ['label' => 'Ditolak', 'value' => $stats['rejected'], 'icon' => 'ti-circle-x', 'accent' => 'text-[#9B3A3A] bg-[#FBEAEA]'],
                ['label' => 'Mahasiswa Aktif', 'value' => $stats['active_interns'], 'icon' => 'ti-users', 'accent' => 'text-[#0C2340] bg-[#E8EEF5]'],
            ];
        @endphp

        @foreach ($cards as $card)
            <x-admin.card class="!rounded-xl !p-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $card['accent'] }}">
                        <i class="ti {{ $card['icon'] }} text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="font-heading text-xl font-bold text-[#1E2A24]">{{ $card['value'] }}</p>
                        <p class="truncate text-xs text-[#64705F]">{{ $card['label'] }}</p>
                    </div>
                </div>
            </x-admin.card>
        @endforeach
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-5">
        {{-- Chart --}}
        <x-admin.card title="Grafik Pengajuan" subtitle="Jumlah pengajuan per bulan dalam 12 bulan terakhir" class="xl:col-span-3">
            <canvas id="applicationsChart" height="110"></canvas>
        </x-admin.card>

        {{-- Latest applications --}}
        <x-admin.card title="Pengajuan Terbaru" subtitle="5 pengajuan terakhir masuk" class="xl:col-span-2">
            <div class="divide-y divide-[#E3E5DE]">
                @forelse ($latestApplications as $application)
                    <a href="{{ route('admin.applications.show', $application) }}" class="flex items-center justify-between gap-3 py-3 first:pt-0 last:pb-0 hover:opacity-80">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-[#1E2A24]">{{ $application->nama }}</p>
                            <p class="truncate text-xs text-[#64705F]">{{ $application->bidang->nama_bidang ?? '-' }} · {{ $application->created_at?->translatedFormat('d M Y') }}</p>
                        </div>
                        <x-admin.badge :status="$application->status === 'menunggu_verifikasi' ? 'menunggu' : $application->status" />
                    </a>
                @empty
                    <p class="py-6 text-center text-sm text-[#8B958A]">Belum ada pengajuan masuk.</p>
                @endforelse
            </div>
        </x-admin.card>
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.4/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('applicationsChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                    labels: @json($chartLabels),
                datasets: [{
                    label: 'Jumlah Pengajuan',
                    data: @json($chartData),
                    backgroundColor: '#0C2340',
                    borderRadius: 6,
                    maxBarThickness: 28,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#EFF1EC' } },
                    x: { grid: { display: false } },
                }
            }
        });
    </script>
    @endpush
</x-admin.layouts.app>

