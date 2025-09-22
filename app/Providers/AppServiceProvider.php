<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire; // Add this line

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
         $this->loadRoutesFrom(base_path('routes/api.php'));
        // Set Livewire update route for admin in a subfolder
//        Livewire::setUpdateRoute('/stroop/public/admin/livewire/update');
    }
}
