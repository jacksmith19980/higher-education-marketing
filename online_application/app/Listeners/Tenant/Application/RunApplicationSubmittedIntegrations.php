<?php

namespace App\Listeners\Tenant\Application;

use App\Events\Tenant\Application\ApplicationSubmissionEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RunApplicationSubmittedIntegrations
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
     * @param  ApplicationSubmissionEvent  $event
     * @return void
     */
    public function handle(ApplicationSubmissionEvent $event)
    {
        $application = $event->application;
        $submission = $event->submission;
        $student = $event->student;
        $setting = $event->setting;

        if ($integrations = $application->integrations) {
            foreach ($integrations as $integration) {
                $integrationClass = 'App\\Jobs\\Tenant\\Integrations\\Integrate'.ucwords($integration->type);
                $response = dispatch(new $integrationClass($application, $submission, $student, $integration, 'submitApplication', $setting));
            }
        }
    }
}
