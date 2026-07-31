<x-admin.layouts.app title="Dashboard">

    {{-- Stat cards --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-3 xl:grid-cols-6">
        @php
            $cards = [
                ['label' => 'Total Pengajuan', 'value' => $stats['total_applications'], 'icon' => 'ti-file-description', 'accent' => 'text-[#0F6E56] bg-[#E7F2ED]'],
                ['label' => 'Menunggu Verifikasi', 'value' => $stats['pending'], 'icon' => 'ti-clock', 'accent' => 'text-[#5B6660] bg-[#EFF1EC]'],
                ['label' => 'Diproses', 'value' => $stats['processed'], 'icon' => 'ti-loader-2', 'accent' => 'text-[#8A661E] bg-[#FBF3E1]'],
                ['label' => 'Diterima', 'value' => $stats['accepted'], 'icon' => 'ti-circle-check', 'accent' => 'text-[#0B5443] bg-[#E7F2ED]'],
                ['label' => 'Ditolak', 'value' => $stats['rejected'], 'icon' => 'ti-circle-x', 'accent' => 'text-[#9B3A3A] bg-[#FBEAEA]'],
                ['label' => 'Mahasiswa Aktif', 'value' => $stats['active_interns'], 'icon' => 'ti-users', 'accent' => 'text-[#0F6E56] bg-[#E7F2ED]'],
            ];
        @endphp

        @foreach ($cards as $card)
            <x-admin.card class="!p-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl {{ $card['accent'] }}">
                        <i class="ti {{ $card['icon'] }} text-lg"></i>
                    </div>
                    <div>
                        <p class="font-heading text-xl font-semibold text-[#1E2A24]">{{ $card['value'] }}</p>
                        <p class="text-xs text-[#64705F]">{{ $card['label'] }}</p>
                    </div>
                </div>
            </x-admin.card>
        @endforeach
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-5">
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
                    backgroundColor: '#0F6E56',
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
