<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    public function rules(): array
    {
        return [
            'nama_bidang' => [
                'required', 'string', 'max:255',
                Rule::unique('bidangs', 'nama_bidang')->ignore($this->route('department')),
            ],
            'requires_portfolio' => ['boolean'],
            'is_active' => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'requires_portfolio' => $this->boolean('requires_portfolio'),
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    public function messages(): array
    {
        return [
            'nama_bidang.required' => 'Nama bidang wajib diisi.',
            'nama_bidang.unique' => 'Nama bidang ini sudah terdaftar.',
        ];
    }
}
