<?php

namespace App\Listeners\Tenant\Application;

use App\Events\Tenant\School\UnlockRequestEvent;
use App\Helpers\Application\SubmissionHelpers;

class UnlockRequestSubmissionStatusListener
{
    public function handle(UnlockRequestEvent $event)
    {
        SubmissionHelpers::newSubmissionStatus($event->submission, 'Unlock Requested');
    }
}
