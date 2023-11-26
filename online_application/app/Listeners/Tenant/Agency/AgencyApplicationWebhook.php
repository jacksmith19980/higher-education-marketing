<?php

namespace App\Listeners\Tenant\Agency;

use App\Events\Tenant\Agency\AgencyApplicationSubmitted;
use App\Tenant\Traits\Integratable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AgencyApplicationWebhook
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
     * @param  AgencyApplicationSubmitted  $event
     * @return void
     */
    public function handle(AgencyApplicationSubmitted $event)
    {
        if ($integrations = $event->application->integrations) {
            foreach ($integrations as $integration) {
                $integrationClass = 'App\\Jobs\\Tenant\\Integrations\\Agency\\Integrate'.ucwords($integration->type);

                dispatch(
                    new $integrationClass(
                        $event->application,
                        $event->submission,
                        $event->agency,
                        $integration,
                        'submitAgencyApplication'
                    )
                );
            }
        }
    }
}
