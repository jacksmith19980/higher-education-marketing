<?php

namespace App\Listeners\Tenant\School;

use App\Events\Tenant\School\UnlockEvent;
use App\Mail\Tenant\SendUnlockSubmissionNotifiactionMail;

class UnlockSendNotificationListener
{
    public function handle(UnlockEvent $event)
    {
        \Mail::to($event->student->email)
            ->send(new SendUnlockSubmissionNotifiactionMail());
    }
}
