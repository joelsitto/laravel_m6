<?php

namespace App\Http\Requests;

use App\Models\Ticket;
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
            'ticket_pare_id' => ['nullable', 'integer', Rule::exists('tickets', 'id')],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $ticket = $this->route('ticket');
            $projecte = $this->route('projecte');
            $parentId = (int) ($this->input('ticket_pare_id') ?? 0);

            if (! $ticket || ! $projecte || $parentId <= 0) {
                return;
            }

            if ((int) $ticket->id === $parentId) {
                $validator->errors()->add('ticket_pare_id', 'Un ticket no pot ser pare de si mateix.');
                return;
            }

            $pare = Ticket::find($parentId);
            if (! $pare) {
                return;
            }

            if ((int) $pare->projecte_id !== (int) $projecte->id) {
                $validator->errors()->add('ticket_pare_id', 'El pare i el fill han de ser del mateix projecte.');
            }

            if (! is_null($pare->ticket_pare_id)) {
                $validator->errors()->add('ticket_pare_id', 'Nomes es permet 1 nivell de jerarquia.');
            }

            if ($ticket->fills()->exists()) {
                $validator->errors()->add('ticket_pare_id', 'Un ticket pare no pot passar a ser fill si ja te fills.');
            }
        });
    }
}

