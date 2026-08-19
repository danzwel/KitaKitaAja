<x-intern.layouts.app title="Absensi">
    <div class="space-y-6">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#0B1F3D] to-[#1a4480] p-6 text-white sm:p-8">
            <div class="pointer-events-none absolute -right-10 -top-10 h-40 w-40 rounded-full bg-white/5"></div>
            <div class="relative"><p class="text-xs uppercase tracking-[0.2em] text-blue-200/60">Kehadiran Magang</p><h2 class="mt-2 font-heading text-2xl font-bold">Catat kehadiranmu dengan QR Code</h2><p class="mt-2 max-w-xl text-sm text-blue-100/75">Lokasi GPS akan dicatat hanya saat kamu melakukan scan.</p></div>
        </div>

        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            @foreach ([['label'=>'Total Hadir','value'=>$stats['hadir'],'border'=>'border-emerald-500'],['label'=>'Terlambat','value'=>$stats['terlambat'],'border'=>'border-rose-500'],['label'=>'Izin','value'=>$stats['izin'],'border'=>'border-blue-500'],['label'=>'Sakit','value'=>$stats['sakit'],'border'=>'border-amber-500']] as $stat)
                <div class="rounded-xl border-l-4 {{ $stat['border'] }} bg-white p-4 shadow-sm"><p class="text-2xl font-bold text-gray-900">{{ $stat['value'] }}</p><p class="mt-1 text-xs font-medium text-gray-400">{{ $stat['label'] }}</p></div>
            @endforeach
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
                <h3 class="font-heading text-lg font-bold text-gray-900">Status Hari Ini</h3>
                @if ($todayRecord)
                    <div class="mt-5 grid grid-cols-2 gap-3 text-sm"><div class="rounded-xl bg-emerald-50 p-4"><p class="text-xs text-emerald-600">Datang</p><p class="mt-1 font-bold text-emerald-900">{{ $todayRecord->check_in_at?->format('H:i') ?? '-' }}</p><p class="text-xs text-emerald-700">{{ $todayRecord->check_in_status ? ucwords(str_replace('_', ' ', $todayRecord->check_in_status)) : '-' }}</p></div><div class="rounded-xl bg-blue-50 p-4"><p class="text-xs text-blue-600">Pulang</p><p class="mt-1 font-bold text-blue-900">{{ $todayRecord->check_out_at?->format('H:i') ?? '-' }}</p><p class="text-xs text-blue-700">{{ $todayRecord->check_out_status ? ucwords(str_replace('_', ' ', $todayRecord->check_out_status)) : 'Belum absen' }}</p></div></div>
                @else <p class="mt-5 rounded-xl bg-gray-50 p-4 text-sm text-gray-500">Belum ada absensi hari ini. Scan QR yang ditampilkan admin.</p> @endif
                <div class="mt-5 flex flex-wrap gap-3"><a href="{{ route('intern.attendance.leave') }}" class="rounded-lg border border-[#0C2340] px-4 py-2.5 text-sm font-semibold text-[#0C2340]">Ajukan Izin / Sakit</a><a href="{{ route('intern.attendance.history') }}" class="rounded-lg bg-[#0C2340] px-4 py-2.5 text-sm font-semibold text-white">Lihat Riwayat</a></div>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100"><h3 class="font-heading text-lg font-bold text-gray-900">Riwayat Terbaru</h3><div class="mt-4 divide-y divide-gray-100">@forelse ($recentRecords->take(5) as $record)<div class="flex items-center justify-between gap-3 py-3"><div><p class="text-sm font-medium text-gray-800">{{ $record->attendance_date->format('d M Y') }}</p><p class="text-xs text-gray-400">Datang {{ $record->check_in_at?->format('H:i') ?? '-' }} · Pulang {{ $record->check_out_at?->format('H:i') ?? '-' }}</p></div><span class="rounded-full bg-gray-100 px-2 py-1 text-[10px] font-semibold text-gray-600">{{ $record->check_in_status ? ucwords(str_replace('_', ' ', $record->check_in_status)) : '-' }}</span></div>@empty<p class="py-5 text-sm text-gray-400">Belum ada riwayat.</p>@endforelse</div></div>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 sm:p-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="font-heading text-lg font-bold text-gray-900">Scan QR Absensi</h3>
                    <p class="mt-1 max-w-xl text-sm text-gray-500">Arahkan kamera ke QR Code yang ditampilkan admin. Setelah QR terbaca, sistem akan meminta izin GPS dan mencatat absensi menggunakan akun Anda.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button id="start-qr-scanner" type="button" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-[#0C2340] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#081A30]"><i class="ti ti-camera"></i> Buka Kamera</button>
                    <label for="qr-image-input" class="inline-flex shrink-0 cursor-pointer items-center justify-center gap-2 rounded-lg border border-[#0C2340] px-4 py-2.5 text-sm font-semibold text-[#0C2340] hover:bg-gray-50"><i class="ti ti-photo"></i> Foto QR</label>
                    <input id="qr-image-input" type="file" accept="image/*" capture="environment" class="hidden">
                </div>
            </div>
            <div id="qr-scanner-panel" class="mt-5 hidden max-w-md">
                <div id="qr-reader" class="overflow-hidden rounded-xl border border-gray-200"></div>
                <p id="qr-reader-status" class="mt-3 text-xs text-gray-500">Meminta izin kamera...</p>
                <button id="stop-qr-scanner" type="button" class="mt-3 rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-50">Tutup Kamera</button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
        <script>
            (() => {
                const startButton = document.getElementById('start-qr-scanner');
                const stopButton = document.getElementById('stop-qr-scanner');
                const imageInput = document.getElementById('qr-image-input');
                const panel = document.getElementById('qr-scanner-panel');
                const status = document.getElementById('qr-reader-status');
                let scanner = null;

                const openScannedUrl = async (decodedText) => {
                    try {
                        const scannedUrl = new URL(decodedText, window.location.origin);
                        if (!scannedUrl.pathname.includes('/mahasiswa/absensi/scan/')) {
                            status.textContent = 'QR tidak dikenali sebagai QR absensi aplikasi ini.';
                            return;
                        }
                        status.textContent = 'QR berhasil dibaca. Membuka absensi...';
                        await stopScanner();
                        // QR boleh dibuat dari localhost di laptop atau dari tunnel.
                        // Gunakan host yang sedang dibuka mahasiswa di HP.
                        const targetUrl = new URL(
                            scannedUrl.pathname + scannedUrl.search + scannedUrl.hash,
                            window.location.origin
                        );
                        window.location.href = targetUrl.href;
                    } catch (error) {
                        status.textContent = 'QR tidak valid. Gunakan QR yang dibuat dari menu admin.';
                    }
                };

                const stopScanner = async () => {
                    if (!scanner) return;
                    try { await scanner.stop(); } catch (error) { /* Kamera mungkin sudah berhenti */ }
                    scanner.clear();
                    scanner = null;
                    panel.classList.add('hidden');
                    startButton.classList.remove('hidden');
                };

                startButton.addEventListener('click', async () => {
                    if (typeof Html5Qrcode === 'undefined') {
                        status.textContent = 'Scanner kamera belum tersedia. Pastikan perangkat terhubung ke internet.';
                        panel.classList.remove('hidden');
                        return;
                    }

                    panel.classList.remove('hidden');
                    startButton.classList.add('hidden');
                    status.textContent = 'Arahkan kamera ke QR Code admin...';
                    scanner = new Html5Qrcode('qr-reader');

                    try {
                        await scanner.start(
                            { facingMode: 'environment' },
                            { fps: 10, qrbox: { width: 240, height: 240 } },
                            async (decodedText) => {
                                try {
                                    await openScannedUrl(decodedText);
                                } catch (error) {
                                    status.textContent = 'QR tidak valid. Gunakan QR yang dibuat dari menu admin.';
                                }
                            },
                            () => {}
                        );
                    } catch (error) {
                        status.textContent = 'Kamera tidak dapat dibuka. Pastikan izin kamera sudah diberikan.';
                        startButton.classList.remove('hidden');
                    }
                });

                imageInput.addEventListener('change', async (event) => {
                    const file = event.target.files?.[0];
                    event.target.value = '';
                    if (!file) return;
                    if (typeof Html5Qrcode === 'undefined') {
                        alert('Pembaca QR belum tersedia. Pastikan HP terhubung ke internet.');
                        return;
                    }

                    panel.classList.remove('hidden');
                    startButton.classList.add('hidden');
                    status.textContent = 'Membaca foto QR...';
                    scanner = new Html5Qrcode('qr-reader');
                    try {
                        const decodedText = await scanner.scanFile(file, true);
                        await openScannedUrl(decodedText);
                    } catch (error) {
                        status.textContent = 'QR tidak terbaca. Foto QR lebih dekat dan pastikan tidak buram.';
                        startButton.classList.remove('hidden');
                        if (scanner) {
                            scanner.clear();
                            scanner = null;
                        }
                    }
                });

                stopButton.addEventListener('click', stopScanner);
            })();
        </script>
    @endpush
</x-intern.layouts.app>
