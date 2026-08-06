@props(['title' => null, 'subtitle' => null])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-[#E3E5DE] bg-white p-5 shadow-sm']) }}>
    @if ($title)
        <div class="mb-4 flex items-center justify-between">
            <div>
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

