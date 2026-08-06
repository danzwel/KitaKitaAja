@if (session('success'))
    <div class="mb-6 flex items-start gap-3 rounded-xl border border-[#D4DFEA] bg-[#E8EEF5] px-4 py-3 text-sm text-[#081A30]" x-data="{ show: true }" x-show="show">
        <i class="ti ti-check mt-0.5"></i>
        <span class="flex-1">{{ session('success') }}</span>
        <button type="button" @click="show = false" aria-label="Tutup" class="text-[#081A30]/70 hover:text-[#081A30]">
            <i class="ti ti-x"></i>
        </button>
    </div>
@endif

@if (session('error'))
    <div class="mb-6 flex items-start gap-3 rounded-xl border border-[#F0C9C9] bg-[#FBEAEA] px-4 py-3 text-sm text-[#9B3A3A]" x-data="{ show: true }" x-show="show">
        <i class="ti ti-alert-circle mt-0.5"></i>
        <span class="flex-1">{{ session('error') }}</span>
        <button type="button" @click="show = false" aria-label="Tutup" class="text-[#9B3A3A]/70 hover:text-[#9B3A3A]">
            <i class="ti ti-x"></i>
        </button>
    </div>
@endif

@if ($errors->any())
    <div class="mb-6 rounded-xl border border-[#F0C9C9] bg-[#FBEAEA] px-4 py-3 text-sm text-[#9B3A3A]">
        <p class="mb-1 font-medium">Terjadi kesalahan pada input:</p>
        <ul class="list-inside list-disc space-y-0.5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

