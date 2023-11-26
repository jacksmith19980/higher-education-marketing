<?php

namespace App\Listeners\Tenant\Application;

use App\Events\Tenant\Application\ApplicationSubmissionEvent;
use App\Tenant\Models\SubmissionStatus;
use Carbon\Carbon;

class InitiatSubmissionStatus
{
    /**
     * Handle the event.
     *
     * @param  ApplicationSubmissionEvent  $event
     * @return void
     */
    public function handle(ApplicationSubmissionEvent $event)
    {
        $student = $event->student;
        $status = SubmissionStatus::create([
            'submission_id' => $event->submission->id,
            'student_id' => $student->id,
            'status' => 'Account Created',
            'step' => 1,
            'properties' => [
                'submitted_by'  => 'Student',
                'name'          => $student->name,
                'submitted_at'  => Carbon::now(),
            ],
        ]);
    }
}
