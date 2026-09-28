<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\DepotAssigned;

class UpdateDepotStatus
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DepotAssigned $event)
    {
        // Changer le statut du dépôt à actif
        $event->depot->statut = 'actif';
        $event->depot->save();
    }
}
