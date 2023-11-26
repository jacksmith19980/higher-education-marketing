<?php

namespace App\Listeners\Tenant\Application;

use App\Events\Tenant\Application\ApplicationSubmissionEvent;
use App\Events\Tenant\Application\ApplicationSubmissionUpdated;
use App\Tenant\Models\SubmissionStatus;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateSubmissionStatus
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
        $status = SubmissionStatus::firstOrCreate(
            [
                'submission_id' => $event->submission->id,
                'student_id' => $student->id,
                'status' => $event->submission->status,
            ]
        );

        if ($status) {
            $status->step = isset($event->submission->properties['step']) ? $event->submission->properties['step'] : 1;
            $status->properties = [
                'submitted_by'  => 'Student',
                'name'          => $student->name,
                'submitted_at'  => Carbon::now(),
            ];
            $status->save();
        }
    }
}
