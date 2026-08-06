<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ApproveApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    public function rules(): array
    {
        return ['approval_note' => ['required', 'string', 'min:10', 'max:1000']];
    }

    public function messages(): array
    {
        return [
            'approval_note.required' => 'Catatan penerimaan wajib diisi.',
            'approval_note.min' => 'Catatan penerimaan minimal 10 karakter.',
        ];
    }
}
