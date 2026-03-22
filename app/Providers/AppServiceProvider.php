<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\Comentari;
use App\Models\Projecte;
use App\Models\Ticket;
use App\Policies\ClientPolicy;
use App\Policies\ComentariPolicy;
use App\Policies\ProjectePolicy;
use App\Policies\TicketPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Gate::policy(Client::class, ClientPolicy::class);
        Gate::policy(Projecte::class, ProjectePolicy::class);
        Gate::policy(Ticket::class, TicketPolicy::class);
        Gate::policy(Comentari::class, ComentariPolicy::class);
    }
}
