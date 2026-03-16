<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjecteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => [
                'required',
                'integer',
                Rule::exists('clients', 'id')->where(fn ($query) => $query->where('actiu', true)),
            ],
            'gestor_id' => ['required', 'integer', Rule::exists('users', 'id')],
            'nom' => ['required', 'string', 'max:255'],
            'descripcio' => ['nullable', 'string'],
            'data_inici' => ['nullable', 'date', 'before_or_equal:data_fi_prevista'],
            'data_fi_prevista' => ['nullable', 'date', 'after_or_equal:data_inici'],
            'pressupost_hores_previstes' => ['required', 'numeric', 'gt:0'],
        ];
    }
}

