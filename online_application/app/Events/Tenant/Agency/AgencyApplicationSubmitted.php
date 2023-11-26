<?php

namespace App\Events\Tenant\Agency;

use App\School;
use App\Tenant\Models\Agency;
use App\Tenant\Models\AgencySubmission;
use App\Tenant\Models\Application;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AgencyApplicationSubmitted
{
    use Dispatchable, SerializesModels;

    public $submission;
    public $application;
    public $agency;
    public $school;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(AgencySubmission $submission, Application $application, Agency $agency, School $school)
    {
        $this->submission = $submission;
        $this->application = $application;
        $this->agency = $agency;
        $this->school = $school;
    }
}
