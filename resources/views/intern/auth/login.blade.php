<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Mahasiswa — SIM Magang</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-heading { font-family: 'Plus Jakarta Sans', sans-serif; }
        .login-bg {
            background-image:
                radial-gradient(ellipse at 20% 50%, rgba(184,145,47,0.08) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(30,80,160,0.12) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 100%, rgba(11,31,61,0.15) 0%, transparent 50%);
        }
        .float-animation { animation: float 6s ease-in-out infinite; }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
        }
    </style>
</head>
<body class="min-h-screen antialiased">

    <div class="flex min-h-screen">
        {{-- LEFT: Decorative Panel --}}
        <div class="hidden lg:flex lg:w-[55%] relative overflow-hidden bg-gradient-to-br from-[#0B1F3D] via-[#12305C] to-[#0d2b52]">
            {{-- Animated shapes --}}
            <div class="absolute inset-0">
                <div class="absolute top-[15%] left-[10%] h-64 w-64 rounded-full bg-gold/5 blur-3xl float-animation"></div>
                <div class="absolute bottom-[20%] right-[15%] h-80 w-80 rounded-full bg-blue-400/5 blur-3xl float-animation" style="animation-delay: -3s;"></div>
                <div class="absolute top-[60%] left-[40%] h-48 w-48 rounded-full bg-white/[0.02] blur-2xl float-animation" style="animation-delay: -1.5s;"></div>
            </div>

            {{-- Grid Pattern --}}
            <div class="absolute inset-0 opacity-[0.03]" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

            {{-- Content --}}
            <div class="relative z-10 flex flex-col justify-between p-12">
                {{-- Logo --}}
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/10 backdrop-blur-sm ring-1 ring-white/10">
                        <svg class="h-6 w-6 text-gold" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3 5 6v5c0 4.5 2.9 8.4 7 10 4.1-1.6 7-5.5 7-10V6l-7-3Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-heading text-sm font-bold text-white">SIM Magang</p>
                        <p class="text-[11px] text-blue-200/50">Sistem Informasi Manajemen Magang</p>
                    </div>
                </div>

                {{-- Center Text --}}
                <div class="max-w-md">
                    <h1 class="font-heading text-4xl font-extrabold leading-tight text-white">
                        Portal<br>
                        <span class="bg-gradient-to-r from-gold to-gold-light bg-clip-text text-transparent">Mahasiswa Magang</span>
                    </h1>
                    <p class="mt-4 text-base leading-relaxed text-blue-200/60">
                        Akses dashboard Anda untuk memantau status magang, mengelola absensi, dan memperbarui profil.
                    </p>
                    {{-- Feature Pills --}}
                    <div class="mt-8 flex flex-wrap gap-3">
                        <span class="inline-flex items-center gap-2 rounded-full bg-white/[0.06] px-4 py-2 text-xs font-medium text-blue-200/70 ring-1 ring-white/10">
                            <svg class="h-3.5 w-3.5 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
                            Dashboard Interaktif
                        </span>
                        <span class="inline-flex items-center gap-2 rounded-full bg-white/[0.06] px-4 py-2 text-xs font-medium text-blue-200/70 ring-1 ring-white/10">
                            <svg class="h-3.5 w-3.5 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"/><circle cx="9.5" cy="7" r="4"/></svg>
                            Profil Lengkap
                        </span>
                        <span class="inline-flex items-center gap-2 rounded-full bg-white/[0.06] px-4 py-2 text-xs font-medium text-blue-200/70 ring-1 ring-white/10">
                            <svg class="h-3.5 w-3.5 text-gold" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            Keamanan Terjamin
                        </span>
                    </div>
                </div>

                {{-- Footer --}}
                <div>
                    <p class="text-xs text-blue-200/30">UPTD Pelatihan Kesehatan</p>
                    <p class="text-xs text-blue-200/30">Dinas Kesehatan Provinsi Jawa Barat</p>
                </div>
            </div>
        </div>

        {{-- RIGHT: Login Form --}}
        <div class="flex flex-1 items-center justify-center bg-gray-50 px-6 py-12 login-bg lg:px-12">
            <div class="w-full max-w-[400px]">
                {{-- Mobile Logo --}}
                <div class="mb-8 text-center lg:hidden">
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-navy">
                        <svg class="h-7 w-7 text-gold" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3 5 6v5c0 4.5 2.9 8.4 7 10 4.1-1.6 7-5.5 7-10V6l-7-3Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4" />
                        </svg>
                    </div>
                    <h1 class="font-heading text-xl font-bold text-navy">Portal Mahasiswa</h1>
                    <p class="mt-1 text-xs text-gray-400">UPTD Pelatihan Kesehatan Jabar</p>
                </div>

                {{-- Form Header --}}
                <div class="mb-8 hidden lg:block">
                    <h2 class="font-heading text-2xl font-bold text-gray-900">Selamat Datang! 👋</h2>
                    <p class="mt-2 text-sm text-gray-500">Masuk dengan kredensial yang diberikan Admin setelah pengajuan Anda diterima.</p>
                </div>
                <div class="mb-6 lg:hidden">
                    <h2 class="text-lg font-bold text-gray-900">Masuk ke Akun Anda</h2>
                    <p class="mt-1 text-sm text-gray-500">Gunakan kredensial dari Admin.</p>
                </div>

                <form method="POST" action="{{ route('intern.login') }}" class="space-y-5">
                    @csrf
                    {{-- Username --}}
                    <div>
                        <label for="username" class="mb-1.5 block text-sm font-semibold text-gray-700">NIM / Username</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                <svg class="h-[18px] w-[18px] text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                            <input id="username" name="username" value="{{ old('username') }}" required autofocus placeholder="Contoh: 2211102441"
                                class="block w-full rounded-xl border border-gray-200 bg-white py-3 pl-11 pr-4 text-sm text-gray-900 shadow-sm placeholder-gray-400 transition focus:border-navy focus:shadow-md focus:ring-2 focus:ring-navy/10 focus:outline-none">
                        </div>
                        @error('username')<p class="mt-1.5 flex items-center gap-1 text-xs font-medium text-red-500"><svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 8v4m0 4h.01"/></svg>{{ $message }}</p>@enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="mb-1.5 block text-sm font-semibold text-gray-700">Password</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                <svg class="h-[18px] w-[18px] text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            </div>
                            <input id="password" type="password" name="password" required placeholder="••••••••"
                                class="block w-full rounded-xl border border-gray-200 bg-white py-3 pl-11 pr-12 text-sm text-gray-900 shadow-sm placeholder-gray-400 transition focus:border-navy focus:shadow-md focus:ring-2 focus:ring-navy/10 focus:outline-none">
                            <button type="button" data-password-toggle="password"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 transition hover:text-navy focus:outline-none focus:ring-2 focus:ring-inset focus:ring-navy/30"
                                aria-label="Tampilkan password">
                                <svg data-password-icon="eye" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z" />
                                    <circle cx="12" cy="12" r="2.5" />
                                </svg>
                                <svg data-password-icon="eye-off" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m3 3 18 18M10.6 10.6a2 2 0 0 0 2.8 2.8M9.9 5.2A10.5 10.5 0 0 1 12 5c6 0 9.5 7 9.5 7a17 17 0 0 1-3.1 3.9M6.2 6.3A10.5 10.5 0 0 0 2.5 12s3.5 7 9.5 7c1.5 0 2.8-.4 4-.9" />
                                </svg>
                            </button>
                        </div>
                        @error('password')<p class="mt-1.5 flex items-center gap-1 text-xs font-medium text-red-500"><svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 8v4m0 4h.01"/></svg>{{ $message }}</p>@enderror
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                        class="group relative w-full overflow-hidden rounded-xl bg-gradient-to-r from-navy to-[#12305C] py-3.5 text-sm font-semibold text-white shadow-lg shadow-navy/25 transition-all duration-300 hover:shadow-xl hover:shadow-navy/35 active:scale-[0.98]">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            Masuk ke Portal
                            <svg class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-7-7 7 7-7 7"/></svg>
                        </span>
                    </button>
                </form>

                {{-- Bottom --}}
                <p class="mt-8 text-center text-xs text-gray-400">
                    © {{ date('Y') }} UPTD Pelatihan Kesehatan Dinas Kesehatan Provinsi Jawa Barat
                </p>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('[data-password-toggle]').forEach((button) => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.passwordToggle);
                const isVisible = input.type === 'text';

                input.type = isVisible ? 'password' : 'text';
                button.setAttribute('aria-label', isVisible ? 'Tampilkan password' : 'Sembunyikan password');
                button.querySelector('[data-password-icon="eye"]').classList.toggle('hidden', !isVisible);
                button.querySelector('[data-password-icon="eye-off"]').classList.toggle('hidden', isVisible);
            });
        });
    </script>
</body>
</html>
