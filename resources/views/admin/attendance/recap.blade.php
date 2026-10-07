<x-admin.layouts.app title="Rekap Absensi">
    <div class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between"><div><p class="text-sm font-medium text-[#8A94A6]">Ringkasan per peserta</p><h2 class="mt-1 font-heading text-2xl font-bold text-[#0C2340]">Rekap Absensi</h2></div><p class="text-xs text-[#8A94A6]">Senin–Jumat dihitung sebagai hari kerja.</p></div>

        <x-admin.card id="pengajuan-izin-sakit" title="Pengajuan Izin / Sakit" subtitle="Pengajuan yang masih menunggu keputusan admin.">
            <div class="space-y-3">
                @forelse ($leaveRequests as $leave)
                    <div class="flex flex-col gap-3 rounded-xl border border-[#E7EAF1] p-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2"><p class="font-medium text-[#1E2A24]">{{ $leave->intern->name }} · {{ ucfirst($leave->type) }}</p><x-status-badge :status="$leave->status" /></div>
                            <p class="text-xs text-[#8A94A6]">{{ $leave->intern->username }} · {{ $leave->start_date->format('d M Y') }} - {{ $leave->end_date->format('d M Y') }}</p>
                            <details class="mt-2 text-xs">
                                <summary class="cursor-pointer font-semibold text-[#0C2340]">Lihat detail pengajuan</summary>
                                <div class="mt-2 space-y-1 rounded-lg bg-[#F8FAFD] p-3 text-[#687386]">
                                    <p><span class="font-semibold text-[#1E2A24]">Diajukan:</span> {{ $leave->created_at?->format('d M Y H:i') }}</p>
                                    <p><span class="font-semibold text-[#1E2A24]">Alasan:</span> {{ $leave->reason }}</p>
                                    @if ($leave->attachment)
                                        <p><span class="font-semibold text-[#1E2A24]">Bukti:</span> <a href="{{ asset('storage/'.$leave->attachment) }}" target="_blank" rel="noopener" class="font-semibold text-[#0C2340] underline">Lihat lampiran</a></p>
                                    @else
                                        <p><span class="font-semibold text-[#1E2A24]">Bukti:</span> Tidak ada lampiran</p>
                                    @endif
                                </div>
                            </details>
                        </div>
                        <div class="flex shrink-0 gap-2"><form method="POST" action="{{ route('admin.attendance.leave.review', $leave) }}">@csrf @method('PATCH')<input type="hidden" name="decision" value="approved"><button class="rounded-lg bg-[#0C2340] px-3 py-2 text-xs font-semibold text-white">Setujui</button></form><form method="POST" action="{{ route('admin.attendance.leave.review', $leave) }}">@csrf @method('PATCH')<input type="hidden" name="decision" value="rejected"><button class="rounded-lg border border-[#F0C9C9] px-3 py-2 text-xs font-semibold text-[#9B3A3A]">Tolak</button></form></div>
                    </div>
                @empty
                    <p class="text-sm text-[#8A94A6]">Tidak ada pengajuan yang menunggu persetujuan.</p>
                @endforelse
            </div>
        </x-admin.card>

        <x-admin.card title="Absensi Hari Ini" subtitle="Data akan diperbarui otomatis setiap 60 detik.">
            <div class="mb-4 flex items-center justify-between rounded-lg bg-[#E8EEF5] px-4 py-3 text-sm text-[#0C2340]"><span>{{ now()->translatedFormat('l, d F Y') }}</span><span class="text-xs font-semibold">{{ $todayRecords->count() }} peserta sudah scan</span></div>
            <div class="responsive-table"><table class="w-full min-w-[700px] text-left text-sm"><thead><tr class="border-b border-[#E7EAF1] text-xs uppercase tracking-wide text-[#8A94A6]"><th class="py-3 pr-4">Mahasiswa</th><th class="py-3 pr-4">Datang</th><th class="py-3 pr-4">Pulang</th><th class="py-3 pr-4">Status</th><th class="py-3 pr-4">Lokasi</th></tr></thead><tbody class="divide-y divide-[#EEF0F5]">@forelse ($todayRecords as $record)<tr><td class="py-3 pr-4"><p class="font-medium text-[#1E2A24]">{{ $record->intern->name }}</p><p class="text-xs text-[#8A94A6]">{{ $record->intern->username }}</p></td><td class="py-3 pr-4 font-medium">{{ $record->check_in_at?->format('H:i') ?? '-' }}</td><td class="py-3 pr-4">{{ $record->check_out_at?->format('H:i') ?? '-' }}</td><td class="py-3 pr-4"><x-status-badge :status="$record->check_in_status ?? '-'" /></td><td class="py-3 pr-4 text-xs text-[#687386]">{{ $record->check_in_distance_meters !== null ? $record->check_in_distance_meters.' m' : 'Belum ada' }}</td></tr>@empty<tr><td colspan="5" class="py-8 text-center text-sm text-[#8A94A6]">Belum ada mahasiswa yang scan hari ini.</td></tr>@endforelse</tbody></table></div>
        </x-admin.card>

        <x-admin.card title="Ringkasan Kehadiran Per Orang" subtitle="Data mengikuti periode magang. Alpa dihitung mulai hari setelah sesi absen terlewat; hari berjalan belum dianggap alpa.">
            <x-slot:action>
                <a href="{{ route('admin.attendance.recap.pdf') }}" class="inline-flex items-center gap-2 rounded-lg bg-[#0C2340] px-4 py-2.5 text-xs font-semibold text-white hover:bg-[#081A30]"><i class="ti ti-file-download"></i> Download PDF</a>
            </x-slot:action>
            <div class="responsive-table"><table class="w-full min-w-[980px] text-left text-sm"><thead><tr class="border-b border-[#E7EAF1] text-xs uppercase tracking-wide text-[#8A94A6]"><th class="py-3 pr-4">Peserta</th><th class="py-3 pr-4">Periode Magang</th><th class="py-3 pr-4 text-center">Hari Kerja</th><th class="py-3 pr-4 text-center">Hadir</th><th class="py-3 pr-4 text-center">Terlambat</th><th class="py-3 pr-4 text-center">Izin</th><th class="py-3 pr-4 text-center">Sakit</th><th class="py-3 pr-4 text-center">Alpa</th><th class="py-3 pr-4 text-center">Verifikasi</th></tr></thead><tbody class="divide-y divide-[#EEF0F5]">@forelse ($summaryRows as $row)<tr class="hover:bg-[#F8FAFD]"><td class="py-4 pr-4"><a href="{{ route('admin.attendance.intern-recap', $row['intern']) }}" class="font-semibold text-[#0C2340] hover:underline">{{ $row['intern']->name }}</a><p class="text-xs text-[#8A94A6]">{{ $row['intern']->username }} · {{ $row['intern']->university }}</p></td><td class="py-4 pr-4 text-xs text-[#687386]">{{ $row['period_start'] }}<br>s/d {{ $row['period_end'] }}</td><td class="py-4 pr-4 text-center font-medium text-[#1E2A24]">{{ $row['working_days'] }}</td><td class="py-4 pr-4 text-center"><span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">{{ $row['present'] }}</span></td><td class="py-4 pr-4 text-center text-[#8A94A6]">{{ $row['late'] }}</td><td class="py-4 pr-4 text-center"><span class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700">{{ $row['izin'] }}</span></td><td class="py-4 pr-4 text-center"><span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">{{ $row['sakit'] }}</span></td><td class="py-4 pr-4 text-center"><span class="rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-700">{{ $row['alpha'] }}</span></td><td class="py-4 pr-4 text-center text-xs text-[#8A94A6]">{{ $row['pending'] }}</td></tr>@empty<tr><td colspan="9" class="py-10 text-center text-sm text-[#8A94A6]">Belum ada peserta aktif.</td></tr>@endforelse</tbody></table></div>
        </x-admin.card>

    </div>
    @push('scripts')
        <script>setTimeout(() => window.location.reload(), 60000);</script>
    @endpush
</x-admin.layouts.app>


