<x-admin.layouts.app title="Tambah Akun Admin">
    <div class="mx-auto max-w-3xl space-y-6">
        <div><a href="{{ route('admin.accounts.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#0C2340] hover:underline"><i class="ti ti-arrow-left"></i> Kembali ke daftar admin</a><p class="mt-5 text-sm font-medium text-[#8A94A6]">Akses operasional aplikasi</p><h2 class="mt-1 font-heading text-2xl font-bold text-[#0C2340]">Tambah Akun Admin</h2></div>
        <x-admin.card title="Data Administrator" subtitle="Akun ini dapat mengelola pengajuan, peserta, absensi, bidang magang, dan akun admin tanpa akses ke hosting.">
            <form method="POST" action="{{ route('admin.accounts.store') }}" class="space-y-5">@csrf
                <div><label for="name" class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Nama</label><input id="name" name="name" value="{{ old('name') }}" required autofocus class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]">@error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                <div><label for="email" class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Email</label><input id="email" type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]">@error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                <div class="grid gap-5 sm:grid-cols-2"><div><label for="password" class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Password</label><input id="password" type="password" name="password" required minlength="8" class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]"></div><div><label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Konfirmasi Password</label><input id="password_confirmation" type="password" name="password_confirmation" required minlength="8" class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]"></div></div>
                <button class="rounded-lg bg-[#0C2340] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#081A30]">Simpan Akun Admin</button>
            </form>
        </x-admin.card>
    </div>
</x-admin.layouts.app>
