<x-admin.layouts.app title="Detail Absensi Mahasiswa">
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-3"><a href="{{ route('admin.attendance.recap') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#0C2340] hover:underline"><i class="ti ti-arrow-left"></i> Kembali ke rekap</a><a href="{{ route('admin.attendance.intern-recap.pdf', $intern) }}" class="inline-flex items-center gap-2 rounded-lg bg-[#0C2340] px-4 py-2.5 text-xs font-semibold text-white hover:bg-[#081A30]"><i class="ti ti-file-download"></i> Download PDF Mahasiswa</a></div>
        <x-admin.card title="{{ $intern->name }}" subtitle="{{ $intern->username }} · {{ $intern->university }}">
            <div class="grid gap-4 text-sm sm:grid-cols-3"><div><p class="text-xs uppercase tracking-wide text-[#8A94A6]">Periode Magang</p><p class="mt-1 font-medium text-[#1E2A24]">{{ $start->format('d M Y') }} s/d {{ $end->format('d M Y') }}</p></div><div><p class="text-xs uppercase tracking-wide text-[#8A94A6]">Total Record</p><p class="mt-1 font-medium text-[#1E2A24]">{{ $records->count() }} hari</p></div><div><p class="text-xs uppercase tracking-wide text-[#8A94A6]">Status</p><p class="mt-1"><x-status-badge :status="$intern->status" /></p></div></div>
        </x-admin.card>

        <x-admin.card title="Riwayat Absensi" subtitle="Hadir, izin, sakit, dan alpa ditampilkan berdasarkan setiap hari kerja.">
            <div id="attendance-history" class="responsive-table"><table class="w-full min-w-[900px] text-left text-sm"><thead><tr class="border-b border-[#E7EAF1] text-xs uppercase tracking-wide text-[#8A94A6]"><th class="py-3 pr-4">Tanggal</th><th class="py-3 pr-4">Datang</th><th class="py-3 pr-4">Jarak Datang</th><th class="py-3 pr-4">Pulang</th><th class="py-3 pr-4">Jarak Pulang</th><th class="py-3 pr-4">Status</th></tr></thead><tbody class="divide-y divide-[#EEF0F5]">
                @forelse ($attendanceHistory as $item)
                    @php $record = $item['record']; @endphp
                    <tr data-page-item><td class="py-3 pr-4 font-medium">{{ $item['date']->format('d M Y') }}</td><td class="py-3 pr-4">{{ $record?->check_in_at?->format('H:i') ?? '-' }}</td><td class="py-3 pr-4">{{ $record?->check_in_distance_meters !== null ? $record->check_in_distance_meters.' m' : '-' }}</td><td class="py-3 pr-4">{{ $record?->check_out_at?->format('H:i') ?? '-' }}</td><td class="py-3 pr-4">{{ $record?->check_out_distance_meters !== null ? $record->check_out_distance_meters.' m' : '-' }}</td><td class="py-3 pr-4"><x-status-badge :status="$item['status']" /></td></tr>
                @empty
                    <tr><td colspan="6" class="py-8 text-center text-sm text-[#8A94A6]">Belum ada hari kerja dalam periode ini.</td></tr>
                @endforelse
            </tbody></table></div>
            <div id="attendance-pagination" class="mt-4 flex items-center justify-between border-t border-[#EEF0F5] pt-4"></div>
        </x-admin.card>

        <x-admin.card title="Pengajuan Izin / Sakit" subtitle="Riwayat pengajuan mahasiswa ini.">
            <div id="leave-history" class="space-y-3">@forelse ($leaveRequests as $leave)<div data-page-item class="rounded-xl border border-[#E7EAF1] p-4"><div class="flex items-center justify-between gap-3"><p class="font-medium text-[#1E2A24]">{{ ucfirst($leave->type) }}</p><x-status-badge :status="$leave->status" /></div><p class="mt-1 text-xs text-[#8A94A6]">{{ $leave->start_date->format('d M Y') }} - {{ $leave->end_date->format('d M Y') }}</p><p class="mt-2 text-sm text-[#687386]">{{ $leave->reason }}</p></div>@empty<p class="text-sm text-[#8A94A6]">Belum ada pengajuan izin/sakit.</p>@endforelse</div>
            <div id="leave-pagination" class="mt-4 flex items-center justify-between border-t border-[#EEF0F5] pt-4"></div>
        </x-admin.card>
    </div>
    @push('scripts')
        <script>
            (() => { const setup = (containerId, paginationId, perPage) => { const container = document.getElementById(containerId), pagination = document.getElementById(paginationId); if (!container || !pagination) return; const items = [...container.querySelectorAll('[data-page-item]')]; if (items.length <= perPage) return; let page = 1; const total = Math.ceil(items.length / perPage); const render = () => { items.forEach((item, index) => item.hidden = index < (page - 1) * perPage || index >= page * perPage); pagination.innerHTML = `<button type="button" data-prev class="rounded-lg border border-[#E3E5DE] px-3 py-2 text-xs font-semibold text-[#687386] disabled:opacity-40">Sebelumnya</button><span class="text-xs text-[#8A94A6]">Halaman ${page} dari ${total}</span><button type="button" data-next class="rounded-lg border border-[#E3E5DE] px-3 py-2 text-xs font-semibold text-[#687386] disabled:opacity-40">Berikutnya</button>`; const prev = pagination.querySelector('[data-prev]'), next = pagination.querySelector('[data-next]'); prev.disabled = page === 1; next.disabled = page === total; prev.onclick = () => { if (page > 1) { page--; render(); } }; next.onclick = () => { if (page < total) { page++; render(); } }; }; render(); }; setup('attendance-history', 'attendance-pagination', 5); setup('leave-history', 'leave-pagination', 2); })();
        </script>
    @endpush
</x-admin.layouts.app>


