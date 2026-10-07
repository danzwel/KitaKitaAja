@extends('layouts.app')
@section('title', 'Form Pengajuan Magang')
@section('content')
<div class="relative isolate overflow-hidden bg-gradient-to-b from-[#E8EEF5] via-[#EEF1F5] to-[#EEF1F5]">
    <div class="pointer-events-none absolute -left-24 top-28 h-64 w-64 rounded-full bg-[#0C2340]/[0.06]"></div>
    <div class="pointer-events-none absolute -right-28 top-10 h-80 w-80 rounded-full border-[36px] border-[#B8912F]/[0.08]"></div>
<div class="relative max-w-3xl mx-auto px-6 pt-28 pb-24">

    {{-- HEADER --}}
    <div class="text-center mb-14">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-navy to-navy-light text-gold-light mb-5 shadow-lg shadow-navy/20">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </div>
        <p class="text-xs font-semibold tracking-[0.2em] text-health uppercase mb-2">Formulir</p>
        <h1 class="font-display font-bold text-3xl md:text-4xl text-navy">Pengajuan Magang</h1>
        <div class="w-14 h-1 bg-gradient-to-r from-gold to-health rounded-full mx-auto mt-4 mb-4"></div>
        <p class="text-gray-500 text-sm max-w-sm mx-auto">Lengkapi data di bawah ini dengan benar dan sesuai dokumen resmi.</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg mb-6 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
        @csrf

        {{-- CARD 1: DATA DIRI --}}
        <div class="relative bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition p-6 pt-8">
            <div class="absolute -top-3 -left-3 w-9 h-9 rounded-full bg-navy text-white text-xs font-display font-bold flex items-center justify-center shadow-md shadow-navy/30">1</div>
            <div class="h-1 w-12 bg-navy rounded-full mb-5"></div>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-navy/5 ring-1 ring-navy/10 flex items-center justify-center text-navy">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <p class="font-display font-semibold text-navy text-lg">Data Diri</p>
            </div>

            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama') }}"
                           class="w-full border border-gray-200 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-health/40 focus:border-health transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">NIM</label>
                    <input type="text" name="nim" value="{{ old('nim') }}"
                           class="w-full border border-gray-200 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-health/40 focus:border-health transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full border border-gray-200 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-health/40 focus:border-health transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                           class="w-full border border-gray-200 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-health/40 focus:border-health transition">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat</label>
                    <textarea name="alamat" rows="2"
                              class="w-full border border-gray-200 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-health/40 focus:border-health transition">{{ old('alamat') }}</textarea>
                </div>
            </div>
        </div>

        {{-- CARD 2: DATA AKADEMIK --}}
        <div class="relative bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition p-6 pt-8">
            <div class="absolute -top-3 -left-3 w-9 h-9 rounded-full bg-health text-white text-xs font-display font-bold flex items-center justify-center shadow-md shadow-health/30">2</div>
            <div class="h-1 w-12 bg-health rounded-full mb-5"></div>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-health/10 ring-1 ring-health/20 flex items-center justify-center text-health">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.42A12.1 12.1 0 0112 20.5a12.1 12.1 0 01-6.16-9.92L12 14z" />
                    </svg>
                </div>
                <p class="font-display font-semibold text-navy text-lg">Data Akademik</p>
            </div>

            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Universitas</label>
                    <input type="text" name="universitas" value="{{ old('universitas') }}"
                           class="w-full border border-gray-200 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-health/40 focus:border-health transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Fakultas</label>
                    <input type="text" name="fakultas" value="{{ old('fakultas') }}"
                           class="w-full border border-gray-200 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-health/40 focus:border-health transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Program Studi</label>
                    <input type="text" name="program_studi" value="{{ old('program_studi') }}"
                           class="w-full border border-gray-200 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-health/40 focus:border-health transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Semester</label>
                    <input type="text" name="semester" value="{{ old('semester') }}"
                           class="w-full border border-gray-200 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-health/40 focus:border-health transition">
                </div>
            </div>
        </div>

        {{-- CARD 3: DATA MAGANG --}}
        <div class="relative bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition p-6 pt-8">
            <div class="absolute -top-3 -left-3 w-9 h-9 rounded-full bg-gold text-navy text-xs font-display font-bold flex items-center justify-center shadow-md shadow-gold/40">3</div>
            <div class="h-1 w-12 bg-gold rounded-full mb-5"></div>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-gold/10 ring-1 ring-gold/20 flex items-center justify-center text-gold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <p class="font-display font-semibold text-navy text-lg">Data Magang</p>
            </div>

            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Periode Mulai</label>
                    <input type="date" name="periode_mulai" value="{{ old('periode_mulai') }}"
                           class="w-full border border-gray-200 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-health/40 focus:border-health transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Periode Selesai</label>
                    <input type="date" name="periode_selesai" value="{{ old('periode_selesai') }}"
                           class="w-full border border-gray-200 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-health/40 focus:border-health transition">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Bidang yang Diminati</label>
                    <select name="bidang_id" id="bidang_id"
                            class="w-full border border-gray-200 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-health/40 focus:border-health transition">
                        <option value="">-- Pilih Bidang --</option>
                        @foreach ($bidangs as $bidang)
                            <option value="{{ $bidang->id }}" data-portfolio="{{ $bidang->requires_portfolio ? '1' : '0' }}"
                                {{ old('bidang_id') == $bidang->id ? 'selected' : '' }}>
                                {{ $bidang->nama_bidang }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tujuan Magang</label>
                    <textarea name="tujuan_magang" rows="3"
                              class="w-full border border-gray-200 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-health/40 focus:border-health transition">{{ old('tujuan_magang') }}</textarea>
                </div>
            </div>
        </div>

        {{-- CARD 4: DOKUMEN --}}
        <div class="relative bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition p-6 pt-8">
            <div class="absolute -top-3 -left-3 w-9 h-9 rounded-full bg-navy text-white text-xs font-display font-bold flex items-center justify-center shadow-md shadow-navy/30">4</div>
            <div class="h-1 w-12 bg-navy rounded-full mb-5"></div>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-navy/5 ring-1 ring-navy/10 flex items-center justify-center text-navy">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16" />
                    </svg>
                </div>
                <p class="font-display font-semibold text-navy text-lg">Dokumen Persyaratan</p>
            </div>

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Pas Foto <span class="text-red-500 font-normal">*wajib, JPG/PNG max 1MB</span>
                    </label>
                    <input id="foto-input" type="file" name="foto" accept="image/jpeg,image/png"
                           class="w-full border border-gray-200 rounded-lg px-3.5 py-2 text-sm file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:bg-navy file:text-white file:text-xs file:font-medium hover:file:bg-navy-light">
                    <p id="foto-crop-status" class="mt-2 text-xs text-gray-500">Setelah memilih foto, atur potongan dengan rasio 3:4.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Surat Pengantar Kampus <span class="text-red-500 font-normal">*wajib, PDF max 2MB</span>
                    </label>
                    <input type="file" name="surat_pengantar"
                           class="w-full border border-gray-200 rounded-lg px-3.5 py-2 text-sm file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:bg-navy file:text-white file:text-xs file:font-medium hover:file:bg-navy-light">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">CV <span class="text-gray-400 font-normal">(opsional, PDF)</span></label>
                    <input type="file" name="cv"
                           class="w-full border border-gray-200 rounded-lg px-3.5 py-2 text-sm file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:bg-gray-200 file:text-gray-700 file:text-xs file:font-medium hover:file:bg-gray-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Proposal Magang <span class="text-gray-400 font-normal">(opsional, PDF)</span></label>
                    <input type="file" name="proposal"
                           class="w-full border border-gray-200 rounded-lg px-3.5 py-2 text-sm file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:bg-gray-200 file:text-gray-700 file:text-xs file:font-medium hover:file:bg-gray-300">
                </div>

                {{-- Muncul otomatis hanya untuk bidang tertentu (misal Desain/Editor) --}}
                <div id="portofolio-wrapper" class="hidden border-t border-dashed border-gray-200 pt-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Portofolio <span class="text-gold font-normal">(wajib untuk bidang ini — PDF/JPG/PNG, max 5MB)</span>
                    </label>
                    <input type="file" name="portofolio"
                           class="w-full border border-gray-200 rounded-lg px-3.5 py-2 text-sm file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:bg-gold file:text-navy file:text-xs file:font-medium hover:file:bg-gold-light">
                </div>
            </div>
        </div>

        <button type="submit"
                class="w-full bg-health hover:bg-health-dark transition text-white font-semibold py-3.5 rounded-lg shadow-md shadow-health/30">
            Kirim Pengajuan Magang
        </button>
</form>
</div>
</div>

<div id="foto-crop-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-navy/70 p-4" role="dialog" aria-modal="true" aria-labelledby="foto-crop-title">
    <div class="w-full max-w-lg rounded-2xl bg-white p-5 shadow-2xl">
        <div class="flex items-center justify-between gap-3">
            <div><h2 id="foto-crop-title" class="font-display text-lg font-bold text-navy">Potong Pas Foto</h2><p class="text-xs text-gray-500">Geser foto dan atur zoom sampai wajah berada di area 3:4.</p></div>
            <button id="foto-crop-cancel" type="button" class="rounded-lg px-3 py-2 text-sm text-gray-500 hover:bg-gray-100">Batal</button>
        </div>
        <div id="foto-crop-viewport" class="relative mx-auto mt-5 overflow-hidden bg-gray-900 select-none touch-none" style="width: min(75vw, 300px); height: min(70vh, 400px); aspect-ratio: 3 / 4;">
            <img id="foto-crop-image" alt="Pratinjau crop pas foto" class="absolute max-w-none cursor-move select-none" draggable="false">
            <div class="pointer-events-none absolute inset-0 ring-2 ring-white/90"></div>
        </div>
        <label class="mt-4 block text-xs font-medium text-gray-600">Zoom <input id="foto-crop-zoom" type="range" min="1" max="3" step="0.01" value="1" class="mt-2 w-full accent-[#0C2340]"></label>
        <div class="mt-5 flex justify-end gap-2"><button id="foto-crop-confirm" type="button" class="rounded-lg bg-navy px-4 py-2.5 text-sm font-semibold text-white hover:bg-navy-light">Gunakan Foto Ini</button></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const bidangSelect = document.getElementById('bidang_id');
    const portofolioWrapper = document.getElementById('portofolio-wrapper');

    function togglePortofolio() {
        const selected = bidangSelect.options[bidangSelect.selectedIndex];
        const needsPortfolio = selected?.dataset.portfolio === '1';
        portofolioWrapper.classList.toggle('hidden', !needsPortfolio);
    }

    bidangSelect.addEventListener('change', togglePortofolio);
    togglePortofolio();

    const fotoInput = document.getElementById('foto-input');
    const cropModal = document.getElementById('foto-crop-modal');
    const cropViewport = document.getElementById('foto-crop-viewport');
    const cropImage = document.getElementById('foto-crop-image');
    const cropZoom = document.getElementById('foto-crop-zoom');
    const cropStatus = document.getElementById('foto-crop-status');
    const cropCancel = document.getElementById('foto-crop-cancel');
    const cropConfirm = document.getElementById('foto-crop-confirm');
    let sourceImage = null;
    let cropState = { scale: 1, baseScale: 1, x: 0, y: 0, startX: 0, startY: 0, pointerId: null };

    function limitCropPosition() {
        const width = cropImage.offsetWidth;
        const height = cropImage.offsetHeight;
        const viewportWidth = cropViewport.clientWidth;
        const viewportHeight = cropViewport.clientHeight;
        cropState.x = Math.min(0, Math.max(viewportWidth - width, cropState.x));
        cropState.y = Math.min(0, Math.max(viewportHeight - height, cropState.y));
    }

    function renderCropImage() {
        const width = sourceImage.naturalWidth * cropState.baseScale * cropState.scale;
        const height = sourceImage.naturalHeight * cropState.baseScale * cropState.scale;
        cropImage.style.width = width + 'px';
        cropImage.style.height = height + 'px';
        limitCropPosition();
        cropImage.style.transform = `translate(${cropState.x}px, ${cropState.y}px)`;
    }

    function openCropper(file) {
        const reader = new FileReader();
        reader.onload = () => {
            sourceImage = new Image();
            sourceImage.onload = () => {
                cropModal.classList.remove('hidden');
                cropModal.classList.add('flex');
                const viewportWidth = cropViewport.clientWidth;
                const viewportHeight = cropViewport.clientHeight;
                cropState.baseScale = Math.max(viewportWidth / sourceImage.naturalWidth, viewportHeight / sourceImage.naturalHeight);
                cropState.scale = 1;
                cropState.x = (viewportWidth - sourceImage.naturalWidth * cropState.baseScale) / 2;
                cropState.y = (viewportHeight - sourceImage.naturalHeight * cropState.baseScale) / 2;
                cropImage.src = sourceImage.src;
                cropZoom.value = '1';
                renderCropImage();
            };
            sourceImage.src = reader.result;
        };
        reader.readAsDataURL(file);
    }

    function closeCropper() {
        cropModal.classList.add('hidden');
        cropModal.classList.remove('flex');
        cropImage.removeAttribute('src');
        sourceImage = null;
    }

    fotoInput.addEventListener('change', () => {
        const file = fotoInput.files?.[0];
        if (file) {
            cropStatus.textContent = 'Foto belum dipotong. Selesaikan crop 3:4 sebelum mengirim.';
            openCropper(file);
        }
    });

    cropZoom.addEventListener('input', () => {
        const oldWidth = cropImage.offsetWidth;
        const oldHeight = cropImage.offsetHeight;
        const centerX = cropViewport.clientWidth / 2;
        const centerY = cropViewport.clientHeight / 2;
        cropState.scale = Number(cropZoom.value);
        const newWidth = sourceImage.naturalWidth * cropState.baseScale * cropState.scale;
        const newHeight = sourceImage.naturalHeight * cropState.baseScale * cropState.scale;
        cropState.x = centerX - ((centerX - cropState.x) * newWidth / oldWidth);
        cropState.y = centerY - ((centerY - cropState.y) * newHeight / oldHeight);
        renderCropImage();
    });

    cropImage.addEventListener('pointerdown', (event) => {
        cropState.pointerId = event.pointerId;
        cropState.startX = event.clientX - cropState.x;
        cropState.startY = event.clientY - cropState.y;
        cropImage.setPointerCapture(event.pointerId);
    });
    cropImage.addEventListener('pointermove', (event) => {
        if (cropState.pointerId !== event.pointerId) return;
        cropState.x = event.clientX - cropState.startX;
        cropState.y = event.clientY - cropState.startY;
        renderCropImage();
    });
    cropImage.addEventListener('pointerup', () => { cropState.pointerId = null; });
    cropImage.addEventListener('pointercancel', () => { cropState.pointerId = null; });
    cropCancel.addEventListener('click', () => { fotoInput.value = ''; closeCropper(); cropStatus.textContent = 'Pilih pas foto lalu potong dengan rasio 3:4.'; });

    cropConfirm.addEventListener('click', () => {
        const canvas = document.createElement('canvas');
        const outputWidth = 450;
        const outputHeight = 600;
        const viewportWidth = cropViewport.clientWidth;
        const viewportHeight = cropViewport.clientHeight;
        const displayedWidth = sourceImage.naturalWidth * cropState.baseScale * cropState.scale;
        const displayedHeight = sourceImage.naturalHeight * cropState.baseScale * cropState.scale;
        const sx = Math.max(0, -cropState.x) / displayedWidth * sourceImage.naturalWidth;
        const sy = Math.max(0, -cropState.y) / displayedHeight * sourceImage.naturalHeight;
        const sw = viewportWidth / displayedWidth * sourceImage.naturalWidth;
        const sh = viewportHeight / displayedHeight * sourceImage.naturalHeight;
        canvas.width = outputWidth;
        canvas.height = outputHeight;
        canvas.getContext('2d').drawImage(sourceImage, sx, sy, sw, sh, 0, 0, outputWidth, outputHeight);
        canvas.toBlob((blob) => {
            if (!blob) return;
            const croppedFile = new File([blob], 'pas-foto-3x4.jpg', { type: 'image/jpeg' });
            const transfer = new DataTransfer();
            transfer.items.add(croppedFile);
            fotoInput.files = transfer.files;
            cropStatus.textContent = 'Pas foto sudah dipotong dengan rasio 3:4 dan siap dikirim.';
            cropStatus.className = 'mt-2 text-xs text-emerald-600';
            closeCropper();
        }, 'image/jpeg', 0.82);
    });
</script>
@endpush
