<?php

namespace Database\Factories;

use App\Models\Projecte;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ConfiguracioProjecte>
 */
class ConfiguracioProjecteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'projecte_id' => Projecte::factory(),
            'plantilla_correus' => fake()->randomElement(['FORMAL', 'INFORMAL', 'TECNICA']),
            'notificacions_actives' => fake()->boolean(80),
            'workflow_personalitzat' => fake()->boolean(50)
                ? [
                    'regla' => fake()->word(),
                    'nivell' => fake()->numberBetween(1, 3),
                ]
                : null,
            'requereix_aprovacio_client' => fake()->boolean(30),
        ];
    }
}
