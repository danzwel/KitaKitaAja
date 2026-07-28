<x-admin.layouts.app title="Detail Mahasiswa">

    <a href="{{ route('admin.interns.index') }}" class="mb-4 inline-flex items-center gap-1.5 text-sm font-medium text-[#4B564B] hover:text-[#1E2A24]">
        <i class="ti ti-arrow-left"></i> Kembali ke daftar mahasiswa magang
    </a>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <x-admin.card title="Data Mahasiswa">
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    @foreach ([
                        'Nama' => $intern->name,
                        'Universitas' => $intern->university,
                        'Bidang Magang' => $intern->department->name ?? '-',
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

            <x-admin.card title="Surat Balasan" subtitle="Dapat diunduh mahasiswa melalui dashboard mereka">
                <div class="mb-4 space-y-2">
                    @forelse ($intern->replyLetters as $letter)
                        <div class="flex items-center justify-between rounded-lg border border-[#E3E5DE] px-4 py-3">
                            <div class="flex items-center gap-2 text-sm text-[#1E2A24]">
                                <i class="ti ti-file-type-pdf text-[#64705F]"></i>
                                Surat Balasan — {{ $letter->created_at->translatedFormat('d M Y') }}
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="{{ Storage::url($letter->file_path) }}" target="_blank" class="text-xs font-medium text-[#0F6E56] hover:underline">Lihat</a>
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
                               class="min-w-0 flex-1 rounded-lg border border-[#E3E5DE] px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-[#E7F2ED] file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-[#0B5443]">
                        <button type="submit" class="whitespace-nowrap rounded-lg bg-[#0F6E56] px-4 py-2 text-sm font-medium text-white hover:bg-[#0B5443]">
                            <i class="ti ti-upload"></i> Unggah
                        </button>
                    </form>
                @else
                    <p class="rounded-lg bg-[#F6F7F4] px-3 py-2 text-sm text-[#64705F]">Upload surat balasan tidak tersedia karena status mahasiswa sudah selesai.</p>
                @endif
            </x-admin.card>
        </div>

        <div class="space-y-6">
            <x-admin.card title="Status">
                <x-admin.badge :status="$intern->status" class="!text-sm" />
            </x-admin.card>

            <x-admin.card title="Akun Mahasiswa">
                <p class="mb-4 text-sm text-[#4B564B]">Reset password akan membuat password baru secara acak untuk akun ini.</p>
                <form method="POST" action="{{ route('admin.interns.reset-password', $intern) }}" onsubmit="return confirm('Reset password mahasiswa ini?');">
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
