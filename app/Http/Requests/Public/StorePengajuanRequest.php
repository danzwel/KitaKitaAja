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
        'fakultas' => ['required', 'string', 'max:255'],
        'program_studi' => ['required', 'string', 'max:255'],
        'semester' => ['required', 'string', 'max:5'],
        'email' => ['required', 'email', 'max:255'],
        'no_hp' => ['required', 'string', 'max:20'],
        'alamat' => ['required', 'string'],
        'periode_mulai' => ['required', 'date'],
        'periode_selesai' => ['required', 'date', 'after_or_equal:periode_mulai'],
        'bidang_id' => ['required', 'exists:bidangs,id'],
        'tujuan_magang' => ['required', 'string'],

        'surat_pengantar' => ['required', 'file', 'mimes:pdf', 'max:2048'],
        'foto' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
        'cv' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
        'proposal' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
        'portofolio' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
    ];
}
    public function messages(): array
    {
        return [
            'surat_pengantar.required' => 'Surat pengantar kampus wajib diupload.',
            'surat_pengantar.mimes' => 'Surat pengantar harus berformat PDF.',
            'surat_pengantar.max' => 'Ukuran surat pengantar maksimal 2MB.',
            'periode_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'foto.required' => 'Pas foto wajib diupload.',
            'foto.image' => 'Foto harus berformat JPG atau PNG.',
            'bidang_id.required' => 'Silakan pilih bidang yang diminati.',
        ];
    }

    public function withValidator($validator)
{
    $validator->after(function ($validator) {
        $this->validateFileContent($validator, 'surat_pengantar', ['application/pdf']);
        $this->validateFileContent($validator, 'foto', ['image/jpeg', 'image/png']);

        if ($this->hasFile('cv')) {
            $this->validateFileContent($validator, 'cv', ['application/pdf']);
        }
        if ($this->hasFile('proposal')) {
            $this->validateFileContent($validator, 'proposal', ['application/pdf']);
        }
        if ($this->hasFile('portofolio')) {
            $this->validateFileContent($validator, 'portofolio', [
                'application/pdf', 'image/jpeg', 'image/png',
            ]);
        }
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