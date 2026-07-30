<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInternRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'university' => ['required', 'string', 'max:255'],
            'department_id' => ['required', 'exists:departments,id'],
            'period' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:aktif,selesai'],
        ];
    }
}
