<x-intern.layouts.app title="Izin dan Sakit">
    <div class="grid gap-6 lg:grid-cols-[380px_minmax(0,1fr)]">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <h2 class="font-heading text-xl font-bold text-gray-900">Ajukan Izin / Sakit</h2><p class="mt-1 text-sm text-gray-500">Pengajuan akan diperiksa oleh admin.</p>
            <form method="POST" action="{{ route('intern.attendance.leave.store') }}" enctype="multipart/form-data" class="mt-5 space-y-4">@csrf
                <div><label class="mb-1.5 block text-sm font-medium">Jenis</label><select name="type" required class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm"><option value="izin">Izin</option><option value="sakit">Sakit</option><option value="darurat">Darurat</option></select></div>
                <div class="grid grid-cols-2 gap-3"><div><label class="mb-1.5 block text-sm font-medium">Mulai</label><input type="date" name="start_date" required class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm"></div><div><label class="mb-1.5 block text-sm font-medium">Selesai</label><input type="date" name="end_date" required class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm"></div></div>
                <div><label class="mb-1.5 block text-sm font-medium">Alasan</label><textarea name="reason" rows="4" required minlength="10" class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm"></textarea></div>
                <div><label class="mb-1.5 block text-sm font-medium">Bukti (opsional)</label><input type="file" name="attachment" accept=".jpg,.jpeg,.png,.pdf" class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm"></div>
                <button class="w-full rounded-lg bg-[#0C2340] px-4 py-2.5 text-sm font-semibold text-white">Kirim Pengajuan</button>
            </form>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <h2 class="text-xl font-bold text-gray-900">Pengajuan Saya</h2>
            <div id="leave-request-list" class="mt-4 space-y-3">
                @forelse ($leaveRequests as $leave)
                    @php
                        $statusClass = match ($leave->status) {
                            'approved' => 'bg-emerald-100 text-emerald-700',
                            'rejected' => 'bg-rose-100 text-rose-700',
                            default => 'bg-amber-100 text-amber-700',
                        };
                        $statusLabel = match ($leave->status) {
                            'approved' => 'Disetujui',
                            'rejected' => 'Ditolak',
                            default => 'Menunggu',
                        };
                    @endphp
                    <div data-page-item class="rounded-xl border border-gray-100 p-4"><div class="flex justify-between gap-3"><p class="font-medium text-gray-800">{{ ucfirst($leave->type) }}</p><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClass }}">{{ $statusLabel }}</span></div><p class="mt-1 text-xs text-gray-400">{{ $leave->start_date->format('d M Y') }} - {{ $leave->end_date->format('d M Y') }}</p><p class="mt-2 text-sm text-gray-600">{{ $leave->reason }}</p></div>
                @empty
                    <p class="text-sm text-gray-400">Belum ada pengajuan.</p>
                @endforelse
            </div>
            <div id="leave-request-pagination" class="mt-5 flex items-center justify-between border-t border-gray-100 pt-4"></div>
        </div>
    </div>

    @push('scripts')
        <script>
            (() => {
                const items = [...document.querySelectorAll('#leave-request-list [data-page-item]')];
                const pagination = document.getElementById('leave-request-pagination');
                const perPage = 4;
                if (!pagination || items.length <= perPage) return;
                let page = 1;
                const totalPages = Math.ceil(items.length / perPage);
                const render = () => {
                    items.forEach((item, index) => { item.hidden = index < (page - 1) * perPage || index >= page * perPage; });
                    pagination.innerHTML = `<button type="button" data-prev class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-600 disabled:cursor-not-allowed disabled:opacity-40">Sebelumnya</button><span class="text-xs text-gray-400">Halaman ${page} dari ${totalPages}</span><button type="button" data-next class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-600 disabled:cursor-not-allowed disabled:opacity-40">Berikutnya</button>`;
                    const previous = pagination.querySelector('[data-prev]');
                    const next = pagination.querySelector('[data-next]');
                    previous.disabled = page === 1; next.disabled = page === totalPages;
                    previous.onclick = () => { if (page > 1) { page--; render(); } };
                    next.onclick = () => { if (page < totalPages) { page++; render(); } };
                };
                render();
            })();
        </script>
    @endpush
</x-intern.layouts.app>
