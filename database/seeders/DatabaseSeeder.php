<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Comentari;
use App\Models\Projecte;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1) Admin principal
        $admin = User::factory()->admin()->create([
            'name' => 'Joel',
            'email' => 'joel@example.com',
            'password' => Hash::make('password'),
        ]);

        // 2) Clients: 3 actius + 1 inactiu
        $clientsActius = Client::factory()->count(3)->create();
        $clientInactiu = Client::factory()->inactiu()->create();

        // 3) Usuaris per RBAC
        $gestorGlobal = User::factory()->gestor()->create([
            'name' => 'Gestor Global',
            'email' => 'gestor.global@example.com',
        ]);
        $gestorSuport = User::factory()->gestor()->create([
            'name' => 'Gestor Suport',
            'email' => 'gestor.suport@example.com',
        ]);

        $devAssignatParcial = User::factory()->desenvolupador()->create([
            'name' => 'Dev Assignat',
            'email' => 'dev.assignat@example.com',
        ]);
        $dev2 = User::factory()->desenvolupador()->create(['email' => 'dev2@example.com']);
        $dev3 = User::factory()->desenvolupador()->create(['email' => 'dev3@example.com']);
        $dev4 = User::factory()->desenvolupador()->create(['email' => 'dev4@example.com']);

        $clientUserA = User::factory()->client()->create([
            'name' => 'Usuari Client A',
            'email' => 'client.a@example.com',
            'client_id' => $clientsActius[0]->id,
        ]);
        $clientUserB = User::factory()->client()->create([
            'name' => 'Usuari Client B',
            'email' => 'client.b@example.com',
            'client_id' => $clientsActius[1]->id,
        ]);

        // 4) Projectes (10 en total): tots amb client_id i gestor_id
        $distribucio = [
            ['client' => $clientsActius[0], 'estats' => ['EN_CURS', 'PLANIFICACIO', 'FINALITZAT']],
            ['client' => $clientsActius[1], 'estats' => ['EN_CURS', 'PAUSAT', 'PLANIFICACIO']],
            ['client' => $clientsActius[2], 'estats' => ['EN_CURS', 'PLANIFICACIO']],
            ['client' => $clientInactiu, 'estats' => ['CANCELAT', 'FINALITZAT']],
        ];

        $projectes = collect();
        $poolGestors = collect([$gestorGlobal, $gestorSuport]);
        $poolDevs = collect([$devAssignatParcial, $dev2, $dev3, $dev4]);

        foreach ($distribucio as $grup) {
            foreach ($grup['estats'] as $estat) {
                $projecte = Projecte::factory()->for($grup['client'])->create([
                    'gestor_id' => $poolGestors->random()->id,
                    'estat' => $estat,
                ]);

                // Relacio 1:1 de configuracio per projecte
                $projecte->configuracio()->create();

                $projectes->push($projecte);
            }
        }

        // DEV parcial: nomes en una part dels projectes (per provar policies)
        $projectesDevAssignat = $projectes->take(4);
        foreach ($projectes as $index => $projecte) {
            $ids = $poolDevs->except(0)->random(rand(1, 3))->pluck('id')->values();
            if ($projectesDevAssignat->contains('id', $projecte->id)) {
                $ids->push($devAssignatParcial->id);
            }

            $projecte->desenvolupadors()->sync($ids->unique()->values());
        }

        // Garantim projectes per als client-users (client_id coherent)
        if (! $projectes->where('client_id', $clientUserA->client_id)->count()) {
            $extra = Projecte::factory()->create([
                'client_id' => $clientUserA->client_id,
                'gestor_id' => $gestorGlobal->id,
                'estat' => 'EN_CURS',
            ]);
            $extra->configuracio()->create();
            $extra->desenvolupadors()->sync([$devAssignatParcial->id]);
            $projectes->push($extra);
        }

        if (! $projectes->where('client_id', $clientUserB->client_id)->count()) {
            $extra = Projecte::factory()->create([
                'client_id' => $clientUserB->client_id,
                'gestor_id' => $gestorGlobal->id,
                'estat' => 'PLANIFICACIO',
            ]);
            $extra->configuracio()->create();
            $extra->desenvolupadors()->sync([$dev2->id]);
            $projectes->push($extra);
        }

        // 5) Tickets i comentaris
        $totsElsUsuaris = User::all();

        foreach ($projectes as $projecte) {
            $numTickets = rand(2, 3);
            for ($t = 0; $t < $numTickets; $t++) {
                $ticket = Ticket::factory()
                    ->withCreador($totsElsUsuaris->random())
                    ->create(['projecte_id' => $projecte->id]);

                $numComentaris = rand(0, 4);
                for ($c = 0; $c < $numComentaris; $c++) {
                    Comentari::factory()
                        ->withAutor($totsElsUsuaris->random())
                        ->create(['ticket_id' => $ticket->id]);
                }
            }
        }
    }
}
