<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Projecte;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {

        // Esto del seeder me lo ha hecho el compadre
        // ── 1. El teu usuari personal (admin) ──────────────────────────────
        User::factory()->admin()->create([
            'name'     => 'Joel',
            'email'    => 'joel@example.com',
            'password' => Hash::make('password'),
        ]);

        // ── 2. Clients ─────────────────────────────────────────────────────
        $clients      = Client::factory()->count(3)->create();        // 3 actius
        $clientInactiu = Client::factory()->inactiu()->create();       // 1 inactiu

        // ── 3. Usuaris ─────────────────────────────────────────────────────
        $gestors = User::factory()->gestor()->count(2)->create();
        $devs    = User::factory()->desenvolupador()->count(4)->create();

        // 2 users CLIENT, cadascun vinculat a un client actiu diferent
        User::factory()->client()->create([
            'name'      => 'Usuari Alpha',
            'email'     => 'usuari.alpha@example.com',
            'client_id' => $clients[0]->id,
        ]);
        User::factory()->client()->create([
            'name'      => 'Usuari Beta',
            'email'     => 'usuari.beta@example.com',
            'client_id' => $clients[1]->id,
        ]);

        // ── 4. Projectes ──────────────────────────────────────────────────
        // 3 per client1, 3 per client2, 2 per client3, 2 per client inactiu = 10
        $distribucio = [
            ['client' => $clients[0],    'estats' => ['EN_CURS', 'PLANIFICACIO', 'FINALITZAT']],
            ['client' => $clients[1],    'estats' => ['EN_CURS', 'PAUSAT', 'PLANIFICACIO']],
            ['client' => $clients[2],    'estats' => ['EN_CURS', 'PLANIFICACIO']],
            ['client' => $clientInactiu, 'estats' => ['CANCELAT', 'FINALITZAT']],
        ];

        foreach ($distribucio as $grup) {
            foreach ($grup['estats'] as $estat) {
                $projecte = Projecte::factory()
                    ->for($grup['client'])
                    ->create([
                        'gestor_id' => $gestors->random()->id,
                        'estat'     => $estat,
                    ]);

                // Assignar 1–3 devs aleatoris al projecte
                $projecte->desenvolupadors()->attach(
                    $devs->random(rand(1, min(3, $devs->count())))->pluck('id')
                );
            }
        }
    }
}
