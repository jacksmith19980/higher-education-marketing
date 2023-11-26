<?php

namespace App\Listeners\Tenant\Student;

use Auth;
use Illuminate\Support\Arr;
use App\Tenant\Models\Setting;
use App\Tenant\Traits\Integratable;
use App\Events\Tenant\Student\StudentCreated;
use App\Helpers\Application\SubmissionHelpers;

class StudentCreatedHistory
{
    use Integratable;

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
        $submision = $event->

        $status = SubmissionHelpers::newSubmissionStatus($submission , 'Account Creates', null, $user);


    }
}
