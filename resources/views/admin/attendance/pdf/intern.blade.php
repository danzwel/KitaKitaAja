<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 28px 30px; }
        body { font-family: DejaVu Sans, sans-serif; color: #172033; font-size: 9px; }
        h1 { margin: 0 0 3px; color: #0c2340; font-size: 20px; }
        h2 { margin: 20px 0 8px; color: #0c2340; font-size: 13px; }
        .muted { color: #64748b; } .info { margin: 14px 0 18px; }
        .info td { padding: 3px 25px 3px 0; } .label { color: #64748b; font-size: 8px; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #0c2340; color: #fff; text-align: left; font-size: 8px; padding: 8px 6px; }
        td { border-bottom: 1px solid #e2e8f0; padding: 7px 6px; }
        tr:nth-child(even) td { background: #f8fafc; }
        .center { text-align: center; }
        .pill { display: inline-block; padding: 4px 7px; border-radius: 12px; font-weight: bold; white-space: nowrap; }
        .green { background: #d1fae5; color: #047857; } .orange { background: #ffedd5; color: #c2410c; }
        .blue { background: #dbeafe; color: #1d4ed8; } .amber { background: #fef3c7; color: #b45309; }
        .rose { background: #ffe4e6; color: #be123c; } .gray { background: #f3f4f6; color: #4b5563; }
        .footer { margin-top: 18px; color: #94a3b8; font-size: 8px; text-align: right; }
    </style>
</head>
<body>
    <h1>Rekap Absensi Mahasiswa</h1>
    <div class="muted">{{ $intern->name }} &middot; {{ $intern->username }} &middot; {{ $intern->university }}</div>
    <table class="info"><tr><td><div class="label">Periode Magang</div>{{ $start->format('d M Y') }} s/d {{ $end->format('d M Y') }}</td><td><div class="label">Total Record</div>{{ $records->count() }} hari</td><td><div class="label">Status</div>{{ ucfirst($intern->status) }}</td></tr></table>
    <h2>Riwayat Absensi</h2>
    <table>
        <thead><tr><th>Tanggal</th><th>Datang</th><th>Jarak Datang</th><th>Pulang</th><th>Jarak Pulang</th><th>Status</th></tr></thead>
        <tbody>
        @forelse ($attendanceHistory as $item)
            @php
                $status = strtolower($item['status']);
                $statusClass = match ($status) {
                    'hadir', 'tepat waktu' => 'green',
                    'terlambat' => 'orange',
                    'izin' => 'blue',
                    'sakit' => 'amber',
                    'alpa' => 'rose',
                    default => 'gray',
                };
            @endphp
            <tr><td>{{ $item['date']->format('d M Y') }}</td><td>{{ $item['record']?->check_in_at?->format('H:i') ?? '-' }}</td><td>{{ $item['record']?->check_in_distance_meters !== null ? $item['record']->check_in_distance_meters.' m' : '-' }}</td><td>{{ $item['record']?->check_out_at?->format('H:i') ?? '-' }}</td><td>{{ $item['record']?->check_out_distance_meters !== null ? $item['record']->check_out_distance_meters.' m' : '-' }}</td><td><span class="pill {{ $statusClass }}">{{ $item['status'] }}</span></td></tr>
        @empty
            <tr><td colspan="6">Belum ada riwayat absensi.</td></tr>
        @endforelse
        </tbody>
    </table>
    <h2>Pengajuan Izin / Sakit</h2>
    <table>
        <thead><tr><th>Jenis</th><th>Periode</th><th>Status</th><th>Alasan</th></tr></thead>
        <tbody>
        @forelse ($leaveRequests as $leave)
            @php $leaveClass = $leave->status === 'approved' ? 'green' : ($leave->status === 'rejected' ? 'rose' : 'amber'); @endphp
            <tr><td>{{ ucfirst($leave->type) }}</td><td>{{ $leave->start_date->format('d M Y') }} - {{ $leave->end_date->format('d M Y') }}</td><td><span class="pill {{ $leaveClass }}">{{ $leave->status === 'approved' ? 'Disetujui' : ($leave->status === 'rejected' ? 'Ditolak' : 'Menunggu') }}</span></td><td>{{ $leave->reason }}</td></tr>
        @empty
            <tr><td colspan="4">Belum ada pengajuan izin/sakit.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div class="footer">Dicetak {{ now()->format('d M Y H:i') }}</div>
</body>
</html>
