<?php

namespace App\Http\Requests;

use App\Models\Ticket;
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
            'ticket_pare_id' => ['nullable', 'integer', Rule::exists('tickets', 'id')],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $parentId = (int) ($this->input('ticket_pare_id') ?? 0);
            if ($parentId <= 0) {
                return;
            }

            $projecte = $this->route('projecte');
            $pare = Ticket::find($parentId);

            if (! $pare || ! $projecte) {
                return;
            }

            if ((int) $pare->projecte_id !== (int) $projecte->id) {
                $validator->errors()->add('ticket_pare_id', 'El pare i el fill han de ser del mateix projecte.');
            }

            if (! is_null($pare->ticket_pare_id)) {
                $validator->errors()->add('ticket_pare_id', 'Nomes es permet 1 nivell de jerarquia.');
            }
        });
    }
}

