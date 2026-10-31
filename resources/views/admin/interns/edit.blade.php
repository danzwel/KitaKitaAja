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
                <select name="department_id" required
                        class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]">
                    @foreach (\App\Models\Department::active()->get() as $department)
                        <option value="{{ $department->id }}" @selected(old('department_id', $intern->department_id) == $department->id)>{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Periode</label>
                <input type="text" name="period" value="{{ old('period', $intern->period) }}" required
                       class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]">
            </div>

            <div class="rounded-lg bg-[#F4F6FB] p-4 text-sm text-[#687386]"><p class="font-semibold text-[#0C2340]">Status magang: {{ ucfirst($intern->status) }}</p><p class="mt-1 text-xs">Status berubah otomatis menjadi selesai setelah tanggal akhir periode magang berlalu.</p></div>

            <div class="flex justify-end gap-2 pt-2">
                <a href="{{ route('admin.interns.index') }}" class="rounded-lg border border-[#E3E5DE] px-4 py-2.5 text-sm font-medium text-[#4B564B]">Batal</a>
                <button type="submit" class="rounded-lg bg-[#0C2340] px-4 py-2.5 text-sm font-medium text-white hover:bg-[#081A30]">Simpan Perubahan</button>
            </div>
        </form>
    </x-admin.card>
</x-admin.layouts.app>

