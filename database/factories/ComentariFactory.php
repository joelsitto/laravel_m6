<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comentari>
 */
class ComentariFactory extends Factory
{
    public function definition(): array
    {
        return [
            'ticket_id' => Ticket::factory(),
            'autor_id'  => User::factory(),
            'text'      => fake()->paragraph(),
        ];
    }

    /** Injecta un user existent com a autor (evita crear users addicionals al seeder). */
    public function withAutor(User $user): static
    {
        return $this->state(['autor_id' => $user->id]);
    }
}
