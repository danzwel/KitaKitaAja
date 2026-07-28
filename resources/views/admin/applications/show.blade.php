<x-admin.layouts.app title="Detail Pengajuan">

    <a href="{{ route('admin.applications.index') }}" class="mb-4 inline-flex items-center gap-1.5 text-sm font-medium text-[#4B564B] hover:text-[#1E2A24]">
        <i class="ti ti-arrow-left"></i> Kembali ke daftar pengajuan
    </a>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <x-admin.card title="Identitas Mahasiswa">
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    @foreach ([
                        'Nama' => $application->name,
                        'NIM' => $application->nim,
                        'Universitas' => $application->university,
                        'Jurusan' => $application->major,
                        'Bidang Magang' => $application->department->name ?? '-',
                        'Periode' => $application->period,
                        'Tanggal Pengajuan' => $application->application_date?->translatedFormat('d F Y'),
                    ] as $label => $value)
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-[#8B958A]">{{ $label }}</dt>
                            <dd class="mt-1 text-sm font-medium text-[#1E2A24]">{{ $value ?: '-' }}</dd>
                        </div>
                    @endforeach
                </dl>
            </x-admin.card>

            <x-admin.card title="Dokumen">
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                    @foreach ([
                        'Surat Pengantar' => $application->cover_letter_path,
                        'CV' => $application->cv_path,
                        'Proposal' => $application->proposal_path,
                    ] as $label => $path)
                        <div class="flex items-center justify-between rounded-lg border border-[#E3E5DE] px-4 py-3">
                            <div class="flex items-center gap-2 text-sm text-[#1E2A24]">
                                <i class="ti ti-file-text text-[#64705F]"></i> {{ $label }}
                            </div>
                            @if ($path)
                                <a href="{{ Storage::url($path) }}" target="_blank" class="text-xs font-medium text-[#0F6E56] hover:underline">Lihat</a>
                            @else
                                <span class="text-xs text-[#8B958A]">Tidak ada</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </x-admin.card>

            @if ($application->notes || $application->rejection_reason)
                <x-admin.card title="Catatan">
                    @if ($application->rejection_reason)
                        <p class="text-sm text-[#9B3A3A]"><span class="font-medium">Alasan penolakan:</span> {{ $application->rejection_reason }}</p>
                    @endif
                    @if ($application->notes)
                        <p class="mt-2 text-sm text-[#4B564B]">{{ $application->notes }}</p>
                    @endif
                </x-admin.card>
            @endif
        </div>

        <div>
            <x-admin.card title="Status">
                <div class="mb-4"><x-admin.badge :status="$application->status" class="!text-sm" /></div>

                @if (in_array($application->status, ['menunggu', 'diproses']))
                    <div class="space-y-2" x-data="{ rejectOpen: false }">
                        <form method="POST" action="{{ route('admin.applications.approve', $application) }}"
                              onsubmit="return confirm('Terima pengajuan ini? Akun mahasiswa akan dibuat otomatis.');">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-lg bg-[#0F6E56] px-4 py-2.5 text-sm font-medium text-white hover:bg-[#0B5443]">
                                <i class="ti ti-check"></i> Terima Pengajuan
                            </button>
                        </form>

                        <button @click="rejectOpen = true" type="button"
                                class="flex w-full items-center justify-center gap-2 rounded-lg border border-[#F0C9C9] px-4 py-2.5 text-sm font-medium text-[#9B3A3A] hover:bg-[#FBEAEA]">
                            <i class="ti ti-x"></i> Tolak Pengajuan
                        </button>

                        {{-- Modal tolak --}}
                        <div x-show="rejectOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
                            <div @click.outside="rejectOpen = false" class="w-full max-w-md rounded-2xl bg-white p-5">
                                <h3 class="font-heading mb-3 text-base font-semibold text-[#1E2A24]">Tolak Pengajuan</h3>
                                <form method="POST" action="{{ route('admin.applications.reject', $application) }}">
                                    @csrf
                                    @method('PATCH')
                                    <label class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Alasan penolakan</label>
                                    <textarea name="rejection_reason" rows="4" required
                                              class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0F6E56] focus:outline-none focus:ring-1 focus:ring-[#0F6E56]"
                                              placeholder="Jelaskan alasan penolakan..."></textarea>
                                    <div class="mt-4 flex justify-end gap-2">
                                        <button type="button" @click="rejectOpen = false" class="rounded-lg border border-[#E3E5DE] px-4 py-2 text-sm font-medium text-[#4B564B]">Batal</button>
                                        <button type="submit" class="rounded-lg bg-[#9B3A3A] px-4 py-2 text-sm font-medium text-white hover:bg-[#7E2E2E]">Tolak</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @elseif ($application->intern)
                    <a href="{{ route('admin.interns.show', $application->intern) }}" class="flex w-full items-center justify-center gap-2 rounded-lg border border-[#E3E5DE] px-4 py-2.5 text-sm font-medium text-[#1E2A24] hover:bg-[#F6F7F4]">
                        <i class="ti ti-user"></i> Lihat Data Mahasiswa
                    </a>
                @endif
            </x-admin.card>
        </div>
    </div>
</x-admin.layouts.app>
