<?php

namespace App\Actions;

use App\Actions\Action;
use Illuminate\Support\Facades\Redirect;

class EversignSignature extends Action
{
    protected $action;
    protected $application;
    protected $submission;
    protected $student;
    protected $setting;
    protected $school;

    public function __construct(
        $action,
        $application,
        $submission,
        $student,
        $setting,
        $school
    ) {
        $this->action = $action;
        $this->application = $application;
        $this->submission = $submission;
        $this->student = $student;
        $this->setting = $setting;
        $this->school = $school;
    }

    public function handle()
    {
    }
}
