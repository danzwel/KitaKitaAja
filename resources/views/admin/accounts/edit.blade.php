<x-admin.layouts.app title="Kelola Akun Admin">
    <div class="mx-auto max-w-3xl space-y-6">
        <div><a href="{{ route('admin.accounts.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#0C2340] hover:underline"><i class="ti ti-arrow-left"></i> Kembali ke daftar admin</a><p class="mt-5 text-sm font-medium text-[#8A94A6]">Akses operasional aplikasi</p><h2 class="mt-1 font-heading text-2xl font-bold text-[#0C2340]">Kelola Akun Admin</h2></div>
        <x-admin.card title="Informasi Akun" subtitle="Menonaktifkan akun menghentikan akses login tanpa menghapus riwayat pekerjaannya.">
            <form method="POST" action="{{ route('admin.accounts.update', $account) }}" class="space-y-5">@csrf @method('PUT')
                <div><label for="name" class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Nama</label><input id="name" name="name" value="{{ old('name', $account->name) }}" required class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]">@error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                <div><label for="email" class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Email</label><input id="email" type="email" name="email" value="{{ old('email', $account->email) }}" required class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]">@error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                <div><p class="mb-1.5 text-sm font-medium text-[#1E2A24]">Status Akses</p><label class="flex items-center gap-3 rounded-lg border border-[#E3E5DE] p-3 text-sm"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $account->is_active)) class="rounded border-[#C9CED8] text-[#0C2340]"><span><span class="block font-semibold text-[#1E2A24]">Akun aktif</span><span class="block text-xs text-[#687386]">Admin dapat masuk dan menggunakan seluruh menu operasional.</span></span></label>@error('is_active')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                <button class="rounded-lg bg-[#0C2340] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#081A30]">Simpan Perubahan</button>
            </form>
        </x-admin.card>
        <x-admin.card title="Reset Password" subtitle="Tentukan password baru dan sampaikan secara aman kepada admin terkait.">
            <form method="POST" action="{{ route('admin.accounts.password', $account) }}" class="space-y-5">@csrf @method('PATCH')
                <div class="grid gap-5 sm:grid-cols-2"><div><label for="password" class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Password Baru</label><input id="password" type="password" name="password" required minlength="8" class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]"></div><div><label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Konfirmasi Password</label><input id="password_confirmation" type="password" name="password_confirmation" required minlength="8" class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]"></div></div>
                <button class="rounded-lg border border-[#0C2340] px-4 py-2.5 text-sm font-semibold text-[#0C2340] hover:bg-[#E8EEF5]">Perbarui Password</button>
            </form>
        </x-admin.card>
    </div>
</x-admin.layouts.app>
