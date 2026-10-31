<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApproveApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    public function rules(): array
    {
        return [
            'bidang_id' => ['required', Rule::exists('bidangs', 'id')->where('is_active', true)],
            'approval_note' => ['required', 'string', 'min:10', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'bidang_id.required' => 'Pilih bidang penempatan terlebih dahulu.',
            'bidang_id.exists' => 'Bidang penempatan tidak tersedia atau tidak aktif.',
            'approval_note.required' => 'Catatan penerimaan wajib diisi.',
            'approval_note.min' => 'Catatan penerimaan minimal 10 karakter.',
        ];
    }
}
