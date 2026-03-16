<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $client = $this->route('client');
        $clientId = is_object($client) ? $client->id : $client;

        return [
            'nom' => ['required', 'string', 'max:255'],
            'cif' => ['required', 'string', 'max:255', Rule::unique('clients', 'cif')->ignore($clientId)],
            'email_contacte' => ['nullable', 'email', 'max:255'],
            'telefon' => ['nullable', 'string', 'max:255'],
            'direccio' => ['nullable', 'string'],
            'actiu' => ['nullable', 'boolean'],
        ];
    }
}

