<x-admin.layouts.app title="Profil Saya">
    <div class="mx-auto max-w-3xl space-y-6">
        <div>
            <p class="text-sm font-medium text-[#8A94A6]">Pengaturan akun</p>
            <h2 class="mt-1 font-heading text-2xl font-bold text-[#0C2340]">Profil Saya</h2>
        </div>

        <x-admin.card title="Informasi Profil" subtitle="Perbarui nama dan email akun administrator.">
            <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-5">
                @csrf
                @method('PATCH')

                <div>
                    <label for="name" class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Nama</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $admin->name) }}" required
                           class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]">
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email" class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $admin->email) }}" required
                           class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]">
                    @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="rounded-lg bg-[#0C2340] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#081A30]">Simpan Profil</button>
            </form>
        </x-admin.card>

        <x-admin.card title="Ubah Password" subtitle="Gunakan password minimal 8 karakter.">
            <form method="POST" action="{{ route('admin.profile.password') }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Password Saat Ini</label>
                    <input id="current_password" name="current_password" type="password" required
                           class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]">
                    @error('current_password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="password" class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Password Baru</label>
                        <input id="password" name="password" type="password" required
                               class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]">
                    </div>
                    <div>
                        <label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Konfirmasi Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                               class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]">
                    </div>
                </div>

                <button type="submit" class="rounded-lg border border-[#0C2340] px-4 py-2.5 text-sm font-semibold text-[#0C2340] hover:bg-[#E8EEF5]">Perbarui Password</button>
            </form>
        </x-admin.card>
    </div>
</x-admin.layouts.app>
