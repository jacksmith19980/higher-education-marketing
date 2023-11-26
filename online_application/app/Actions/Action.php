<?php

namespace App\Actions;

use App\Tenant\Models\Application;
use App\Tenant\Models\ApplicationAction;
use App\Tenant\Models\Student;
use App\Tenant\Models\Submission;
use App\Tenant\Traits\Integratable;

class Action
{
    use Integratable;

    protected $action;

    protected $application;

    protected $submission;

    protected $student;

    protected $settings;

    protected $integration;

    public function __construct(ApplicationAction $action, Application $application, Submission $submission, Student $student, $settings)
    {
        $this->action = $action;

        $this->application = $application;

        $this->submission = $submission;

        $this->student = $student;

        $this->settings = $settings;

        $this->integration = $this->inetgration();

        /*@todo Check if Action is one time action Only*/

        // Run Action

        //$this->run();
    }
}
