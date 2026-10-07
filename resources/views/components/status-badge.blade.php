@props(['status'])
@php
    $value = strtolower((string) ($status ?? ''));
    $colors = match ($value) {
        'hadir', 'tepat_waktu', 'approved', 'disetujui', 'aktif' => ['background-color' => '#D1FAE5', 'color' => '#047857'],
        'terlambat' => ['background-color' => '#FFEDD5', 'color' => '#C2410C'],
        'izin' => ['background-color' => '#DBEAFE', 'color' => '#1D4ED8'],
        'sakit' => ['background-color' => '#FEF3C7', 'color' => '#B45309'],
        'alpa', 'ditolak', 'rejected' => ['background-color' => '#FFE4E6', 'color' => '#BE123C'],
        'menunggu_verifikasi', 'pending', 'diproses', 'menunggu' => ['background-color' => '#FEF3C7', 'color' => '#B45309'],
        'selesai' => ['background-color' => '#F1F5F9', 'color' => '#475569'],
        default => ['background-color' => '#F3F4F6', 'color' => '#4B5563'],
    };
    $label = match ($value) {
        'tepat_waktu' => 'Tepat Waktu',
        'menunggu_verifikasi' => 'Menunggu Verifikasi',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak',
        default => ucfirst(str_replace('_', ' ', (string) $status)),
    };
@endphp
<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold', 'style' => 'background-color: '.$colors['background-color'].'; color: '.$colors['color'].';']) }}>{{ $label }}</span>
