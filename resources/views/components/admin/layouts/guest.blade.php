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
<body class="flex min-h-screen items-center justify-center bg-[#F6F7F4] px-4 antialiased">

    <div class="w-full max-w-sm">
        <div class="mb-8 flex flex-col items-center text-center">
            <div class="mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#0F6E56] text-white">
                <i class="ti ti-shield-check text-2xl"></i>
            </div>
            <h1 class="font-heading text-lg font-semibold text-[#1E2A24]">SIM Magang</h1>
            <p class="text-sm text-[#64705F]">UPTD Pelatihan Kesehatan — Dinkes Provinsi Jawa Barat</p>
        </div>

        <div class="rounded-2xl border border-[#E3E5DE] bg-white p-6 shadow-sm">
            {{ $slot }}
        </div>

        <p class="mt-6 text-center text-xs text-[#8B958A]">Portal ini hanya untuk Administrator sistem.</p>
    </div>

</body>
</html>
