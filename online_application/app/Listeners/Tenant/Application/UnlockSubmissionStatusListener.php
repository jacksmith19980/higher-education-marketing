<?php

namespace App\Listeners\Tenant\Application;

use App\Events\Tenant\School\UnlockEvent;
use App\Helpers\Application\SubmissionHelpers;

class UnlockSubmissionStatusListener
{
    public function handle(UnlockEvent $event)
    {
        SubmissionHelpers::newSubmissionStatus($event->submission, 'Unlocked', null, auth()->user());
    }
}
