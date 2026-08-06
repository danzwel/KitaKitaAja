<x-admin.layouts.app title="Detail Pengajuan">

    <a href="{{ route('admin.applications.index') }}" class="mb-4 inline-flex items-center gap-1.5 text-sm font-medium text-[#4B564B] hover:text-[#1E2A24]">
        <i class="ti ti-arrow-left"></i> Kembali ke daftar pengajuan
    </a>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <x-admin.card title="Identitas Mahasiswa">
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    @foreach ([
                        'Kode Pengajuan' => $application->application_code,
                        'Nama' => $application->nama,
                        'NIM' => $application->nim,
                        'Universitas' => $application->universitas,
                        'Fakultas' => $application->fakultas,
                        'Program Studi' => $application->program_studi,
                        'Semester' => $application->semester,
                        'Bidang Magang' => $application->bidang->nama_bidang ?? '-',
                        'Periode' => $application->periode_mulai?->translatedFormat('d F Y').' – '.$application->periode_selesai?->translatedFormat('d F Y'),
                        'Email' => $application->email,
                        'No. HP' => $application->no_hp,
                        'Tanggal Pengajuan' => $application->created_at?->translatedFormat('d F Y'),
                    ] as $label => $value)
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-[#8B958A]">{{ $label }}</dt>
                            <dd class="mt-1 text-sm font-medium text-[#1E2A24]">{{ $value ?: '-' }}</dd>
                        </div>
                    @endforeach
                </dl>
            </x-admin.card>

            <x-admin.card title="Dokumen">
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ([
                        'Surat Pengantar' => $application->document?->surat_pengantar,
                        'Foto' => $application->document?->foto,
                        'CV' => $application->document?->cv,
                        'Proposal' => $application->document?->proposal,
                        'Portofolio' => $application->document?->portofolio,
                    ] as $label => $path)
                        <div class="flex items-center justify-between rounded-lg border border-[#E3E5DE] px-4 py-3">
                            <div class="flex items-center gap-2 text-sm text-[#1E2A24]">
                                <i class="ti ti-file-text text-[#64705F]"></i> {{ $label }}
                            </div>
                            @if ($path)
                                <a href="{{ Storage::url($path) }}" target="_blank" class="text-xs font-medium text-[#0C2340] hover:underline">Lihat</a>
                            @else
                                <span class="text-xs text-[#8B958A]">Tidak ada</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </x-admin.card>

            @if ($application->tujuan_magang || $application->catatan_admin || $application->alamat)
                <x-admin.card title="Catatan">
                    @if ($application->tujuan_magang)
                        <p class="text-sm text-[#4B564B]"><span class="font-medium">Tujuan magang:</span> {{ $application->tujuan_magang }}</p>
                    @endif
                    @if ($application->catatan_admin)
                        <p class="mt-2 text-sm text-[#4B564B]"><span class="font-medium">Catatan Admin:</span> {{ $application->catatan_admin }}</p>
                    @endif
                    @if ($application->alamat)
                        <p class="mt-2 text-sm text-[#4B564B]"><span class="font-medium">Alamat:</span> {{ $application->alamat }}</p>
                    @endif
                </x-admin.card>
            @endif
        </div>

        <div>
            <x-admin.card title="Status">
                <div class="mb-4"><x-admin.badge :status="$application->status === 'menunggu_verifikasi' ? 'menunggu' : $application->status" class="!text-sm" /></div>

                @if (in_array($application->status, ['menunggu_verifikasi', 'diproses'], true))
                    <div class="space-y-2" x-data="{ approveOpen: @json($errors->has('approval_note')), rejectOpen: false }">
                        <button @click="approveOpen = true" type="button"
                                class="flex w-full items-center justify-center gap-2 rounded-lg bg-[#0C2340] px-4 py-2.5 text-sm font-medium text-white hover:bg-[#081A30]">
                            <i class="ti ti-check"></i> Terima Pengajuan
                        </button>

                        <div x-show="approveOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
                            <div @click.outside="approveOpen = false" class="w-full max-w-md rounded-2xl bg-white p-5">
                                <h3 class="font-heading mb-3 text-base font-semibold text-[#1E2A24]">Terima Pengajuan</h3>
                                <form method="POST" action="{{ route('admin.applications.approve', $application) }}">
                                    @csrf
                                    @method('PATCH')
                                    <label class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Catatan penerimaan</label>
                                    <textarea name="approval_note" rows="4" required minlength="10"
                                              class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]"
                                              placeholder="Tuliskan catatan penerimaan...">{{ old('approval_note') }}</textarea>
                                    @error('approval_note')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                    <div class="mt-4 flex justify-end gap-2">
                                        <button type="button" @click="approveOpen = false" class="rounded-lg border border-[#E3E5DE] px-4 py-2 text-sm font-medium text-[#4B564B]">Batal</button>
                                        <button type="submit" class="rounded-lg bg-[#0C2340] px-4 py-2 text-sm font-medium text-white hover:bg-[#081A30]">Terima</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <button @click="rejectOpen = true" type="button"
                                class="flex w-full items-center justify-center gap-2 rounded-lg border border-[#F0C9C9] px-4 py-2.5 text-sm font-medium text-[#9B3A3A] hover:bg-[#FBEAEA]">
                            <i class="ti ti-x"></i> Tolak Pengajuan
                        </button>

                        <div x-show="rejectOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
                            <div @click.outside="rejectOpen = false" class="w-full max-w-md rounded-2xl bg-white p-5">
                                <h3 class="font-heading mb-3 text-base font-semibold text-[#1E2A24]">Tolak Pengajuan</h3>
                                <form method="POST" action="{{ route('admin.applications.reject', $application) }}">
                                    @csrf
                                    @method('PATCH')
                                    <label class="mb-1.5 block text-sm font-medium text-[#1E2A24]">Alasan penolakan</label>
                                    <textarea name="rejection_reason" rows="4" required
                                              class="w-full rounded-lg border border-[#E3E5DE] px-3 py-2.5 text-sm focus:border-[#0C2340] focus:outline-none focus:ring-1 focus:ring-[#0C2340]"
                                              placeholder="Jelaskan alasan penolakan..."></textarea>
                                    <div class="mt-4 flex justify-end gap-2">
                                        <button type="button" @click="rejectOpen = false" class="rounded-lg border border-[#E3E5DE] px-4 py-2 text-sm font-medium text-[#4B564B]">Batal</button>
                                        <button type="submit" class="rounded-lg bg-[#9B3A3A] px-4 py-2 text-sm font-medium text-white hover:bg-[#7E2E2E]">Tolak</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-[#64705F]">Pengajuan ini sudah diproses.</p>
                @endif
            </x-admin.card>
        </div>
    </div>
</x-admin.layouts.app>

