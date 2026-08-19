<x-admin.layouts.app title="Sesi QR Absensi">
    <div class="space-y-6">
        <div>
            <p class="text-sm font-medium text-[#8A94A6]">Kehadiran mahasiswa</p>
            <h2 class="mt-1 font-heading text-2xl font-bold text-[#0C2340]">Sesi QR Absensi</h2>
        </div>

        @if(false)

        <x-admin.card>
            <form method="GET" class="grid gap-3 lg:grid-cols-[minmax(0,1.5fr)_repeat(2,minmax(0,1fr))_auto] lg:items-end">
                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#8A94A6]">Peserta Magang</label>
                    <select name="intern_id" class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]">
                        <option value="">Semua peserta — tanggal hari ini</option>
                        @foreach ($interns as $intern)
                            <option value="{{ $intern->id }}" @selected($selectedIntern?->id === $intern->id)>{{ $intern->name }} · {{ $intern->username }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#8A94A6]">Mulai</label>
                    <input type="date" name="start_date" value="{{ $periodStart }}" class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]">
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#8A94A6]">Selesai</label>
                    <input type="date" name="end_date" value="{{ $periodEnd }}" class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]">
                </div>
                <div class="flex gap-2">
                    <button class="flex-1 rounded-lg bg-[#0C2340] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#081A30]">Tampilkan</button>
                    <a href="{{ route('admin.attendance.index') }}" class="rounded-lg border border-[#E3E5DE] px-4 py-2.5 text-sm font-semibold text-[#687386] hover:bg-[#F4F6FB]">Reset</a>
                </div>
            </form>
            @if ($selectedIntern)
                <div class="mt-4 flex flex-col gap-1 rounded-lg bg-[#E8EEF5] px-4 py-3 text-sm text-[#0C2340] sm:flex-row sm:items-center sm:justify-between">
                    <span>Menampilkan absensi <strong>{{ $selectedIntern->name }}</strong>.</span>
                    <span class="text-xs font-medium">Periode: {{ $periodStart }} s/d {{ $periodEnd }}</span>
                </div>
            @endif
        </x-admin.card>

        <div class="grid grid-cols-2 gap-4 xl:grid-cols-4">
            @foreach ([['label'=>'Total Scan','value'=>$summary['total'],'accent'=>'border-[#0C2340]'],['label'=>'Hadir Valid','value'=>$summary['hadir'],'accent'=>'border-emerald-500'],['label'=>'Perlu Verifikasi','value'=>$summary['pending'],'accent'=>'border-amber-500'],['label'=>'Izin Menunggu','value'=>$summary['leave_pending'],'accent'=>'border-blue-500']] as $item)
                <div class="rounded-xl border-l-4 {{ $item['accent'] }} bg-white p-4 shadow-sm"><p class="font-heading text-2xl font-bold text-[#0C2340]">{{ $item['value'] }}</p><p class="mt-1 text-xs font-medium text-[#8A94A6]">{{ $item['label'] }}</p></div>
            @endforeach
        </div>

        @endif

        <div class="grid gap-6 xl:grid-cols-[360px_minmax(0,1fr)]">
            <x-admin.card title="Buat Sesi QR" subtitle="QR aktif untuk tanggal hari ini atau jadwal yang dipilih.">
                <form method="POST" action="{{ route('admin.attendance.sessions.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Jenis Absensi</label>
                        <select name="type" required class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]">
                            <option value="datang">Absen Datang (07:45)</option>
                            <option value="pulang">Absen Pulang (16:00)</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Tanggal</label>
                        <input type="date" name="attendance_date" value="{{ $date }}" required class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Latitude</label>
                            <input type="number" step="any" name="latitude" value="{{ old('latitude', config('attendance.latitude')) }}" placeholder="Opsional" class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]">
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Longitude</label>
                            <input type="number" step="any" name="longitude" value="{{ old('longitude', config('attendance.longitude')) }}" placeholder="Opsional" class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]">
                        </div>
                    </div>
                    <button id="use-current-location" type="button" class="flex w-full items-center justify-center gap-2 rounded-lg border border-[#0C2340] px-4 py-2.5 text-sm font-semibold text-[#0C2340] hover:bg-[#E8EEF5]"><i class="ti ti-current-location"></i> Ambil Lokasi Instansi Otomatis</button>
                    <p id="location-status" class="text-xs text-[#8A94A6]">Gunakan tombol ini dari perangkat yang berada di lokasi instansi.</p>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Radius (meter)</label>
                        <input type="number" name="radius_meters" value="{{ old('radius_meters', config('attendance.radius_meters', 150)) }}" min="10" max="5000" required class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]">
                    </div>
                    <button type="submit" class="w-full rounded-lg bg-[#0C2340] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#081A30]">Buat QR Absensi</button>
                </form>
                <p class="mt-4 rounded-lg bg-[#F4F6FB] p-3 text-xs leading-relaxed text-[#687386]">Isi koordinat instansi agar sistem dapat memvalidasi jarak GPS. Jika kosong, lokasi tetap dicatat tetapi tidak dibandingkan.</p>
            </x-admin.card>

            <x-admin.card title="Sesi QR Aktif" subtitle="Tampilkan QR ini agar mahasiswa dapat melakukan scan.">
                <div class="grid gap-4 sm:grid-cols-2">
                    @forelse ($sessions as $session)
                        <div class="rounded-xl border border-[#E7EAF1] p-4 {{ $session->isAvailable() ? 'bg-[#F8FAFD]' : 'bg-[#FAFAFB] opacity-70' }}">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-[#0C2340]">Absen {{ ucfirst($session->type) }}</p>
                                    <p class="mt-1 text-xs text-[#8A94A6]">{{ $session->attendance_date->translatedFormat('d F Y') }} · Radius {{ $session->radius_meters }} m</p>
                                </div>
                                <span class="rounded-full px-2 py-1 text-[10px] font-semibold {{ $session->isAvailable() ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">{{ $session->isAvailable() ? 'Aktif' : 'Ditutup' }}</span>
                            </div>
                            <div class="mt-4 flex flex-col items-center gap-3 sm:flex-row">
                                <button type="button" class="qr-trigger cursor-zoom-in rounded-lg bg-white p-2 transition hover:ring-2 hover:ring-[#0C2340]/30" data-qr-id="qr-{{ $session->id }}" data-qr-url="{{ route('intern.attendance.scan', $session) }}" aria-label="Perbesar QR Absen {{ ucfirst($session->type) }}">
                                    <div id="qr-{{ $session->id }}"></div>
                                </button>
                                <div class="min-w-0 text-xs text-[#687386]">
                                    <p>Scan melalui kamera HP mahasiswa.</p>
                                    @if ($session->isAvailable())
                                        <form method="POST" action="{{ route('admin.attendance.sessions.close', $session) }}" class="mt-3">
                                            @csrf @method('PATCH')
                                            <button class="font-semibold text-[#9B3A3A] hover:underline">Tutup sesi</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            <script>window.attendanceQr = window.attendanceQr || []; window.attendanceQr.push({id: 'qr-{{ $session->id }}', url: @json(route('intern.attendance.scan', $session))});</script>
                        </div>
                    @empty
                        <p class="py-8 text-sm text-[#8A94A6] sm:col-span-2">Belum ada sesi QR untuk tanggal ini.</p>
                    @endforelse
                </div>
            </x-admin.card>
        </div>

        @if(false)
        <x-admin.card title="Rekap Absensi" subtitle="{{ $selectedIntern ? 'Satu peserta dalam periode magang.' : 'Semua peserta untuk tanggal hari ini.' }} Lokasi dicatat saat mahasiswa melakukan scan.">
            <div class="responsive-table">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead><tr class="border-b border-[#E7EAF1] text-xs uppercase tracking-wide text-[#8A94A6]"><th class="py-3 pr-4">Mahasiswa</th><th class="py-3 pr-4">Datang</th><th class="py-3 pr-4">Pulang</th><th class="py-3 pr-4">Lokasi</th><th class="py-3 pr-4">Aksi</th></tr></thead>
                    <tbody class="divide-y divide-[#EEF0F5]">
                    @forelse ($records as $record)
                        <tr>
                            <td class="py-3 pr-4"><p class="font-medium text-[#1E2A24]">{{ $record->intern->name }}</p><p class="text-xs text-[#8A94A6]">{{ $record->intern->username }}</p></td>
                            <td class="py-3 pr-4"><span class="font-medium">{{ $record->check_in_at?->format('H:i') ?? '-' }}</span><span class="ml-2 rounded-full bg-[#E8EEF5] px-2 py-1 text-[10px] text-[#0C2340]">{{ $record->check_in_status ? ucwords(str_replace('_', ' ', $record->check_in_status)) : '-' }}</span></td>
                            <td class="py-3 pr-4">{{ $record->check_out_at?->format('H:i') ?? '-' }}</td>
                            <td class="py-3 pr-4 text-xs"><div class="text-[#687386]">Masuk: {{ $record->check_in_distance_meters !== null ? $record->check_in_distance_meters.' m' : 'GPS belum disetel' }}</div><div class="mt-1 text-[#A0A8B8]">Pulang: {{ $record->check_out_distance_meters !== null ? $record->check_out_distance_meters.' m' : '-' }}</div></td>
                            <td class="py-3 pr-4">
                                @if ($record->check_in_status === 'menunggu_verifikasi')
                                    <div class="flex gap-2">
                                        <form method="POST" action="{{ route('admin.attendance.records.review', $record) }}">@csrf @method('PATCH')<input type="hidden" name="decision" value="approve"><button class="rounded-lg bg-[#0C2340] px-2.5 py-1.5 text-xs font-semibold text-white">Terima</button></form>
                                        <form method="POST" action="{{ route('admin.attendance.records.review', $record) }}">@csrf @method('PATCH')<input type="hidden" name="decision" value="reject"><button class="rounded-lg border border-[#F0C9C9] px-2.5 py-1.5 text-xs font-semibold text-[#9B3A3A]">Tolak</button></form>
                                    </div>
                                @else <span class="text-xs text-[#8A94A6]">Tidak perlu verifikasi</span> @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-8 text-center text-sm text-[#8A94A6]">Belum ada data absensi.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </x-admin.card>

        <x-admin.card title="Pengajuan Izin / Sakit" subtitle="Pengajuan yang masih menunggu keputusan admin.">
            <div class="space-y-3">
                @forelse ($leaveRequests as $leave)
                    <div class="flex flex-col gap-3 rounded-xl border border-[#E7EAF1] p-4 sm:flex-row sm:items-center sm:justify-between">
                        <div><p class="font-medium text-[#1E2A24]">{{ $leave->intern->name }} · {{ ucfirst($leave->type) }}</p><p class="text-xs text-[#8A94A6]">{{ $leave->start_date->format('d M Y') }} - {{ $leave->end_date->format('d M Y') }} · {{ $leave->reason }}</p></div>
                        <div class="flex gap-2"><form method="POST" action="{{ route('admin.attendance.leave.review', $leave) }}">@csrf @method('PATCH')<input type="hidden" name="decision" value="approved"><button class="rounded-lg bg-[#0C2340] px-3 py-2 text-xs font-semibold text-white">Setujui</button></form><form method="POST" action="{{ route('admin.attendance.leave.review', $leave) }}">@csrf @method('PATCH')<input type="hidden" name="decision" value="rejected"><button class="rounded-lg border border-[#F0C9C9] px-3 py-2 text-xs font-semibold text-[#9B3A3A]">Tolak</button></form></div>
                    </div>
                @empty <p class="text-sm text-[#8A94A6]">Tidak ada pengajuan yang menunggu persetujuan.</p> @endforelse
            </div>
        </x-admin.card>
        @endif

        <div id="qr-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-[#081A30]/70 p-4" role="dialog" aria-modal="true" aria-labelledby="qr-modal-title">
            <div class="relative w-full max-w-md rounded-2xl bg-white p-6 text-center shadow-2xl">
                <button id="qr-modal-close" type="button" class="absolute right-4 top-4 flex h-9 w-9 items-center justify-center rounded-full text-[#687386] hover:bg-[#F4F6FB]" aria-label="Tutup QR"><i class="ti ti-x text-lg"></i></button>
                <p id="qr-modal-title" class="pr-8 text-left font-heading text-lg font-bold text-[#0C2340]">QR Absensi</p>
                <p class="mt-1 text-left text-sm text-[#8A94A6]">Arahkan kamera mahasiswa ke QR ini.</p>
                <div id="qr-modal-code" class="mx-auto mt-6 flex min-h-[310px] w-[310px] items-center justify-center rounded-xl bg-white p-2"></div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
        <script>
            const locationButton = document.getElementById('use-current-location');
            const locationStatus = document.getElementById('location-status');
            if (locationButton) locationButton.addEventListener('click', () => {
                if (!navigator.geolocation) { locationStatus.textContent = 'Browser tidak mendukung GPS.'; return; }
                locationButton.disabled = true;
                locationStatus.textContent = 'Mengambil lokasi perangkat...';
                navigator.geolocation.getCurrentPosition(position => {
                    document.querySelector('input[name="latitude"]').value = position.coords.latitude.toFixed(7);
                    document.querySelector('input[name="longitude"]').value = position.coords.longitude.toFixed(7);
                    locationStatus.textContent = `Lokasi siap: ${position.coords.latitude.toFixed(6)}, ${position.coords.longitude.toFixed(6)}`;
                    locationButton.disabled = false;
                }, () => { locationStatus.textContent = 'Lokasi tidak dapat diambil. Izinkan akses GPS lalu coba lagi.'; locationButton.disabled = false; }, {enableHighAccuracy: true, timeout: 10000, maximumAge: 0});
            });

            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.getElementById('qr-modal');
                const modalCode = document.getElementById('qr-modal-code');
                const closeModal = () => { modal.classList.add('hidden'); modal.classList.remove('flex'); modalCode.innerHTML = ''; };

                (window.attendanceQr || []).forEach(item => {
                    new QRCode(document.getElementById(item.id), {text: item.url, width: 112, height: 112, colorDark: '#0C2340', colorLight: '#ffffff'});
                });

                document.querySelectorAll('.qr-trigger').forEach(trigger => trigger.addEventListener('click', () => {
                    modalCode.innerHTML = '';
                    new QRCode(modalCode, {text: trigger.dataset.qrUrl, width: 300, height: 300, colorDark: '#0C2340', colorLight: '#ffffff'});
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }));

                document.getElementById('qr-modal-close').addEventListener('click', closeModal);
                modal.addEventListener('click', event => { if (event.target === modal) closeModal(); });
                document.addEventListener('keydown', event => { if (event.key === 'Escape') closeModal(); });
            });
        </script>
    @endpush
</x-admin.layouts.app>
