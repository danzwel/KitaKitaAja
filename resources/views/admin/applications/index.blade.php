<x-admin.layouts.app title="Kelola Pengajuan">

    <x-admin.card>
        {{-- Search & Filter --}}
        <form method="GET" class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="relative flex-1">
                <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-[#8B958A]"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, NIM, atau universitas..."
                       class="w-full rounded-lg border border-[#E3E5DE] py-2.5 pl-10 pr-3 text-sm focus:border-[#0F6E56] focus:outline-none focus:ring-1 focus:ring-[#0F6E56]">
            </div>

            <select name="status" onchange="this.form.submit()"
                    class="rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0F6E56] focus:outline-none focus:ring-1 focus:ring-[#0F6E56]">
                <option value="">Semua Status</option>
                @foreach (['menunggu' => 'Menunggu', 'diproses' => 'Diproses', 'diterima' => 'Diterima', 'ditolak' => 'Ditolak'] as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>

            <button type="submit" class="rounded-lg bg-[#0F6E56] px-4 py-2.5 text-sm font-medium text-white hover:bg-[#0B5443]">
                Terapkan
            </button>
        </form>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-[#E3E5DE] text-xs uppercase tracking-wide text-[#64705F]">
                        <th class="whitespace-nowrap py-3 pr-4">Nama</th>
                        <th class="whitespace-nowrap py-3 pr-4">NIM</th>
                        <th class="whitespace-nowrap py-3 pr-4">Universitas</th>
                        <th class="whitespace-nowrap py-3 pr-4">Bidang</th>
                        <th class="whitespace-nowrap py-3 pr-4">Periode</th>
                        <th class="whitespace-nowrap py-3 pr-4">Tgl. Pengajuan</th>
                        <th class="whitespace-nowrap py-3 pr-4">Status</th>
                        <th class="whitespace-nowrap py-3 pr-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EFF1EC]">
                    @forelse ($applications as $application)
                        <tr class="hover:bg-[#F6F7F4]">
                            <td class="py-3 pr-4 font-medium text-[#1E2A24]">{{ $application->name }}</td>
                            <td class="py-3 pr-4 text-[#4B564B]">{{ $application->nim }}</td>
                            <td class="py-3 pr-4 text-[#4B564B]">{{ $application->university }}</td>
                            <td class="py-3 pr-4 text-[#4B564B]">{{ $application->department->name ?? '-' }}</td>
                            <td class="py-3 pr-4 text-[#4B564B]">{{ $application->period }}</td>
                            <td class="py-3 pr-4 text-[#4B564B]">{{ $application->application_date?->translatedFormat('d M Y') }}</td>
                            <td class="py-3 pr-4"><x-admin.badge :status="$application->status" /></td>
                            <td class="py-3 pr-4 text-right">
                                <a href="{{ route('admin.applications.show', $application) }}"
                                   class="inline-flex items-center gap-1 rounded-lg border border-[#E3E5DE] px-3 py-1.5 text-xs font-medium text-[#1E2A24] hover:bg-[#F6F7F4]">
                                    <i class="ti ti-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-10 text-center text-sm text-[#8B958A]">Tidak ada data pengajuan ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-5">
            {{ $applications->links() }}
        </div>
    </x-admin.card>
</x-admin.layouts.app>