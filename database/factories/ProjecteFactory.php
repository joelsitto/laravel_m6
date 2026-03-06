<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Projecte>
 */
class ProjecteFactory extends Factory
{
    private static int $counter = 1;

    public function definition(): array
    {
        $estats = ['PLANIFICACIO', 'EN_CURS', 'PAUSAT', 'FINALITZAT', 'CANCELAT'];
        $estat  = fake()->randomElement($estats);

        $dataInici      = fake()->dateTimeBetween('-2 years', '-1 month');
        $dataFiPrevista = fake()->dateTimeBetween($dataInici, '+1 year');

        return [
            'client_id'                 => Client::factory(),
            'gestor_id'                 => User::factory()->state(['rol' => 'GESTOR']),
            'nom'                       => fake()->bs(),
            'descripcio'                => fake()->paragraph(),
            'codi_projecte'             => 'PRJ-' . str_pad(self::$counter++, 4, '0', STR_PAD_LEFT),
            'estat'                     => $estat,
            'data_inici'                => $dataInici,
            'data_fi_prevista'          => $dataFiPrevista,
            'pressupost_hores_previstes' => fake()->randomFloat(2, 50, 1000),
        ];
    }
}

