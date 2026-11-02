<x-admin.layouts.app title="Detail Peserta">

    <a href="{{ route('admin.interns.index') }}" class="mb-4 inline-flex items-center gap-1.5 text-sm font-medium text-[#4B564B] hover:text-[#1E2A24]">
        <i class="ti ti-arrow-left"></i> Kembali ke daftar peserta magang
    </a>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <x-admin.card title="Data Peserta">
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    @foreach ([
                        'Nama' => $intern->name,
                        'Universitas' => $intern->university,
                        'Bidang Magang' => $intern->internshipApplication?->bidang?->nama_bidang ?? $intern->department?->name ?? '-',
                        'Periode' => $intern->period,
                        'Username' => $intern->username,
                    ] as $label => $value)
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-[#8B958A]">{{ $label }}</dt>
                            <dd class="mt-1 text-sm font-medium text-[#1E2A24]">{{ $value ?: '-' }}</dd>
                        </div>
                    @endforeach
                </dl>
            </x-admin.card>

            <x-admin.card title="Surat Balasan" subtitle="Dapat diunduh peserta melalui dashboard mereka">
                <div class="mb-4 space-y-2">
                    @forelse ($intern->replyLetters as $letter)
                        <div class="flex items-center justify-between rounded-lg border border-[#E3E5DE] px-4 py-3">
                            <div class="flex items-center gap-2 text-sm text-[#1E2A24]">
                                <i class="ti ti-file-type-pdf text-[#64705F]"></i>
                                Surat Balasan — {{ $letter->created_at->translatedFormat('d M Y') }}
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="{{ Storage::url($letter->file_path) }}" target="_blank" class="text-xs font-medium text-[#0C2340] hover:underline">Lihat</a>
                                <form method="POST" action="{{ route('admin.reply-letters.destroy', $letter) }}" onsubmit="return confirm('Hapus surat balasan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-medium text-[#9B3A3A] hover:underline">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-[#8B958A]">Belum ada surat balasan diunggah.</p>
                    @endforelse
                </div>

                @if ($intern->status === \App\Models\Intern::STATUS_AKTIF)
                    <form method="POST" action="{{ route('admin.reply-letters.store', $intern) }}" enctype="multipart/form-data" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        @csrf
                        <input type="file" name="file" accept="application/pdf" required
                               class="min-w-0 flex-1 rounded-lg border border-[#E3E5DE] px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-[#E8EEF5] file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-[#081A30]">
                        <button type="submit" class="whitespace-nowrap rounded-lg bg-[#0C2340] px-4 py-2 text-sm font-medium text-white hover:bg-[#081A30]">
                            <i class="ti ti-upload"></i> Unggah
                        </button>
                    </form>
                @else
                    <p class="rounded-lg bg-[#F6F7F4] px-3 py-2 text-sm text-[#64705F]">Upload surat balasan tidak tersedia karena status peserta sudah selesai.</p>
                @endif
            </x-admin.card>

            <x-admin.card title="Sertifikat Magang" subtitle="Dibuat otomatis dari data peserta, bidang, dan periode magang.">
                @if ($intern->certificate)
                    <div class="flex flex-col gap-3 rounded-xl border border-emerald-100 bg-emerald-50 p-4 sm:flex-row sm:items-center sm:justify-between"><div><p class="text-sm font-semibold text-emerald-900">Sertifikat sudah dikirim</p><p class="mt-1 text-xs text-emerald-700">No. {{ $intern->certificate->certificate_number }} · {{ $intern->certificate->issued_at?->translatedFormat('d M Y, H:i') }} WIB</p></div><a href="{{ route('admin.certificates.download', $intern->certificate) }}" class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-emerald-200 bg-white px-4 py-2 text-sm font-semibold text-emerald-800 hover:bg-emerald-100"><i class="ti ti-download"></i> Unduh PDF</a></div>
                @elseif ($intern->status === \App\Models\Intern::STATUS_SELESAI)
                    <p class="mb-4 text-sm leading-relaxed text-[#64705F]">Sertifikat akan memakai nama peserta, bidang penempatan, serta periode magang yang tersimpan. Bagian tanda tangan dikosongkan untuk ditandatangani setelah dicetak.</p><form method="POST" action="{{ route('admin.certificates.issue', $intern) }}" onsubmit="return confirm('Kirim sertifikat magang kepada peserta ini?');">@csrf<button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-[#0C2340] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#081A30]"><i class="ti ti-certificate"></i> Kirim Sertifikat</button></form>
                @else
                    <p class="rounded-lg bg-[#F6F7F4] px-3 py-2 text-sm text-[#64705F]">Sertifikat dapat dikirim setelah status magang peserta selesai.</p>
                @endif
            </x-admin.card>

        </div>

        <div class="space-y-6">
            <x-admin.card title="Status">
                <x-admin.badge :status="$intern->status" class="!text-sm" />
            </x-admin.card>

            <x-admin.card title="Akun Peserta">
                <p class="mb-4 text-sm text-[#4B564B]">Reset password akan membuat password baru secara acak untuk akun ini.</p>
                <form method="POST" action="{{ route('admin.interns.reset-password', $intern) }}" onsubmit="return confirm('Reset password peserta ini?');">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-lg border border-[#E3E5DE] px-4 py-2.5 text-sm font-medium text-[#1E2A24] hover:bg-[#F6F7F4]">
                        <i class="ti ti-key"></i> Reset Password
                    </button>
                </form>
            </x-admin.card>
        </div>
    </div>
</x-admin.layouts.app>

