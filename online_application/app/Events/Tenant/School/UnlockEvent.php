<?php

namespace App\Events\Tenant\School;

use App\Tenant\Models\Student;
use App\Tenant\Models\Submission;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UnlockEvent
{
    use Dispatchable;
    use SerializesModels;

    public $submission;
    public $student;

    public function __construct(Submission $submission, Student $student)
    {
        $this->submission = $submission;
        $this->student = $student;
    }
}
