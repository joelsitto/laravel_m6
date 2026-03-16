<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titol' => ['required', 'string', 'max:255'],
            'descripcio' => ['nullable', 'string'],
            'estat' => ['required', Rule::in(['NOU', 'OBERT', 'TANCAT'])],
        ];
    }
}

