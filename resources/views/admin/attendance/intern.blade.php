<x-admin.layouts.app title="Detail Absensi Peserta">
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-3"><a href="{{ route('admin.attendance.recap') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#0C2340] hover:underline"><i class="ti ti-arrow-left"></i> Kembali ke rekap</a><div class="flex flex-wrap gap-2"><a href="{{ route('admin.attendance.intern-recap.excel', $intern) }}" class="inline-flex items-center gap-2 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-xs font-semibold text-emerald-800 hover:bg-emerald-100"><i class="ti ti-file-spreadsheet"></i> Download Excel</a><a href="{{ route('admin.attendance.intern-recap.pdf', $intern) }}" class="inline-flex items-center gap-2 rounded-lg bg-[#0C2340] px-4 py-2.5 text-xs font-semibold text-white hover:bg-[#081A30]"><i class="ti ti-file-download"></i> Download PDF</a></div></div>
        <x-admin.card title="{{ $intern->name }}" subtitle="{{ $intern->username }} · {{ $intern->university }}">
            <div class="grid gap-x-8 gap-y-5 text-sm sm:grid-cols-2 lg:grid-cols-4"><div><p class="text-xs uppercase tracking-wide text-[#8A94A6]">Periode Magang</p><p class="mt-1 font-medium text-[#1E2A24]">{{ $start->format('d M Y') }} s/d {{ $end->format('d M Y') }}</p></div><div><p class="text-xs uppercase tracking-wide text-[#8A94A6]">Total Record</p><p class="mt-1 font-medium text-[#1E2A24]">{{ $records->count() }} hari</p></div><div><p class="text-xs uppercase tracking-wide text-[#8A94A6]">Jam Kerja Tercatat</p><p class="mt-1 font-semibold text-[#0C2340]">{{ $workingDuration }}</p></div><div><p class="text-xs uppercase tracking-wide text-[#8A94A6]">Status</p><p class="mt-1"><x-status-badge :status="$intern->status" /></p></div></div>
        </x-admin.card>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(320px,0.9fr)] xl:items-start">
            <div class="space-y-6">
            <x-admin.card title="Konfirmasi Kehadiran" subtitle="Catat hanya setelah peserta mengonfirmasi langsung.">
                <form method="POST" action="{{ route('admin.attendance.manual-confirmation', $intern) }}" class="space-y-3">
                    @csrf @method('PATCH')
                    <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#8A94A6]">Tanggal</label><input type="date" name="attendance_date" value="{{ old('attendance_date', now()->toDateString()) }}" min="{{ $start->toDateString() }}" max="{{ min($end->toDateString(), now()->toDateString()) }}" required class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]"></div>
                    <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#8A94A6]">Kasus</label><select name="confirmation_type" required class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]"><option value="missed_check_in" @selected(old('confirmation_type') === 'missed_check_in')>Lupa scan datang — hadir 07.30</option><option value="correct_late_check_in" @selected(old('confirmation_type') === 'correct_late_check_in')>Scan terlambat, tetapi hadir tepat waktu</option><option value="missed_check_out" @selected(old('confirmation_type') === 'missed_check_out')>Lupa scan pulang — pulang 16.00</option><option value="missed_check_in_and_out" @selected(old('confirmation_type') === 'missed_check_in_and_out')>Lupa scan datang dan pulang — 07.30–16.00</option></select></div>
                    <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#8A94A6]">Catatan <span class="normal-case font-normal">(opsional)</span></label><input type="text" name="admin_note" value="{{ old('admin_note') }}" maxlength="1000" placeholder="Keterangan konfirmasi" class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]"></div>
                    <button class="w-full rounded-lg bg-[#0C2340] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#081A30]">Konfirmasi Kehadiran</button>
                </form>
            </x-admin.card>

            <x-admin.card title="Riwayat Absensi" subtitle="Hadir, izin, sakit, dan alpa ditampilkan berdasarkan setiap hari kerja.">
                <div id="attendance-history" class="responsive-table"><table class="w-full min-w-[900px] text-left text-sm"><thead><tr class="border-b border-[#E7EAF1] text-xs uppercase tracking-wide text-[#8A94A6]"><th class="py-3 pr-4">Tanggal</th><th class="py-3 pr-4">Datang</th><th class="py-3 pr-4">Jarak Datang</th><th class="py-3 pr-4">Pulang</th><th class="py-3 pr-4">Jarak Pulang</th><th class="py-3 pr-4">Jam Kerja</th><th class="py-3 pr-4">Status</th><th class="py-3 pr-4">Konfirmasi</th></tr></thead><tbody class="divide-y divide-[#EEF0F5]">
                    @forelse ($attendanceHistory as $item)
                        @php $record = $item['record']; @endphp
                        <tr data-page-item><td class="py-3 pr-4 font-medium">{{ $item['date']->format('d M Y') }}</td><td class="py-3 pr-4">{{ $record?->check_in_at?->format('H:i') ?? '-' }}</td><td class="py-3 pr-4">{{ $record?->check_in_distance_meters !== null ? $record->check_in_distance_meters.' m' : '-' }}</td><td class="py-3 pr-4">{{ $record?->check_out_at?->format('H:i') ?? '-' }}</td><td class="py-3 pr-4">{{ $record?->check_out_distance_meters !== null ? $record->check_out_distance_meters.' m' : '-' }}</td><td class="py-3 pr-4 font-medium text-[#0C2340]">{{ $record?->working_duration ?? '-' }}</td><td class="py-3 pr-4"><x-status-badge :status="$item['status']" /></td><td class="py-3 pr-4 text-xs text-[#687386]">@if($record?->check_in_confirmed_at) Datang · {{ $record->check_in_confirmed_at->format('d M H:i') }}<br>@endif @if($record?->check_out_confirmed_at) Pulang · {{ $record->check_out_confirmed_at->format('d M H:i') }}@endif @if(! $record?->check_in_confirmed_at && ! $record?->check_out_confirmed_at)-@endif</td></tr>
                    @empty
                        <tr><td colspan="8" class="py-8 text-center text-sm text-[#8A94A6]">Belum ada hari kerja dalam periode ini.</td></tr>
                    @endforelse
                </tbody></table></div>
                <div id="attendance-pagination" class="mt-4 flex items-center justify-between border-t border-[#EEF0F5] pt-4"></div>
            </x-admin.card>
            </div>

            <div class="space-y-6">
                <x-admin.card title="Panduan Konfirmasi" subtitle="Pilih kasus sesuai kondisi kehadiran peserta.">
                    <div class="space-y-3 text-sm">
                        <div class="rounded-lg bg-[#F6F8FC] p-3"><p class="font-semibold text-[#1E2A24]">Lupa scan datang</p><p class="mt-1 text-xs leading-relaxed text-[#687386]">Dicatat hadir pukul 07.30 agar tidak dianggap alpa.</p></div>
                        <div class="rounded-lg bg-[#F6F8FC] p-3"><p class="font-semibold text-[#1E2A24]">Scan datang terlambat</p><p class="mt-1 text-xs leading-relaxed text-[#687386]">Gunakan bila peserta sebenarnya sudah hadir sebelum 07.30.</p></div>
                        <div class="rounded-lg bg-[#F6F8FC] p-3"><p class="font-semibold text-[#1E2A24]">Lupa scan pulang</p><p class="mt-1 text-xs leading-relaxed text-[#687386]">Jam pulang akan dicatat pukul 16.00 untuk rekap jam kerja.</p></div>
                    </div>
                </x-admin.card>

                <x-admin.card title="Pengajuan Peserta" subtitle="Izin, sakit, dan izin tetap hadir.">
                    <div id="leave-history" class="space-y-2">@forelse ($leaveRequests as $leave)<div data-page-item class="rounded-lg border border-[#E7EAF1] p-3"><div class="flex items-start justify-between gap-2"><div><p class="text-sm font-semibold text-[#1E2A24]">{{ $leave->attendance_exception ? 'Izin Tetap Hadir' : ucfirst($leave->type) }}</p><p class="mt-0.5 text-xs text-[#8A94A6]">{{ $leave->start_date->format('d M Y') }} - {{ $leave->end_date->format('d M Y') }}@if($leave->attendance_exception && $leave->start_time) · {{ \Carbon\Carbon::parse($leave->start_time)->format('H:i') }}–{{ \Carbon\Carbon::parse($leave->end_time)->format('H:i') }} @endif</p></div><x-status-badge :status="$leave->status" /></div>@if($leave->attendance_exception)<p class="mt-1.5 text-xs text-[#8A94A6]">Batas: {{ $leave->approval_deadline_at?->format('d M Y H:i') }} WIB</p>@endif<p class="mt-1.5 text-xs leading-relaxed text-[#687386]">{{ $leave->reason }}</p></div>@empty<p class="text-sm text-[#8A94A6]">Belum ada pengajuan izin/sakit.</p>@endforelse</div>
                    <div id="leave-pagination" class="mt-3 flex items-center justify-between border-t border-[#EEF0F5] pt-3"></div>
                </x-admin.card>
            </div>
        </div>

    </div>
    @push('scripts')
        <script>
            (() => { const setup = (containerId, paginationId, perPage) => { const container = document.getElementById(containerId), pagination = document.getElementById(paginationId); if (!container || !pagination) return; const items = [...container.querySelectorAll('[data-page-item]')]; if (items.length <= perPage) return; let page = 1; const total = Math.ceil(items.length / perPage); const render = () => { items.forEach((item, index) => item.hidden = index < (page - 1) * perPage || index >= page * perPage); pagination.innerHTML = `<button type="button" data-prev class="rounded-lg border border-[#E3E5DE] px-3 py-2 text-xs font-semibold text-[#687386] disabled:opacity-40">Sebelumnya</button><span class="text-xs text-[#8A94A6]">Halaman ${page} dari ${total}</span><button type="button" data-next class="rounded-lg border border-[#E3E5DE] px-3 py-2 text-xs font-semibold text-[#687386] disabled:opacity-40">Berikutnya</button>`; const prev = pagination.querySelector('[data-prev]'), next = pagination.querySelector('[data-next]'); prev.disabled = page === 1; next.disabled = page === total; prev.onclick = () => { if (page > 1) { page--; render(); } }; next.onclick = () => { if (page < total) { page++; render(); } }; }; render(); }; setup('attendance-history', 'attendance-pagination', 5); setup('leave-history', 'leave-pagination', 2); })();
        </script>
    @endpush
</x-admin.layouts.app>


