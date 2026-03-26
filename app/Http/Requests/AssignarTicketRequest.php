<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssignarTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'assignat_a' => ['required', 'integer', Rule::exists('users', 'id')],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $ticket = $this->route('ticket');
            $userId = (int) $this->input('assignat_a');

            if (! $ticket || $userId <= 0) {
                return;
            }

            $user = User::find($userId);
            if (! $user) {
                return;
            }

            if (! $user->hasRole('DEV')) {
                $validator->errors()->add('assignat_a', 'Nomes es pot assignar un ticket a un usuari DEV.');
                return;
            }

            $esMembre = $ticket->projecte->desenvolupadors()->where('users.id', $userId)->exists();
            if (! $esMembre) {
                $validator->errors()->add('assignat_a', 'L\'usuari assignat ha de formar part de l\'equip del projecte.');
            }
        });
    }
}

