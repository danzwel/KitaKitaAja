<x-admin.layouts.app title="Edit Peserta">

    <a href="{{ route('admin.interns.index') }}" class="mb-4 inline-flex items-center gap-1.5 text-sm font-medium text-[#4B564B] hover:text-[#1E2A24]">
        <i class="ti ti-arrow-left"></i> Kembali ke daftar peserta magang
    </a>

    <x-admin.card title="Edit Data Peserta" class="max-w-2xl">
        <form method="POST" action="{{ route('admin.interns.update', $intern) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Nama</label>
                <input type="text" name="name" value="{{ old('name', $intern->name) }}" required
                       class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Universitas</label>
                <input type="text" name="university" value="{{ old('university', $intern->university) }}" required
                       class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Bidang Magang</label>
                <select name="bidang_id" required
                        class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]">
                    @foreach ($bidangs as $bidang)
                        <option value="{{ $bidang->id }}" @selected(old('bidang_id', $intern->internshipApplication?->bidang_id) == $bidang->id)>{{ $bidang->nama_bidang }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Tanggal Mulai</label>
                    <input type="date" name="periode_mulai" value="{{ old('periode_mulai', $intern->internshipApplication?->periode_mulai?->toDateString()) }}" required
                           class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]">
                    @error('periode_mulai')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Tanggal Selesai</label>
                    <input type="date" name="periode_selesai" value="{{ old('periode_selesai', $intern->internshipApplication?->periode_selesai?->toDateString()) }}" required
                           class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]">
                    @error('periode_selesai')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="rounded-lg bg-[#F4F6FB] p-4 text-sm text-[#687386]"><p class="font-semibold text-[#0C2340]">Status magang: {{ ucfirst($intern->status) }}</p><p class="mt-1 text-xs">Perubahan data ini juga langsung digunakan di akun peserta, absensi, dan sertifikat. Status akan disesuaikan dengan tanggal selesai.</p></div>

            <div class="flex justify-end gap-2 pt-2">
                <a href="{{ route('admin.interns.index') }}" class="rounded-lg border border-[#E3E5DE] px-4 py-2.5 text-sm font-medium text-[#4B564B]">Batal</a>
                <button type="submit" class="rounded-lg bg-[#0C2340] px-4 py-2.5 text-sm font-medium text-white hover:bg-[#081A30]">Simpan Perubahan</button>
            </div>
        </form>
    </x-admin.card>
</x-admin.layouts.app>

