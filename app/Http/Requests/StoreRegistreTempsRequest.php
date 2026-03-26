<?php

namespace App\Http\Requests;

use App\Models\RegistreTemps;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRegistreTempsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ticket_id' => ['required', 'integer', Rule::exists('tickets', 'id')],
            'data' => ['required', 'date', 'before_or_equal:today'],
            'hores' => ['required', 'numeric', 'gt:0'],
            'descripcio' => ['nullable', 'string'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $ticket = $this->route('ticket');
            $ticketId = (int) $this->input('ticket_id');
            $user = $this->user();

            if (! $ticket || ! $user) {
                return;
            }

            if ($ticketId !== (int) $ticket->id) {
                $validator->errors()->add('ticket_id', 'Ticket no valid per aquest formulari.');
                return;
            }

            if (! $user->hasRole('DEV') || (int) $ticket->assignat_a !== (int) $user->id) {
                $validator->errors()->add('hores', 'Nomes el dev assignat pot registrar temps.');
                return;
            }

            $horesNovaLinia = (float) $this->input('hores');
            $horesDia = (float) RegistreTemps::query()
                ->where('ticket_id', $ticket->id)
                ->where('user_id', $user->id)
                ->whereDate('data', $this->input('data'))
                ->sum('hores');

            if (($horesDia + $horesNovaLinia) > 12) {
                $validator->errors()->add('hores', 'No pots superar 12h al dia en aquest ticket.');
            }
        });
    }
}

