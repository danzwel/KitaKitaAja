<?php

namespace App\Http\Requests\Admin;

use App\Models\Application;
use Illuminate\Foundation\Http\FormRequest;

class RejectApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $admin = $this->user('admin');

        // Delegasikan aturan "boleh diproses atau tidak" ke ApplicationPolicy,
        // supaya tidak ada aturan bisnis yang terduplikasi di dua tempat.
        return $admin !== null && $admin->can('process', $this->route('application'));
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
