<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ganti Password — Portal Mahasiswa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    @include('components.vite-assets')
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-heading { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-[#0B1F3D] via-[#12305C] to-[#1a4480] antialiased">

    <div class="flex min-h-screen items-center justify-center px-4 py-12">
        <div class="w-full max-w-[420px]">
            {{-- Logo --}}
            <div class="mb-8 text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-white/10 backdrop-blur-sm ring-1 ring-white/20">
                    <svg class="h-8 w-8 text-gold" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </div>
                <h1 class="font-heading text-2xl font-bold text-white">Ganti Password</h1>
                <p class="mt-1 text-sm text-blue-200/70">Demi keamanan, silakan buat password baru untuk akun Anda.</p>
            </div>

            {{-- Form Card --}}
            <div class="rounded-2xl bg-white p-8 shadow-2xl shadow-black/20">
                {{-- Info Banner --}}
                <div class="mb-6 flex items-start gap-3 rounded-xl bg-amber-50 p-4 ring-1 ring-amber-200/60">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 4h.01M12 3 2 21h20L12 3Z"/></svg>
                    <p class="text-xs leading-relaxed text-amber-800">Ini adalah login pertama Anda. Password sementara harus diganti sebelum melanjutkan.</p>
                </div>

                <form method="POST" action="{{ route('intern.password.change') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label for="password" class="mb-1.5 block text-sm font-medium text-gray-700">Password Baru</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            </div>
                            <input id="password" type="password" name="password" required autofocus placeholder="Minimal 8 karakter"
                                class="block w-full rounded-xl border border-gray-200 bg-gray-50/50 py-3 pl-11 pr-4 text-sm text-gray-900 placeholder-gray-400 transition focus:border-navy focus:bg-white focus:ring-2 focus:ring-navy/20 focus:outline-none">
                        </div>
                        @error('password')<p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
                            </div>
                            <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="Ulangi password baru"
                                class="block w-full rounded-xl border border-gray-200 bg-gray-50/50 py-3 pl-11 pr-4 text-sm text-gray-900 placeholder-gray-400 transition focus:border-navy focus:bg-white focus:ring-2 focus:ring-navy/20 focus:outline-none">
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full rounded-xl bg-navy py-3 text-sm font-semibold text-white shadow-lg shadow-navy/30 transition-all duration-200 hover:shadow-xl hover:shadow-navy/40 hover:brightness-110 active:scale-[0.98]">
                        Simpan & Lanjutkan
                    </button>
                </form>
            </div>

            <p class="mt-6 text-center text-xs text-blue-200/50">
                © {{ date('Y') }} UPTD Pelatihan Kesehatan Dinas Kesehatan Provinsi Jawa Barat
            </p>
        </div>
    </div>
</body>
</html>
