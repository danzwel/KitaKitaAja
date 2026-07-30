<x-admin.layouts.app title="Tambah Bidang">

    <a href="{{ route('admin.departments.index') }}" class="mb-4 inline-flex items-center gap-1.5 text-sm font-medium text-[#4B564B] hover:text-[#1E2A24]">
        <i class="ti ti-arrow-left"></i> Kembali ke daftar bidang
    </a>

    <x-admin.card title="Tambah Bidang Magang" class="max-w-2xl">
        <form method="POST" action="{{ route('admin.departments.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Nama Bidang</label>
                <input type="text" name="nama_bidang" value="{{ old('nama_bidang') }}" required
                       class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0F6E56] focus:outline-none focus:ring-1 focus:ring-[#0F6E56]"
                       placeholder="Contoh: Rekam Medis">
            </div>

            <label class="flex items-center gap-2 text-sm text-[#4B564B]">
                <input type="checkbox" name="requires_portfolio" value="1" @checked(old('requires_portfolio')) class="rounded border-[#E3E5DE] text-[#0F6E56] focus:ring-[#0F6E56]">
                Memerlukan portofolio
            </label>

            <label class="flex items-center gap-2 text-sm text-[#4B564B]">
                <input type="checkbox" name="is_active" value="1" checked class="rounded border-[#E3E5DE] text-[#0F6E56] focus:ring-[#0F6E56]">
                Aktifkan bidang ini
            </label>

            <div class="flex justify-end gap-2 pt-2">
                <a href="{{ route('admin.departments.index') }}" class="rounded-lg border border-[#E3E5DE] px-4 py-2.5 text-sm font-medium text-[#4B564B]">Batal</a>
                <button type="submit" class="rounded-lg bg-[#0F6E56] px-4 py-2.5 text-sm font-medium text-white hover:bg-[#0B5443]">Simpan</button>
            </div>
        </form>
    </x-admin.card>
</x-admin.layouts.app>
