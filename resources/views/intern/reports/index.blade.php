<x-intern.layouts.app title="Laporan Magang">
    <div class="mx-auto max-w-3xl space-y-6">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#0B1F3D] to-[#1a4480] p-6 text-white sm:p-8"><div class="pointer-events-none absolute -right-10 -top-10 h-40 w-40 rounded-full bg-white/5"></div><div class="relative"><p class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-200/70">Dokumen Akhir</p><h2 class="mt-2 font-heading text-2xl font-bold">Laporan Hasil Magang</h2><p class="mt-2 max-w-xl text-sm text-blue-100/75">Unggah laporan akhir Anda agar dapat ditinjau oleh admin UPTD Pelatihan Kesehatan.</p></div></div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 sm:p-8">
            @if ($intern->final_report_path)
                <div class="mb-6 flex flex-col gap-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4 sm:flex-row sm:items-center sm:justify-between"><div class="flex items-center gap-3"><span class="flex h-11 w-11 items-center justify-center rounded-xl bg-white text-emerald-700 shadow-sm"><i class="ti ti-file-check text-xl"></i></span><div><p class="font-semibold text-gray-900">Laporan sudah diunggah</p><p class="mt-1 text-xs text-gray-500">Diunggah {{ $intern->final_report_uploaded_at?->translatedFormat('d M Y, H:i') ?? '-' }} WIB</p></div></div><a href="{{ Storage::url($intern->final_report_path) }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 rounded-xl border border-emerald-300 bg-white px-4 py-2.5 text-sm font-semibold text-emerald-800 hover:bg-emerald-100"><i class="ti ti-download"></i> Lihat / Unduh</a></div>
            @else
                <div class="mb-6 rounded-xl bg-[#F4F6FB] p-4 text-sm text-gray-600"><i class="ti ti-info-circle mr-1 text-[#0C2340]"></i> Belum ada laporan yang diunggah.</div>
            @endif

            <h3 class="font-heading text-lg font-bold text-gray-900">{{ $intern->final_report_path ? 'Ganti Laporan' : 'Unggah Laporan' }}</h3>
            <p class="mt-1 text-sm text-gray-500">Unggahan baru akan menggantikan berkas laporan sebelumnya dan memberi notifikasi kepada admin.</p>
            <form method="POST" action="{{ route('intern.reports.store') }}" enctype="multipart/form-data" class="mt-5 space-y-4">@csrf
                <div><label for="final_report" class="mb-1.5 block text-sm font-medium text-gray-700">Berkas Laporan</label><input id="final_report" type="file" name="final_report" accept="application/pdf,.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" required class="block w-full rounded-xl border border-gray-200 bg-gray-50/50 px-3 py-2.5 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-[#E8EEF5] file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-[#0C2340]">@error('final_report')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror<p class="mt-2 text-xs text-gray-400">Format PDF, DOC, atau DOCX · Maks. 10 MB</p></div>
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-navy px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-navy/20 transition hover:brightness-110"><i class="ti ti-upload"></i> Unggah Laporan</button>
            </form>
        </div>
    </div>
</x-intern.layouts.app>
