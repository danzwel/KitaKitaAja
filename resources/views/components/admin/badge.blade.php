@props(['status'])

@php
    $map = [
        'menunggu' => ['bg' => 'bg-[#EFF1EC]', 'text' => 'text-[#5B6660]', 'dot' => 'bg-[#8B958A]', 'label' => 'Menunggu'],
        'diproses' => ['bg' => 'bg-[#FBF3E1]', 'text' => 'text-[#8A661E]', 'dot' => 'bg-[#C99A3B]', 'label' => 'Diproses'],
        'diterima' => ['bg' => 'bg-[#E7F2ED]', 'text' => 'text-[#0B5443]', 'dot' => 'bg-[#0F6E56]', 'label' => 'Diterima'],
        'ditolak' => ['bg' => 'bg-[#FBEAEA]', 'text' => 'text-[#9B3A3A]', 'dot' => 'bg-[#C24545]', 'label' => 'Ditolak'],
        'aktif' => ['bg' => 'bg-[#E7F2ED]', 'text' => 'text-[#0B5443]', 'dot' => 'bg-[#0F6E56]', 'label' => 'Aktif'],
        'selesai' => ['bg' => 'bg-[#EFF1EC]', 'text' => 'text-[#5B6660]', 'dot' => 'bg-[#8B958A]', 'label' => 'Selesai'],
    ];
    $s = $map[$status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'dot' => 'bg-gray-400', 'label' => ucfirst($status)];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium {$s['bg']} {$s['text']}"]) }}>
    <span class="h-1.5 w-1.5 rounded-full {{ $s['dot'] }}"></span>
    {{ $s['label'] }}
</span>
