<x-admin.layouts.app title="Manajemen Akun Admin">
    <div class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div><p class="text-sm font-medium text-[#8A94A6]">Akses operasional aplikasi</p><h2 class="mt-1 font-heading text-2xl font-bold text-[#0C2340]">Manajemen Akun Admin</h2><p class="mt-2 max-w-2xl text-sm text-[#687386]">Kelola akun petugas dari aplikasi tanpa memberikan akses ke hosting atau data teknis server.</p></div>
            <a href="{{ route('admin.accounts.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#0C2340] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#081A30]"><i class="ti ti-user-plus"></i> Tambah Admin</a>
        </div>

        <x-admin.card title="Daftar Administrator" subtitle="Admin nonaktif tidak dapat masuk, tetapi riwayat tindakan dan data yang pernah dikelolanya tetap tersimpan.">
            <div class="responsive-table"><table class="w-full min-w-[760px] text-left text-sm"><thead><tr class="border-b border-[#E7EAF1] text-xs uppercase tracking-wide text-[#8A94A6]"><th class="py-3 pr-4">Administrator</th><th class="py-3 pr-4">Email</th><th class="py-3 pr-4">Status</th><th class="py-3 pr-4">Dibuat</th><th class="py-3 text-right">Aksi</th></tr></thead><tbody class="divide-y divide-[#EEF0F5]">
                @forelse ($admins as $account)
                    <tr class="hover:bg-[#F8FAFD]"><td class="py-4 pr-4"><p class="font-semibold text-[#0C2340]">{{ $account->name }} @if($account->is(auth('admin')->user())) <span class="ml-1 text-xs font-medium text-[#8A94A6]">(Anda)</span> @endif</p></td><td class="py-4 pr-4 text-[#687386]">{{ $account->email }}</td><td class="py-4 pr-4">@if($account->is_active)<span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Aktif</span>@else<span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">Nonaktif</span>@endif</td><td class="py-4 pr-4 text-xs text-[#8A94A6]">{{ $account->created_at?->format('d M Y') }}</td><td class="py-4 text-right">@if($account->is(auth('admin')->user()))<a href="{{ route('admin.profile.edit') }}" class="inline-flex items-center gap-1 rounded-lg border border-[#E3E5DE] px-3 py-2 text-xs font-semibold text-[#0C2340] hover:bg-[#F4F6FB]"><i class="ti ti-user-circle"></i> Profil Saya</a>@else<a href="{{ route('admin.accounts.edit', $account) }}" class="inline-flex items-center gap-1 rounded-lg border border-[#E3E5DE] px-3 py-2 text-xs font-semibold text-[#0C2340] hover:bg-[#F4F6FB]"><i class="ti ti-settings"></i> Kelola</a>@endif</td></tr>
                @empty
                    <tr><td colspan="5" class="py-10 text-center text-sm text-[#8A94A6]">Belum ada akun admin.</td></tr>
                @endforelse
            </tbody></table></div>
        </x-admin.card>
    </div>
</x-admin.layouts.app>
