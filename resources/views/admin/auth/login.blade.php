<x-admin.layouts.guest>
    <x-admin.alert />

    <form method="POST" action="{{ route('admin.login') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm text-[#1E2A24] focus:border-[#0F6E56] focus:outline-none focus:ring-1 focus:ring-[#0F6E56]"
                   placeholder="admin@uptdpelatihankesehatan.go.id">
        </div>

        <div>
            <label for="password" class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Password</label>
            <input id="password" type="password" name="password" required
                   class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm text-[#1E2A24] focus:border-[#0F6E56] focus:outline-none focus:ring-1 focus:ring-[#0F6E56]"
                   placeholder="••••••••">
        </div>

        <label class="flex items-center gap-2 text-sm text-[#4B564B]">
            <input type="checkbox" name="remember" class="rounded border-[#E3E5DE] text-[#0F6E56] focus:ring-[#0F6E56]">
            Ingat saya
        </label>

        <button type="submit"
                class="flex w-full items-center justify-center gap-2 rounded-lg bg-[#0F6E56] px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-[#0B5443]">
            <i class="ti ti-login-2"></i> Masuk
        </button>
    </form>
</x-admin.layouts.guest>
