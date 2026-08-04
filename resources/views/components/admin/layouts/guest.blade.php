<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin — SIM Magang UPTD Pelatihan Kesehatan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/2.47.0/iconfont/tabler-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-heading { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen bg-[#F3F6FA] antialiased">
    <main class="mx-auto flex min-h-screen w-full max-w-[1280px] items-center p-0 sm:p-6 lg:p-8">
        <div class="grid min-h-[680px] w-full overflow-hidden bg-white shadow-2xl shadow-[#0C2340]/10 sm:rounded-[2rem] lg:grid-cols-[0.9fr_1.1fr]">
            <section class="flex flex-col justify-center px-6 py-10 sm:px-12 lg:px-16">
                <div class="mb-10 flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#0C2340] text-white shadow-lg shadow-[#0C2340]/20">
                        <i class="ti ti-shield-check text-2xl"></i>
                    </div>
                    <div>
                        <p class="font-heading text-base font-bold text-[#0C2340]">SIM Magang</p>
                        <p class="text-xs text-slate-500">Portal Administrator</p>
                    </div>
                </div>

                <div class="mb-8">
                    <p class="mb-3 text-sm font-semibold uppercase tracking-[0.18em] text-[#0C2340]/70">Selamat datang kembali</p>
                    <h1 class="font-heading text-4xl font-bold leading-tight text-[#0C2340] sm:text-5xl">Masuk ke<br><span class="text-[#1E5AA8]">Dashboard Admin</span></h1>
                    <p class="mt-4 max-w-md text-sm leading-6 text-slate-500">Kelola sistem informasi magang UPTD Pelatihan Kesehatan Jawa Barat dengan aman dan mudah.</p>
                </div>

                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>

                <p class="mt-8 text-xs text-slate-400">Portal ini hanya untuk Administrator sistem.</p>
            </section>

            <section class="relative hidden min-h-[680px] overflow-hidden bg-[#0C2340] lg:block">
                <img src="{{ asset('images/admin-login.jpg') }}" alt="Ilustrasi keamanan login admin" class="absolute inset-0 h-full w-full object-cover opacity-90">
                <div class="absolute inset-0 bg-gradient-to-t from-[#07182E]/90 via-[#0C2340]/10 to-transparent"></div>
                <div class="absolute bottom-10 left-10 right-10 rounded-2xl border border-white/20 bg-[#0C2340]/55 p-5 text-white backdrop-blur-md">
                    <p class="text-lg font-bold">Sistem Informasi Magang</p>
                    <p class="mt-1 text-sm leading-6 text-blue-100/80">UPTD Pelatihan Kesehatan — Dinas Kesehatan Provinsi Jawa Barat</p>
                </div>
            </section>
        </div>
    </main>

</body>
</html>
