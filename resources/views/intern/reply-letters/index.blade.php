<x-intern.layouts.app title="Surat Balasan">
    <div class="mx-auto max-w-4xl">
        <section class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#0B1F3D] to-[#1a4480] p-6 text-white shadow-lg sm:p-8">
            <div class="pointer-events-none absolute -right-8 -top-10 h-40 w-40 rounded-full bg-white/5"></div>
            <div class="relative"><p class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-200/70">Dokumen Magang</p><h2 class="mt-2 font-heading text-2xl font-bold">Surat Balasan</h2><p class="mt-2 max-w-2xl text-sm text-blue-100/75">Surat balasan yang dikirim oleh admin dapat diunduh dari halaman ini.</p></div>
        </section>

        <section class="mt-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 sm:p-8">
            <h3 class="font-heading text-lg font-bold text-gray-900">Surat Anda</h3>
            <div class="mt-5 space-y-3">@forelse ($letters as $letter)
                <article class="flex flex-col gap-3 rounded-xl border border-gray-100 p-4 sm:flex-row sm:items-center sm:justify-between"><div class="flex items-center gap-3"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-50 text-rose-600"><i class="ti ti-file-type-pdf text-xl"></i></span><div><p class="text-sm font-semibold text-gray-900">Surat Balasan Magang</p><p class="mt-0.5 text-xs text-gray-400">Dikirim {{ $letter->created_at?->translatedFormat('d M Y, H:i') }} WIB</p></div></div><a href="{{ route('intern.reply-letters.download', $letter) }}" class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-[#0C2340] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#081A30]"><i class="ti ti-download"></i> Unduh PDF</a></article>
            @empty
                <div class="rounded-xl bg-gray-50 py-10 text-center text-sm text-gray-400"><i class="ti ti-file-off mb-2 block text-2xl"></i>Belum ada surat balasan yang dikirim oleh admin.</div>
            @endforelse</div>
        </section>
    </div>
</x-intern.layouts.app>
