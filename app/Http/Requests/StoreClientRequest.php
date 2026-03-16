<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'cif' => ['required', 'string', 'max:255', Rule::unique('clients', 'cif')],
            'email_contacte' => ['nullable', 'email', 'max:255'],
            'telefon' => ['nullable', 'string', 'max:255'],
            'direccio' => ['nullable', 'string'],
            'actiu' => ['nullable', 'boolean'],
        ];
    }
}

