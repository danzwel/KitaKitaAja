<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RejectApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    public function rules(): array
    {
        return [
            'rejection_reason' => ['required', 'string', 'min:10', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
            'rejection_reason.min' => 'Alasan penolakan minimal 10 karakter.',
        ];
    }
}
