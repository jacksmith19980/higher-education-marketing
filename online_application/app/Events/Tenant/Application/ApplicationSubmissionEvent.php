<?php

namespace App\Events\Tenant\Application;

use App\Tenant\Models\Application;
use App\Tenant\Models\Student;
use App\Tenant\Models\Submission;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ApplicationSubmissionEvent
{
    use Dispatchable;
    use SerializesModels;

    public $submission;
    public $student;
    public $application;
    public $setting;
    public $school;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Submission $submission, Student $student, Application $application, $setting = null, $school = null)
    {
        $this->submission = $submission;
        $this->student = $student;
        $this->application = $application;

        $this->setting = $setting;
        $this->school = $school;
    }
}
