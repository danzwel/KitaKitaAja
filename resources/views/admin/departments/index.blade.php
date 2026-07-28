<x-admin.layouts.app title="Bidang Magang">

    <x-admin.card>
        <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <form method="GET" class="relative flex-1 sm:max-w-xs">
                <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-[#8B958A]"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari bidang..."
                       class="w-full rounded-lg border border-[#E3E5DE] py-2.5 pl-10 pr-3 text-sm focus:border-[#0F6E56] focus:outline-none focus:ring-1 focus:ring-[#0F6E56]">
            </form>

            <a href="{{ route('admin.departments.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#0F6E56] px-4 py-2.5 text-sm font-medium text-white hover:bg-[#0B5443]">
                <i class="ti ti-plus"></i> Tambah Bidang
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-[#E3E5DE] text-xs uppercase tracking-wide text-[#64705F]">
                        <th class="whitespace-nowrap py-3 pr-4">Nama Bidang</th>
                        <th class="whitespace-nowrap py-3 pr-4">Deskripsi</th>
                        <th class="whitespace-nowrap py-3 pr-4">Status</th>
                        <th class="whitespace-nowrap py-3 pr-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EFF1EC]">
                    @forelse ($departments as $department)
                        <tr class="hover:bg-[#F6F7F4]">
                            <td class="py-3 pr-4 font-medium text-[#1E2A24]">{{ $department->name }}</td>
                            <td class="py-3 pr-4 max-w-xs truncate text-[#4B564B]">{{ $department->description ?: '-' }}</td>
                            <td class="py-3 pr-4">
                                @if ($department->is_active)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-[#E7F2ED] px-2.5 py-1 text-xs font-medium text-[#0B5443]">
                                        <span class="h-1.5 w-1.5 rounded-full bg-[#0F6E56]"></span> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-[#EFF1EC] px-2.5 py-1 text-xs font-medium text-[#5B6660]">
                                        <span class="h-1.5 w-1.5 rounded-full bg-[#8B958A]"></span> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 pr-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.departments.edit', $department) }}" class="rounded-lg border border-[#E3E5DE] p-1.5 text-[#1E2A24] hover:bg-[#F6F7F4]" title="Edit">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.departments.destroy', $department) }}" onsubmit="return confirm('Hapus bidang ini?');">
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
                            <td colspan="4" class="py-10 text-center text-sm text-[#8B958A]">Belum ada data bidang magang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-5">
            {{ $departments->links() }}
        </div>
    </x-admin.card>
</x-admin.layouts.app>
