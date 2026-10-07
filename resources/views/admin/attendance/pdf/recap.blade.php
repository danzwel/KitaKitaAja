<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 28px 30px; }
        body { font-family: DejaVu Sans, sans-serif; color: #172033; font-size: 10px; }
        h1 { margin: 0 0 4px; color: #0c2340; font-size: 21px; }
        .meta { color: #64748b; margin-bottom: 18px; }
        .date { color: #64748b; font-size: 9px; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #0c2340; color: #fff; text-align: left; font-size: 8px; text-transform: uppercase; letter-spacing: .4px; padding: 9px 7px; }
        td { border-bottom: 1px solid #e2e8f0; padding: 8px 7px; vertical-align: top; }
        tr:nth-child(even) td { background: #f8fafc; }
        .name { font-weight: bold; color: #0c2340; }
        .muted { color: #64748b; font-size: 8px; }
        .center { text-align: center; }
        .pill { display: inline-block; min-width: 18px; padding: 4px 7px; border-radius: 12px; text-align: center; font-weight: bold; }
        .green { background: #d1fae5; color: #047857; } .blue { background: #dbeafe; color: #1d4ed8; }
        .amber { background: #fef3c7; color: #b45309; } .rose { background: #ffe4e6; color: #be123c; }
        .footer { margin-top: 18px; color: #94a3b8; font-size: 8px; text-align: right; }
    </style>
</head>
<body>
    <h1>Rekap Absensi Mahasiswa</h1>
    <div class="meta">Ringkasan kehadiran per orang &middot; Dicetak {{ now()->format('d M Y H:i') }}</div>
    <table>
        <thead><tr><th>Mahasiswa</th><th>Periode Magang</th><th class="center">Hari Kerja</th><th class="center">Hadir</th><th class="center">Terlambat</th><th class="center">Izin</th><th class="center">Sakit</th><th class="center">Alpa</th><th class="center">Verifikasi</th></tr></thead>
        <tbody>
        @forelse ($summaryRows as $row)
            <tr>
                <td><div class="name">{{ $row['intern']->name }}</div><div class="muted">{{ $row['intern']->username }} &middot; {{ $row['intern']->university }}</div></td>
                <td>{{ \Carbon\Carbon::parse($row['period_start'])->format('d M Y') }}<br>s/d {{ \Carbon\Carbon::parse($row['period_end'])->format('d M Y') }}</td>
                <td class="center">{{ $row['working_days'] }}</td>
                <td class="center"><span class="pill green">{{ $row['present'] }}</span></td>
                <td class="center">{{ $row['late'] }}</td>
                <td class="center"><span class="pill blue">{{ $row['izin'] }}</span></td>
                <td class="center"><span class="pill amber">{{ $row['sakit'] }}</span></td>
                <td class="center"><span class="pill rose">{{ $row['alpha'] }}</span></td>
                <td class="center">{{ $row['pending'] }}</td>
            </tr>
        @empty
            <tr><td colspan="9" class="center">Belum ada peserta aktif.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div class="footer">Senin-Jumat dihitung sebagai hari kerja.</div>
</body>
</html>
