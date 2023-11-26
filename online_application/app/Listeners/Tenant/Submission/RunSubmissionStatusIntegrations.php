<?php

namespace App\Listeners\Tenant\Submission;

use App\Tenant\Traits\Integratable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\Tenant\Submission\SubmissionStatusChanged;

class RunSubmissionStatusIntegrations
{
    use  Integratable;
    /**
     * Handle the event.
     *
     * @param  SubmissionStatusChanged  $event
     * @return void
     */
    public function handle(SubmissionStatusChanged $event)
    {
        if ($integration = $this->inetgration()) {
            $response =  $integration->addToStage($event->submission->student, $event->submissionStatus->status);
        }
    }
}
