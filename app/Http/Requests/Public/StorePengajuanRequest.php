<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

class StorePengajuanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
{
    return [
        'nama' => ['required', 'string', 'max:255'],
        'nim' => ['required', 'string', 'max:30'],
        'universitas' => ['required', 'string', 'max:255'],
        'fakultas' => ['nullable', 'string', 'max:255'],
        'program_studi' => ['required', 'string', 'max:255'],
        'semester' => ['required', 'string', 'max:5'],
        'email' => ['required', 'email', 'max:255'],
        'no_hp' => ['required', 'string', 'max:20'],
        'alamat' => ['required', 'string'],
        'periode_mulai' => ['required', 'date'],
        'periode_selesai' => ['required', 'date', 'after_or_equal:periode_mulai'],
        'surat_pengantar' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
        'foto' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
        'cv' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
        'transkrip_nilai' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
    ];
}
    public function messages(): array
    {
        return [
            'surat_pengantar.mimes' => 'Surat pengantar harus berformat PDF.',
            'surat_pengantar.max' => 'Ukuran surat pengantar maksimal 2MB.',
            'periode_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'foto.required' => 'Pas foto wajib diupload.',
            'foto.image' => 'Foto harus berformat JPG atau PNG.',
            'transkrip_nilai.required' => 'Transkrip nilai wajib diunggah.',
            'transkrip_nilai.mimes' => 'Transkrip nilai harus berformat PDF, JPG, atau PNG.',
        ];
    }

    public function withValidator($validator)
{
    $validator->after(function ($validator) {
        $this->validateFileContent($validator, 'foto', ['image/jpeg', 'image/png']);

        if ($this->hasFile('surat_pengantar')) {
            $this->validateFileContent($validator, 'surat_pengantar', ['application/pdf']);
        }
        if ($this->hasFile('cv')) {
            $this->validateFileContent($validator, 'cv', ['application/pdf']);
        }
        $this->validateFileContent($validator, 'transkrip_nilai', ['application/pdf', 'image/jpeg', 'image/png']);
    });
}

private function validateFileContent($validator, string $field, array $allowedMimes): void
{
    if (! $this->hasFile($field)) {
        return;
    }

    $file = $this->file($field);
    $realMime = $file->getMimeType();

    if (! in_array($realMime, $allowedMimes, true)) {
        $validator->errors()->add($field, 'Isi file tidak sesuai dengan format yang diizinkan (terdeteksi: '.$realMime.').');
    }
}
}
