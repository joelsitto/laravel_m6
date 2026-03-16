<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjecteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => [
                'sometimes',
                'integer',
                Rule::exists('clients', 'id')->where(fn ($query) => $query->where('actiu', true)),
            ],
            'gestor_id' => ['required', 'integer', Rule::exists('users', 'id')],
            'nom' => ['required', 'string', 'max:255'],
            'descripcio' => ['nullable', 'string'],
            'estat' => ['nullable', Rule::in(['PLANIFICACIO', 'EN_CURS', 'PAUSAT', 'FINALITZAT', 'CANCELAT'])],
            'data_inici' => ['nullable', 'date', 'before_or_equal:data_fi_prevista'],
            'data_fi_prevista' => ['nullable', 'date', 'after_or_equal:data_inici'],
            'pressupost_hores_previstes' => ['required', 'numeric', 'gt:0'],
        ];
    }
}

