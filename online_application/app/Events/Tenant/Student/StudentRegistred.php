<?php

namespace App\Events\Tenant\Student;

use App\School;
use App\Tenant\Models\Student;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StudentRegistred
{
    use Dispatchable, SerializesModels;

    public $student;
    public $school;
    public $contactType;
    public $request;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Student $student, $school = null, $contactType = 'Applicant')
    {
        $this->student = $student;
        $this->school = $school;
        $this->contactType = $contactType;
        $this->request = request();
    }
}
