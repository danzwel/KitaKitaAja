<x-intern.layouts.app title="Profil Saya">

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

        {{-- ============ LEFT COLUMN ============ --}}
        <div class="space-y-6">

            {{-- Photo Card --}}
            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">
                <div class="h-20 bg-gradient-to-r from-[#0B1F3D] to-[#1a4480]"></div>
                <div class="-mt-12 flex flex-col items-center px-6 pb-6">
                    <div class="relative">
                        <div class="h-24 w-24 overflow-hidden rounded-2xl border-4 border-white bg-gray-100 shadow-lg">
                            @if($intern->profile_photo_path)
                                <img src="{{ Storage::url($intern->profile_photo_path) }}" alt="Foto" class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-gray-200 to-gray-300 text-3xl font-bold text-gray-400">
                                    {{ Str::upper(Str::substr($intern->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        {{-- Upload button overlay --}}
                        <form id="photo-upload-form" method="POST" action="{{ route('intern.profile.photo') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="photo" id="photo" class="hidden" accept="image/jpeg,image/png">
                            <label for="photo" class="absolute -bottom-1 -right-1 flex h-8 w-8 cursor-pointer items-center justify-center rounded-lg bg-navy text-white shadow-md transition hover:bg-navy/80">
                                <i class="ti ti-camera text-sm"></i>
                            </label>
                        </form>
                    </div>
                    <h3 class="mt-4 font-heading text-lg font-bold text-gray-900">{{ $intern->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $intern->username }}</p>
                    @error('photo')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
                    @if (session('status') === 'photo-updated')<p class="mt-2 text-xs text-emerald-600">Foto berhasil diperbarui.</p>@endif
                    <p class="mt-1 text-[11px] text-gray-400">JPG, PNG &middot; Maks. 2MB</p>
                </div>
            </div>

            {{-- Academic Info Card --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
                <h2 class="mb-4 flex items-center gap-2 font-heading text-sm font-bold uppercase tracking-wider text-gray-400">
                    <i class="ti ti-school text-base"></i> Informasi Akademik
                </h2>
                <div class="space-y-4">
                    @php
                        $fields = [
                            ['label' => 'Nama Lengkap', 'value' => $intern->name],
                            ['label' => 'NIM', 'value' => $intern->username],
                            ['label' => 'Perguruan Tinggi', 'value' => $intern->university],
                            ['label' => 'Program Studi', 'value' => $intern->internshipApplication?->program_studi ?? '-'],
                            ['label' => 'Semester', 'value' => $intern->internshipApplication?->semester ?? '-'],
                        ];
                    @endphp

                    @foreach ($fields as $field)
                        <div class="flex items-start justify-between gap-4">
                            <span class="shrink-0 text-xs font-medium text-gray-400">{{ $field['label'] }}</span>
                            <span class="text-right text-sm font-semibold text-gray-800">{{ $field['value'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ============ RIGHT COLUMN ============ --}}
        <div class="space-y-6 xl:col-span-2">

            {{-- Contact Form --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
                <h2 class="flex items-center gap-2 font-heading text-sm font-bold uppercase tracking-wider text-gray-400">
                    <i class="ti ti-address-book text-base"></i> Informasi Kontak
                </h2>
                <p class="mt-1 mb-5 text-sm text-gray-500">Perbarui email, nomor HP, dan alamat Anda.</p>

                @if (session('status') === 'profile-updated')
                    <div class="mb-4 flex items-center gap-2 rounded-lg bg-emerald-50 p-3 text-sm text-emerald-700 ring-1 ring-emerald-200/60">
                        <i class="ti ti-circle-check text-base"></i> Profil berhasil diperbarui.
                    </div>
                @endif

                <form method="POST" action="{{ route('intern.profile.update') }}" class="space-y-4">
                    @csrf
                    @method('patch')

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $intern->email ?? $intern->internshipApplication?->email) }}"
                                class="block w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 px-4 text-sm transition focus:border-navy focus:bg-white focus:ring-2 focus:ring-navy/20 focus:outline-none">
                            @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="phone" class="mb-1.5 block text-sm font-medium text-gray-700">Nomor HP</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $intern->phone ?? $intern->internshipApplication?->no_hp) }}"
                                class="block w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 px-4 text-sm transition focus:border-navy focus:bg-white focus:ring-2 focus:ring-navy/20 focus:outline-none">
                            @error('phone')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div>
                        <label for="address" class="mb-1.5 block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea name="address" id="address" rows="3"
                            class="block w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 px-4 text-sm transition focus:border-navy focus:bg-white focus:ring-2 focus:ring-navy/20 focus:outline-none">{{ old('address', $intern->address ?? $intern->internshipApplication?->alamat) }}</textarea>
                        @error('address')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="rounded-xl bg-navy px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-navy/20 transition hover:brightness-110 active:scale-[0.98]">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            {{-- Password Form --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
                <h2 class="flex items-center gap-2 font-heading text-sm font-bold uppercase tracking-wider text-gray-400">
                    <i class="ti ti-lock text-base"></i> Ganti Password
                </h2>
                <p class="mt-1 mb-5 text-sm text-gray-500">Pastikan akun Anda menggunakan password yang aman.</p>

                @if (session('status') === 'password-updated')
                    <div class="mb-4 flex items-center gap-2 rounded-lg bg-emerald-50 p-3 text-sm text-emerald-700 ring-1 ring-emerald-200/60">
                        <i class="ti ti-circle-check text-base"></i> Password berhasil diubah.
                    </div>
                @endif

                <form method="POST" action="{{ route('intern.profile.password') }}" class="space-y-4">
                    @csrf
                    @method('put')

                    <div>
                        <label for="current_password" class="mb-1.5 block text-sm font-medium text-gray-700">Password Saat Ini</label>
                        <input type="password" name="current_password" id="current_password"
                            class="block w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 px-4 text-sm transition focus:border-navy focus:bg-white focus:ring-2 focus:ring-navy/20 focus:outline-none">
                        @error('current_password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="new_password" class="mb-1.5 block text-sm font-medium text-gray-700">Password Baru</label>
                            <input type="password" name="password" id="new_password"
                                class="block w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 px-4 text-sm transition focus:border-navy focus:bg-white focus:ring-2 focus:ring-navy/20 focus:outline-none">
                            @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="block w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 px-4 text-sm transition focus:border-navy focus:bg-white focus:ring-2 focus:ring-navy/20 focus:outline-none">
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="rounded-xl bg-navy px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-navy/20 transition hover:brightness-110 active:scale-[0.98]">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="profile-photo-crop-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-[#0B1F3D]/70 p-4" role="dialog" aria-modal="true">
        <div class="w-full max-w-lg rounded-2xl bg-white p-5 shadow-2xl">
            <div class="flex items-center justify-between gap-3"><div><h2 class="font-heading text-lg font-bold text-gray-900">Potong Foto Profil</h2><p class="text-xs text-gray-500">Atur foto dengan rasio 3:4.</p></div><button id="profile-photo-crop-cancel" type="button" class="rounded-lg px-3 py-2 text-sm text-gray-500 hover:bg-gray-100">Batal</button></div>
            <div id="profile-photo-crop-viewport" class="relative mx-auto mt-5 overflow-hidden bg-gray-900 select-none touch-none" style="width:min(75vw,300px);height:min(70vh,400px);aspect-ratio:3/4"><img id="profile-photo-crop-image" alt="Pratinjau foto profil" class="absolute max-w-none cursor-move select-none" draggable="false"></div>
            <label class="mt-4 block text-xs font-medium text-gray-600">Zoom <input id="profile-photo-crop-zoom" type="range" min="1" max="3" step="0.01" value="1" class="mt-2 w-full accent-[#0B1F3D]"></label>
            <div class="mt-5 flex justify-end"><button id="profile-photo-crop-confirm" type="button" class="rounded-lg bg-[#0B1F3D] px-4 py-2.5 text-sm font-semibold text-white">Gunakan Foto Ini</button></div>
        </div>
    </div>

@push('scripts')
<script>
(() => {
    const input = document.getElementById('photo'), form = document.getElementById('photo-upload-form');
    const modal = document.getElementById('profile-photo-crop-modal'), viewport = document.getElementById('profile-photo-crop-viewport');
    const image = document.getElementById('profile-photo-crop-image'), zoom = document.getElementById('profile-photo-crop-zoom');
    const cancel = document.getElementById('profile-photo-crop-cancel'), confirm = document.getElementById('profile-photo-crop-confirm');
    let source = null, state = { base: 1, scale: 1, x: 0, y: 0, pointer: null, offsetX: 0, offsetY: 0 };
    const clamp = () => { const w=image.offsetWidth,h=image.offsetHeight,vw=viewport.clientWidth,vh=viewport.clientHeight; state.x=Math.min(0,Math.max(vw-w,state.x)); state.y=Math.min(0,Math.max(vh-h,state.y)); };
    const render = () => { image.style.width=(source.naturalWidth*state.base*state.scale)+'px'; image.style.height=(source.naturalHeight*state.base*state.scale)+'px'; clamp(); image.style.transform=`translate(${state.x}px,${state.y}px)`; };
    const close = () => { modal.classList.add('hidden'); modal.classList.remove('flex'); image.removeAttribute('src'); source=null; };
    input.addEventListener('change', () => { const file=input.files?.[0]; if(!file)return; const reader=new FileReader(); reader.onload=()=>{ source=new Image(); source.onload=()=>{ modal.classList.remove('hidden'); modal.classList.add('flex'); state.base=Math.max(viewport.clientWidth/source.naturalWidth,viewport.clientHeight/source.naturalHeight); state.scale=1; state.x=(viewport.clientWidth-source.naturalWidth*state.base)/2; state.y=(viewport.clientHeight-source.naturalHeight*state.base)/2; image.src=source.src; zoom.value='1'; render(); }; source.src=reader.result; }; reader.readAsDataURL(file); });
    zoom.addEventListener('input',()=>{ const oldW=image.offsetWidth,oldH=image.offsetHeight,cx=viewport.clientWidth/2,cy=viewport.clientHeight/2; state.scale=Number(zoom.value); const nw=source.naturalWidth*state.base*state.scale,nh=source.naturalHeight*state.base*state.scale; state.x=cx-(cx-state.x)*nw/oldW; state.y=cy-(cy-state.y)*nh/oldH; render(); });
    image.addEventListener('pointerdown',e=>{state.pointer=e.pointerId;state.offsetX=e.clientX-state.x;state.offsetY=e.clientY-state.y;image.setPointerCapture(e.pointerId);});
    image.addEventListener('pointermove',e=>{if(state.pointer!==e.pointerId)return;state.x=e.clientX-state.offsetX;state.y=e.clientY-state.offsetY;render();});
    image.addEventListener('pointerup',()=>state.pointer=null); image.addEventListener('pointercancel',()=>state.pointer=null);
    cancel.addEventListener('click',()=>{input.value='';close();});
    confirm.addEventListener('click',()=>{ const canvas=document.createElement('canvas'); canvas.width=450;canvas.height=600; const dw=source.naturalWidth*state.base*state.scale,dh=source.naturalHeight*state.base*state.scale; const sx=Math.max(0,-state.x)/dw*source.naturalWidth,sy=Math.max(0,-state.y)/dh*source.naturalHeight,sw=viewport.clientWidth/dw*source.naturalWidth,sh=viewport.clientHeight/dh*source.naturalHeight; canvas.getContext('2d').drawImage(source,sx,sy,sw,sh,0,0,450,600); canvas.toBlob(blob=>{if(!blob)return;const data=new DataTransfer();data.items.add(new File([blob],'foto-profil-3x4.jpg',{type:'image/jpeg'}));input.files=data.files;close();form.submit();},'image/jpeg',.82); });
})();
</script>
@endpush

</x-intern.layouts.app>
