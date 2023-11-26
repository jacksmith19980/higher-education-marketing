<?php

namespace App\Listeners\Tenant\Agency;

use App\Events\Tenant\Agency\AgencyApplicationUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AgencyApplicationUpdatedWebhook
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
     * @param  AgencyApplicationUpdated  $event
     * @return void
     */
    public function handle(AgencyApplicationUpdated $event)
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
