<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'projecte_id' => ['required', 'integer', Rule::exists('projectes', 'id')],
            'titol' => ['required', 'string', 'max:255'],
            'descripcio' => ['nullable', 'string'],
            'estat' => ['nullable', Rule::in(['NOU', 'OBERT', 'TANCAT'])],
        ];
    }
}

