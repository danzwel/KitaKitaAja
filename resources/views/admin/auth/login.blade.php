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

        <div>
            <label for="password" class="mb-1.5 block text-sm font-semibold text-[#0C2340]">Password</label>
            <input id="password" type="password" name="password" required
                   class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-[#0C2340] transition focus:border-[#1E5AA8] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E5AA8]/20"
                   placeholder="••••••••">
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
