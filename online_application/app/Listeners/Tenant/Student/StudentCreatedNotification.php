<?php

namespace App\Listeners\Tenant\Student;

use App\Events\Tenant\Student\StudentCreated;
use App\Mail\Tenant\InstructorAccountMail;
use App\Mail\Tenant\StudentAccountMail;
use Mail;

class StudentCreatedNotification
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
     * @param  StudentCreated  $event
     * @return void
     */
    public function handle(StudentCreated $event)
    {
        $data = $event->data;
        if (!$event->sendNotification) {
            return;
        }
        // Send Notification
        Mail::to($data['email'])->send(new StudentAccountMail($data));
    }
}
