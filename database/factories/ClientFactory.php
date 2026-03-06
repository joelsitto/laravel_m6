<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre'         => fake()->company(),
            'cif'            => strtoupper(fake()->bothify('?########')),
            'email_contacte' => fake()->unique()->companyEmail(),
            'telefon'        => fake()->phoneNumber(),
            'direccio'       => fake()->address(),
            'actiu'          => true,
        ];
    }

    public function inactiu(): static
    {
        return $this->state(fn (array $attributes) => [
            'actiu' => false,
        ]);
    }
}

