<x-admin.layouts.app title="Dokumentasi Magang">
    <div class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-medium text-[#8A94A6]">Album unggahan mahasiswa</p>
                <h2 class="mt-1 font-heading text-2xl font-bold text-[#0C2340]">Dokumentasi Magang</h2>
                <p class="mt-2 max-w-2xl text-sm text-[#687386]">
                    Dokumentasi diunggah oleh mahasiswa setelah masa magangnya selesai.
                    Admin dapat melihat hasil unggahan, dengan batas maksimal lima foto untuk setiap mahasiswa.
                </p>
            </div>

            <div class="rounded-xl bg-[#E8EEF5] px-4 py-3 text-sm text-[#0C2340]">
                <span class="font-heading text-xl font-bold">{{ $photos->count() }}</span>
                <span class="ml-1 text-xs font-medium text-[#687386]">
                    foto &middot; {{ $selectedYear }}
                </span>
            </div>
        </div>

        <x-admin.card title="Album Dokumentasi" subtitle="Pilih tahun untuk melihat foto dokumentasi yang tersimpan.">
            <form method="GET" class="mb-5 flex flex-wrap items-center gap-2">
                <label for="year" class="text-sm font-medium text-[#1E2A24]">Tahun</label>

                <select id="year" name="year" onchange="this.form.submit()"
                    class="rounded-lg border border-[#E3E5DE] px-3 py-2 text-sm focus:border-[#0C2340] focus:outline-none">
                    @foreach ($years as $year)
                        <option value="{{ $year }}" @selected((int) $year === $selectedYear)>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </form>

            <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                @forelse ($photos as $photo)
                    <article class="overflow-hidden rounded-xl border border-[#E7EAF1] bg-white">
                        <a href="{{ Storage::url($photo->image_path) }}" target="_blank" rel="noopener"
                            class="block aspect-[4/3] bg-[#F4F6FB]">
                            <img src="{{ Storage::url($photo->image_path) }}"
                                alt="{{ $photo->caption ?: 'Dokumentasi magang '.$photo->year }}"
                                class="h-full w-full object-cover transition duration-200 hover:scale-[1.02]">
                        </a>

                        <div class="p-4">
                            <p class="min-h-10 text-sm font-medium text-[#1E2A24]">
                                {{ $photo->caption ?: 'Dokumentasi magang '.$photo->year }}
                            </p>

                            <p class="mt-3 text-xs text-[#8A94A6]">
                                Oleh {{ $photo->intern?->name ?? $photo->uploadedBy?->name ?? 'Data lama' }}

                                @if ($photo->intern?->university)
                                    &middot; {{ $photo->intern->university }}
                                @endif
                            </p>

                            <p class="mt-1 text-xs text-[#8A94A6]">
                                {{ $photo->created_at?->translatedFormat('d M Y') }}
                            </p>
                        </div>
                    </article>
                @empty
                    <div class="sm:col-span-2 xl:col-span-3 rounded-xl bg-[#F8FAFD] py-12 text-center text-sm text-[#8A94A6]">
                        Belum ada foto dokumentasi untuk tahun {{ $selectedYear }}.
                    </div>
                @endforelse
            </div>
        </x-admin.card>
    </div>
</x-admin.layouts.app>
