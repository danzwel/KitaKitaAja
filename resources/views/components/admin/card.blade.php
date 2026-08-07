@props(['title' => null, 'subtitle' => null])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-[#E7EAF1] bg-white p-5 shadow-[0_8px_30px_rgba(28,45,75,0.04)]']) }}>
    @if ($title)
        <div class="mb-4 flex flex-wrap items-start justify-between gap-3">
            <div class="min-w-0">
                <h3 class="font-['Plus_Jakarta_Sans'] text-base font-semibold text-[#1E2A24]">{{ $title }}</h3>
                @if ($subtitle)
                    <p class="mt-0.5 text-sm text-[#64705F]">{{ $subtitle }}</p>
                @endif
            </div>
            @isset($action)
                <div>{{ $action }}</div>
            @endisset
        </div>
    @endif

    {{ $slot }}
</div>

