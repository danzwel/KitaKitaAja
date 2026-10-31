<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 42px; }
        body { font-family: DejaVu Sans, sans-serif; color: #172033; font-size: 11px; }
        h1 { margin: 0 0 4px; color: #0C2340; font-size: 22px; }
        .muted { color: #64748b; }
        .card { margin-top: 22px; border: 1px solid #dbe1ea; border-radius: 8px; padding: 18px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 9px 0; border-bottom: 1px solid #e8edf3; }
        td:first-child { width: 42%; color: #64748b; }
        .status { margin-top: 18px; padding: 10px; text-align: center; background: #e8f5ef; color: #087c4c; font-weight: bold; border-radius: 7px; text-transform: capitalize; }
    </style>
</head>
<body>
    <h1>Status Pengajuan Magang</h1>
    <p class="muted">UPTD Pelatihan Kesehatan Dinas Kesehatan Provinsi Jawa Barat</p>
    <div class="card">
        <table>
            <tr><td>Nomor Pengajuan</td><td><strong>{{ $application->application_code }}</strong></td></tr>
            <tr><td>Nama</td><td>{{ $application->nama }}</td></tr>
            <tr><td>Institusi Pendidikan</td><td>{{ $application->universitas }}</td></tr>
            <tr><td>Periode Magang</td><td>{{ $application->periode_mulai->format('d M Y') }} – {{ $application->periode_selesai->format('d M Y') }}</td></tr>
            @if ($application->bidang)
                <tr><td>Bidang Penempatan</td><td>{{ $application->bidang->nama_bidang }}</td></tr>
            @endif
        </table>
        <div class="status">{{ str_replace('_', ' ', $application->status) }}</div>
        @if ($application->catatan_admin)
            <p><strong>Catatan Admin:</strong> {{ $application->catatan_admin }}</p>
        @endif
    </div>
</body>
</html>
