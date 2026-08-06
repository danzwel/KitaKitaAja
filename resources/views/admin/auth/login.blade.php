<x-admin.layouts.guest>
    <x-admin.alert />

    <form method="POST" action="{{ route('admin.login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="mb-1.5 block text-sm font-semibold text-[#0C2340]">Email Administrator</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-[#0C2340] transition focus:border-[#1E5AA8] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E5AA8]/20"
                   placeholder="admin@uptdpelatihankesehatan.go.id">
        </div>

        <div class="relative">
            <label for="password" class="mb-1.5 block text-sm font-semibold text-[#0C2340]">Password</label>
            <input id="password" type="password" name="password" required
                   class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 pr-12 text-sm text-[#0C2340] transition focus:border-[#1E5AA8] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E5AA8]/20"
                   placeholder="••••••••">
            <button type="button" data-password-toggle="password"
                    class="absolute right-3 top-9 rounded-lg p-1 text-slate-400 transition hover:text-[#0C2340] focus:outline-none focus:ring-2 focus:ring-[#1E5AA8]/30"
                    aria-label="Tampilkan password">
                <svg data-password-icon="eye" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z" />
                    <circle cx="12" cy="12" r="2.5" />
                </svg>
                <svg data-password-icon="eye-off" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m3 3 18 18M10.6 10.6a2 2 0 0 0 2.8 2.8M9.9 5.2A10.5 10.5 0 0 1 12 5c6 0 9.5 7 9.5 7a17 17 0 0 1-3.1 3.9M6.2 6.3C3.8 8.1 2.5 12 2.5 12s3.5 7 9.5 7c1.5 0 2.8-.4 4-.9" />
                </svg>
            </button>
        </div>

        <label class="flex items-center gap-2 text-sm text-slate-500">
            <input type="checkbox" name="remember" class="rounded border-slate-300 text-[#0C2340] focus:ring-[#0C2340]">
            Ingat saya
        </label>

        <button type="submit"
                class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#0C2340] px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-[#0C2340]/20 transition hover:bg-[#123A68]">
            <i class="ti ti-login-2"></i> Masuk
        </button>
    </form>
</x-admin.layouts.guest>

