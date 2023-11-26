<?php

namespace App\Listeners\Tenant\Application;

use App\Events\Tenant\Application\ApplicationSubmissionApproved;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RunApplicationApprovedIntegrations
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
     * @param  ApplicationSubmissionApproved  $event
     * @return void
     */
    public function handle(ApplicationSubmissionApproved $event)
    {
        $application = $event->application;
        $submission = $event->submission;
        $student = $event->student;
        $setting = $event->setting;

        if ($integrations = $application->integrations) {
            foreach ($integrations as $integration) {
                $integrationClass = 'App\\Jobs\\Tenant\\Integrations\\Integrate'.ucwords($integration->type);
                $response = dispatch(new $integrationClass($application, $submission, $student, $integration, 'submissionApproved', $setting));
            }
        }
    }
}
