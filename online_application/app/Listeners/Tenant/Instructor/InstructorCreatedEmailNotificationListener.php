<?php

namespace App\Listeners\Tenant\Instructor;

use App\Events\Tenant\Instructor\InstructorCreated;
use App\Mail\Tenant\InstructorAccountMail;
use Mail;

class InstructorCreatedEmailNotificationListener
{
    public function __construct()
    {
        //
    }

    public function handle(InstructorCreated $event)
    {
        $data = $event->data;

        if (! $event->sendNotification) {
            return;
        }

        // Send Notification
        Mail::to($data['email'])->send(new InstructorAccountMail($data));
    }
}
