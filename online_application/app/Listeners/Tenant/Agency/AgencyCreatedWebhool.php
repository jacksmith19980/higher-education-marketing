<?php

namespace App\Listeners\Tenant\Agency;

use App\Events\Tenant\Agency\AgencyIsCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AgencyCreatedWebhool
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AgencyIsCreated  $event
     * @return void
     */
    public function handle(AgencyIsCreated $event)
    {
        //
    }
}
