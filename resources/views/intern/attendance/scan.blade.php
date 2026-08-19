<x-intern.layouts.app title="Scan Absensi">
    <div class="mx-auto max-w-lg">
        <div class="rounded-2xl bg-white p-6 text-center shadow-sm ring-1 ring-gray-100 sm:p-8">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#E8EEF5] text-[#0C2340]"><i class="ti ti-fingerprint text-3xl"></i></div>
            <p class="mt-5 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Absen {{ ucfirst($session->type) }}</p>
            <h1 class="mt-2 font-heading text-2xl font-bold text-gray-900">Konfirmasi Lokasi</h1>
            <p class="mt-3 text-sm leading-relaxed text-gray-500">Izinkan akses lokasi agar sistem dapat mencatat posisi saat absensi.</p>
            <div id="scan-status" class="mt-5 rounded-xl bg-gray-50 p-4 text-sm text-gray-600">Meminta lokasi perangkat...</div>
            <form id="attendance-form" method="POST" action="{{ route('intern.attendance.scan.store', $session) }}" class="hidden">@csrf<input name="latitude"><input name="longitude"><button type="submit" class="mt-4 rounded-lg bg-[#0C2340] px-4 py-2.5 text-sm font-semibold text-white">Kirim Absensi</button></form>
        </div>
    </div>
    @push('scripts')<script>
        const statusBox = document.getElementById('scan-status'); const form = document.getElementById('attendance-form');
        if (!navigator.geolocation) { statusBox.textContent = 'Perangkat tidak mendukung GPS. Anda tetap dapat mengirim absensi untuk diverifikasi admin.'; form.classList.remove('hidden'); }
        else navigator.geolocation.getCurrentPosition(position => { form.latitude.value = position.coords.latitude; form.longitude.value = position.coords.longitude; statusBox.textContent = 'Lokasi ditemukan. Absensi sedang disimpan...'; form.submit(); }, () => { statusBox.textContent = 'Lokasi tidak diberikan. Anda dapat mengirim tanpa GPS untuk diverifikasi admin.'; form.classList.remove('hidden'); });
    </script>@endpush
</x-intern.layouts.app>
