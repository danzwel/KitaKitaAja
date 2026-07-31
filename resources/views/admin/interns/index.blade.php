<x-admin.layouts.app title="Mahasiswa Magang">

    <x-admin.card>
        <form method="GET" class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="relative flex-1">
                <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-[#8B958A]"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau universitas..."
                       class="w-full rounded-lg border border-[#E3E5DE] py-2.5 pl-10 pr-3 text-sm focus:border-[#0F6E56] focus:outline-none focus:ring-1 focus:ring-[#0F6E56]">
            </div>

            <select name="status" onchange="this.form.submit()"
                    class="rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0F6E56] focus:outline-none focus:ring-1 focus:ring-[#0F6E56]">
                <option value="">Semua Status</option>
                <option value="aktif" @selected(request('status') === 'aktif')>Aktif</option>
                <option value="selesai" @selected(request('status') === 'selesai')>Selesai</option>
            </select>

            <button type="submit" class="rounded-lg bg-[#0F6E56] px-4 py-2.5 text-sm font-medium text-white hover:bg-[#0B5443]">
                Terapkan
            </button>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-[#E3E5DE] text-xs uppercase tracking-wide text-[#64705F]">
                        <th class="whitespace-nowrap py-3 pr-4">Nama</th>
                        <th class="whitespace-nowrap py-3 pr-4">Universitas</th>
                        <th class="whitespace-nowrap py-3 pr-4">Bidang</th>
                        <th class="whitespace-nowrap py-3 pr-4">Periode</th>
                        <th class="whitespace-nowrap py-3 pr-4">Status</th>
                        <th class="whitespace-nowrap py-3 pr-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EFF1EC]">
                    @forelse ($interns as $intern)
                        <tr class="hover:bg-[#F6F7F4]">
                            <td class="py-3 pr-4 font-medium text-[#1E2A24]">{{ $intern->name }}</td>
                            <td class="py-3 pr-4 text-[#4B564B]">{{ $intern->university }}</td>
                            <td class="py-3 pr-4 text-[#4B564B]">{{ $intern->department->name ?? '-' }}</td>
                            <td class="py-3 pr-4 text-[#4B564B]">{{ $intern->period }}</td>
                            <td class="py-3 pr-4"><x-admin.badge :status="$intern->status" /></td>
                            <td class="py-3 pr-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.interns.show', $intern) }}" class="rounded-lg border border-[#E3E5DE] p-1.5 text-[#1E2A24] hover:bg-[#F6F7F4]" title="Detail">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.interns.edit', $intern) }}" class="rounded-lg border border-[#E3E5DE] p-1.5 text-[#1E2A24] hover:bg-[#F6F7F4]" title="Edit">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.interns.destroy', $intern) }}" onsubmit="return confirm('Hapus data mahasiswa ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-[#F0C9C9] p-1.5 text-[#9B3A3A] hover:bg-[#FBEAEA]" title="Hapus">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-sm text-[#8B958A]">Belum ada mahasiswa magang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-5">
            {{ $interns->links() }}
        </div>
    </x-admin.card>
</x-admin.layouts.app>
