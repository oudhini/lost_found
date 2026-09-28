<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Events\DepotAssigned;
use App\Listeners\UpdateDepotStatus;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }
    protected $listen = [
        DepotAssigned::class => [
        UpdateDepotStatus::class,
        ],
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
