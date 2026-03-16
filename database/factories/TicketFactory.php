<?php

namespace Database\Factories;

use App\Models\Projecte;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    private static int $counter = 1;

    public function definition(): array
    {
        return [
            'projecte_id' => Projecte::factory(),
            'creador_id'  => User::factory(),
            'codi_ticket' => 'TKT-' . str_pad(self::$counter++, 4, '0', STR_PAD_LEFT),
            'titol'       => fake()->sentence(6),
            'descripcio'  => fake()->optional(0.7)->paragraph(),
            'estat'       => fake()->randomElement(['NOU', 'OBERT', 'TANCAT']),
        ];
    }

    /** Injecta un user existent com a creador (evita crear users addicionals al seeder). */
    public function withCreador(User $user): static
    {
        return $this->state(['creador_id' => $user->id]);
    }
}

