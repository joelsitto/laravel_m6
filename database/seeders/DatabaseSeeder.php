<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Comentari;
use App\Models\Projecte;
use App\Models\RegistreTemps;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1) Usuaris base
        User::factory()->admin()->create([
            'name' => 'Joel',
            'email' => 'joel@example.com',
            'password' => Hash::make('password'),
        ]);

        $gestorGlobal = User::factory()->gestor()->create([
            'name' => 'Gestor Global',
            'email' => 'gestor.global@example.com',
        ]);
        $gestorSuport = User::factory()->gestor()->create([
            'name' => 'Gestor Suport',
            'email' => 'gestor.suport@example.com',
        ]);

        $dev1 = User::factory()->desenvolupador()->create([
            'name' => 'Dev Assignat',
            'email' => 'dev.assignat@example.com',
        ]);
        $dev2 = User::factory()->desenvolupador()->create(['email' => 'dev2@example.com']);
        $dev3 = User::factory()->desenvolupador()->create(['email' => 'dev3@example.com']);
        $dev4 = User::factory()->desenvolupador()->create(['email' => 'dev4@example.com']);

        // 2) Clients i usuaris client
        $clientsActius = Client::factory()->count(3)->create();
        $clientInactiu = Client::factory()->inactiu()->create();

        User::factory()->client()->create([
            'name' => 'Usuari Client A',
            'email' => 'client.a@example.com',
            'client_id' => $clientsActius[0]->id,
        ]);

        User::factory()->client()->create([
            'name' => 'Usuari Client B',
            'email' => 'client.b@example.com',
            'client_id' => $clientsActius[1]->id,
        ]);

        // 3) Projectes amb equip real
        $projectes = collect();
        $poolGestors = collect([$gestorGlobal, $gestorSuport]);
        $poolDevs = collect([$dev1, $dev2, $dev3, $dev4]);

        $distribucio = [
            ['client' => $clientsActius[0], 'estats' => ['EN_CURS', 'PLANIFICACIO', 'FINALITZAT']],
            ['client' => $clientsActius[1], 'estats' => ['EN_CURS', 'PAUSAT', 'PLANIFICACIO']],
            ['client' => $clientsActius[2], 'estats' => ['EN_CURS', 'PLANIFICACIO']],
            ['client' => $clientInactiu, 'estats' => ['CANCELAT', 'FINALITZAT']],
        ];

        foreach ($distribucio as $grup) {
            foreach ($grup['estats'] as $estatProjecte) {
                $projecte = Projecte::factory()->for($grup['client'])->create([
                    'gestor_id' => $poolGestors->random()->id,
                    'estat' => $estatProjecte,
                ]);

                $projecte->configuracio()->create();

                $idsDevs = $poolDevs->random(rand(2, 3))->pluck('id')->unique()->values();
                $projecte->desenvolupadors()->sync($idsDevs);

                $projectes->push($projecte);
            }
        }

        // 4) Tickets coherents + pare/fill + comentaris + registre temps
        $totsElsUsuaris = User::all();

        foreach ($projectes as $projecte) {
            $devsProjecte = $projecte->desenvolupadors()->get();
            if ($devsProjecte->isEmpty()) {
                continue;
            }

            $numTickets = rand(4, 6);
            $ticketsProjecte = collect();

            for ($t = 0; $t < $numTickets; $t++) {
                $devAssignat = $devsProjecte->random();
                $estatTicket = collect(['ASSIGNAT', 'EN_PROGRES', 'EN_REVISIO', 'TANCAT'])->random();

                $ticket = Ticket::factory()
                    ->withCreador($totsElsUsuaris->random())
                    ->create([
                        'projecte_id' => $projecte->id,
                        'assignat_a' => $devAssignat->id,
                        'estat' => $estatTicket,
                        'ticket_pare_id' => null,
                    ]);

                $ticketsProjecte->push($ticket);

                $numComentaris = rand(0, 3);
                for ($c = 0; $c < $numComentaris; $c++) {
                    Comentari::factory()
                        ->withAutor($totsElsUsuaris->random())
                        ->create(['ticket_id' => $ticket->id]);
                }

                // Registres de temps nomes del dev assignat, amb dates passades i hores coherents
                if (in_array($ticket->estat, ['ASSIGNAT', 'EN_PROGRES', 'EN_REVISIO', 'TANCAT'], true)) {
                    $dies = collect([
                        Carbon::now()->subDays(rand(1, 10))->toDateString(),
                        Carbon::now()->subDays(rand(11, 20))->toDateString(),
                    ])->unique()->values();

                    foreach ($dies as $dia) {
                        $horesDia = rand(1, 8); // <= 12h/dia per ticket

                        RegistreTemps::create([
                            'ticket_id' => $ticket->id,
                            'user_id' => $ticket->assignat_a,
                            'data' => $dia,
                            'hores' => $horesDia,
                            'descripcio' => 'Treball de desenvolupament del ticket.',
                        ]);
                    }
                }
            }

            // Alguns tickets pare-fill coherents (1 nivell)
            if ($ticketsProjecte->count() >= 3) {
                $pare = $ticketsProjecte->first();
                $fills = $ticketsProjecte->slice(1, 2);

                foreach ($fills as $fill) {
                    $fill->update(['ticket_pare_id' => $pare->id]);
                }
            }
        }
    }
}
