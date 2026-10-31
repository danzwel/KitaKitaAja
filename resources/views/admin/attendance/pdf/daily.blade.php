<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 28px 30px; } body { font-family: DejaVu Sans, sans-serif; color: #172033; font-size: 10px; }
        h1 { margin: 0 0 4px; color: #0c2340; font-size: 21px; } .meta { color: #64748b; margin-bottom: 18px; }
        table { width: 100%; border-collapse: collapse; } th { background: #0c2340; color: #fff; text-align: left; font-size: 8px; text-transform: uppercase; letter-spacing: .4px; padding: 9px 7px; }
        td { border-bottom: 1px solid #e2e8f0; padding: 8px 7px; vertical-align: top; } tr:nth-child(even) td { background: #f8fafc; }
        .name { font-weight: bold; color: #0c2340; } .muted { color: #64748b; font-size: 8px; } .center { text-align: center; }
    </style>
</head>
<body>
    <h1>Absensi Peserta Magang</h1>
    <div class="meta">{{ $attendanceDate->translatedFormat('l, d F Y') }} &middot; Dicetak {{ now()->format('d M Y H:i') }} WIB</div>
    <table><thead><tr><th>Peserta</th><th>Datang</th><th>Pulang</th><th>Status Datang</th><th>Status Pulang</th><th>Lokasi Datang</th></tr></thead><tbody>
        @forelse ($todayRecords as $record)
            <tr><td><div class="name">{{ $record->intern->name }}</div><div class="muted">{{ $record->intern->username }}</div></td><td class="center">{{ $record->check_in_at?->format('H:i') ?? '-' }}</td><td class="center">{{ $record->check_out_at?->format('H:i') ?? '-' }}</td><td>{{ ucfirst(str_replace('_', ' ', $record->check_in_status ?? '-')) }}</td><td>{{ ucfirst(str_replace('_', ' ', $record->check_out_status ?? '-')) }}</td><td>{{ $record->check_in_distance_meters !== null ? $record->check_in_distance_meters.' m' : 'Belum ada' }}</td></tr>
        @empty
            <tr><td colspan="6" class="center">Belum ada peserta yang scan pada tanggal ini.</td></tr>
        @endforelse
    </tbody></table>
</body>
</html>
