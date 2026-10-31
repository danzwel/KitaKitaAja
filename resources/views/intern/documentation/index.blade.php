<x-intern.layouts.app title="Dokumentasi Magang">
    <div class="mx-auto max-w-5xl space-y-6">
        <section class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#0B1F3D] to-[#1a4480] p-6 text-white sm:p-8">
            <div class="pointer-events-none absolute -right-10 -top-10 h-40 w-40 rounded-full bg-white/5"></div>
            <div class="relative"><p class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-200/70">Penutupan Magang</p><h2 class="mt-2 font-heading text-2xl font-bold">Dokumentasi Magang Anda</h2><p class="mt-2 max-w-2xl text-sm text-blue-100/75">Masa magang Anda telah selesai. Unggah dokumentasi kegiatan sebagai bagian dari penutupan magang, maksimal {{ $maximumPhotos }} foto.</p></div>
        </section>

        <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 sm:p-8">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between"><div><h3 class="font-heading text-lg font-bold text-gray-900">Unggah Foto</h3><p class="mt-1 text-sm text-gray-500">Anda telah menggunakan {{ $photos->count() }} dari {{ $maximumPhotos }} foto. Foto akan dapat dilihat oleh admin.</p></div><span class="inline-flex shrink-0 items-center rounded-full bg-blue-50 px-3 py-1 text-sm font-semibold text-[#0C2340]">{{ $photos->count() }} / {{ $maximumPhotos }} foto</span></div>

            @if ($photos->count() < $maximumPhotos)
                <form method="POST" action="{{ route('intern.documentation.store') }}" enctype="multipart/form-data" class="mt-6 space-y-4">@csrf
                    <div><label for="photos" class="mb-1.5 block text-sm font-medium text-gray-700">Foto Dokumentasi</label><input id="photos" type="file" name="photos[]" accept="image/jpeg,image/png,image/webp" multiple required class="block w-full rounded-xl border border-gray-200 bg-gray-50/50 px-3 py-2.5 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-[#E8EEF5] file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-[#0C2340]">@error('photos')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror @error('photos.*')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror<p class="mt-2 text-xs text-gray-400">JPG, JPEG, PNG, atau WEBP · Maks. 5 MB per foto · Sisa kuota: {{ $maximumPhotos - $photos->count() }} foto</p></div>
                    <div><label for="caption" class="mb-1.5 block text-sm font-medium text-gray-700">Keterangan <span class="font-normal text-gray-400">(opsional)</span></label><input id="caption" name="caption" value="{{ old('caption') }}" maxlength="255" placeholder="Contoh: Kegiatan penutupan magang" class="block w-full rounded-xl border border-gray-200 bg-gray-50/50 px-3 py-2.5 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]"></div>
                    <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-navy px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-navy/20 transition hover:brightness-110"><i class="ti ti-upload"></i> Unggah Foto</button>
                </form>
            @else
                <div class="mt-6 rounded-xl bg-emerald-50 p-4 text-sm text-emerald-800"><i class="ti ti-circle-check mr-1"></i> Kuota lima foto dokumentasi Anda sudah terpenuhi.</div>
            @endif
        </section>

        <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 sm:p-8"><h3 class="font-heading text-lg font-bold text-gray-900">Foto yang Diunggah</h3><div class="mt-5 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">@forelse ($photos as $photo)<article class="overflow-hidden rounded-xl border border-gray-100"><a href="{{ Storage::url($photo->image_path) }}" target="_blank" rel="noopener" class="block aspect-[4/3] bg-gray-50"><img src="{{ Storage::url($photo->image_path) }}" alt="{{ $photo->caption ?: 'Dokumentasi magang' }}" class="h-full w-full object-cover"></a><div class="p-4"><p class="min-h-10 text-sm font-medium text-gray-800">{{ $photo->caption ?: 'Dokumentasi magang' }}</p><p class="mt-2 text-xs text-gray-400">Diunggah {{ $photo->created_at?->translatedFormat('d M Y, H:i') }} WIB</p></div></article>@empty<p class="sm:col-span-2 lg:col-span-3 rounded-xl bg-gray-50 py-10 text-center text-sm text-gray-400">Belum ada foto dokumentasi yang diunggah.</p>@endforelse</div></section>
    </div>
</x-intern.layouts.app>
