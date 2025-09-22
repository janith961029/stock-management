<?php

namespace App\Providers;

use App\Models\Establishment;
use App\Models\Items;
use App\Policies\EstablishmentPolicy;
use App\Policies\ItemsPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
  
    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        /**
         * Optional custom Gates
         * උදා: user එක Admin role එකේද බලන්න
         */
       
    }
}
