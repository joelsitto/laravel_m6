<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CanviarEstatTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'estat' => ['required', Rule::in(['ASSIGNAT', 'EN_PROGRES', 'EN_REVISIO', 'TANCAT'])],
        ];
    }
}

