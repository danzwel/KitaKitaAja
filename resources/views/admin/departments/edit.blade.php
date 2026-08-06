<x-admin.layouts.app title="Edit Bidang">

    <a href="{{ route('admin.departments.index') }}" class="mb-4 inline-flex items-center gap-1.5 text-sm font-medium text-[#4B564B] hover:text-[#1E2A24]">
        <i class="ti ti-arrow-left"></i> Kembali ke daftar bidang
    </a>

    <x-admin.card title="Edit Bidang Magang" class="max-w-2xl">
        <form method="POST" action="{{ route('admin.departments.update', $department) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Nama Bidang</label>
                <input type="text" name="nama_bidang" value="{{ old('nama_bidang', $department->nama_bidang) }}" required
                       class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]">
            </div>

            <label class="flex items-center gap-2 text-sm text-[#4B564B]">
                <input type="checkbox" name="requires_portfolio" value="1" @checked(old('requires_portfolio', $department->requires_portfolio)) class="rounded border-[#E3E5DE] text-[#0C2340] focus:ring-[#0C2340]">
                Memerlukan portofolio
            </label>

            <label class="flex items-center gap-2 text-sm text-[#4B564B]">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $department->is_active)) class="rounded border-[#E3E5DE] text-[#0C2340] focus:ring-[#0C2340]">
                Aktifkan bidang ini
            </label>

            <div class="flex justify-end gap-2 pt-2">
                <a href="{{ route('admin.departments.index') }}" class="rounded-lg border border-[#E3E5DE] px-4 py-2.5 text-sm font-medium text-[#4B564B]">Batal</a>
                <button type="submit" class="rounded-lg bg-[#0C2340] px-4 py-2.5 text-sm font-medium text-white hover:bg-[#081A30]">Simpan Perubahan</button>
            </div>
        </form>
    </x-admin.card>
</x-admin.layouts.app>

