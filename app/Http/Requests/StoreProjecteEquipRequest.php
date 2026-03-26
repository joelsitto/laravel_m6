<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjecteEquipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', Rule::exists('users', 'id')],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $projecte = $this->route('projecte');
            $userId = (int) $this->input('user_id');

            if (! $projecte || $userId <= 0) {
                return;
            }

            $user = User::find($userId);
            if (! $user) {
                return;
            }

            if (! $user->hasRole('DEV')) {
                $validator->errors()->add('user_id', 'Nomes es poden afegir usuaris amb rol DEV.');
                return;
            }

            $jaEsMembre = $projecte->desenvolupadors()->where('users.id', $userId)->exists();
            if ($jaEsMembre) {
                $validator->errors()->add('user_id', 'Aquest usuari ja forma part de l\'equip del projecte.');
            }
        });
    }
}

