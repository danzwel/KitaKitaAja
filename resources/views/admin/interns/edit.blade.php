<x-admin.layouts.app title="Edit Mahasiswa">

    <a href="{{ route('admin.interns.index') }}" class="mb-4 inline-flex items-center gap-1.5 text-sm font-medium text-[#4B564B] hover:text-[#1E2A24]">
        <i class="ti ti-arrow-left"></i> Kembali ke daftar mahasiswa magang
    </a>

    <x-admin.card title="Edit Data Mahasiswa" class="max-w-2xl">
        <form method="POST" action="{{ route('admin.interns.update', $intern) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Nama</label>
                <input type="text" name="name" value="{{ old('name', $intern->name) }}" required
                       class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0F6E56] focus:outline-none focus:ring-1 focus:ring-[#0F6E56]">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Universitas</label>
                <input type="text" name="university" value="{{ old('university', $intern->university) }}" required
                       class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0F6E56] focus:outline-none focus:ring-1 focus:ring-[#0F6E56]">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Bidang Magang</label>
                <select name="department_id" required
                        class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0F6E56] focus:outline-none focus:ring-1 focus:ring-[#0F6E56]">
                    @foreach (\App\Models\Department::active()->get() as $department)
                        <option value="{{ $department->id }}" @selected(old('department_id', $intern->department_id) == $department->id)>{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Periode</label>
                <input type="text" name="period" value="{{ old('period', $intern->period) }}" required
                       class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0F6E56] focus:outline-none focus:ring-1 focus:ring-[#0F6E56]">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Status</label>
                <select name="status" required
                        class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0F6E56] focus:outline-none focus:ring-1 focus:ring-[#0F6E56]">
                    <option value="aktif" @selected(old('status', $intern->status) === 'aktif')>Aktif</option>
                    <option value="selesai" @selected(old('status', $intern->status) === 'selesai')>Selesai</option>
                </select>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <a href="{{ route('admin.interns.index') }}" class="rounded-lg border border-[#E3E5DE] px-4 py-2.5 text-sm font-medium text-[#4B564B]">Batal</a>
                <button type="submit" class="rounded-lg bg-[#0F6E56] px-4 py-2.5 text-sm font-medium text-white hover:bg-[#0B5443]">Simpan Perubahan</button>
            </div>
        </form>
    </x-admin.card>
</x-admin.layouts.app>
