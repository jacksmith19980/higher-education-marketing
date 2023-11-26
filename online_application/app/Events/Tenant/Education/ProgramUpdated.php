<?php

namespace App\Events\Tenant\Education;

use App\Tenant\Models\Program;
use App\Tenant\Models\Student;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class ProgramUpdated
{
    use Dispatchable, SerializesModels;
    public $program;
    public $dateId;
    public $student;
    public $parent;
    public $courses;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Program $educationable , $dateId , Student $student , $education , $courses = null)
    {
        $this->program  = $educationable;
        $this->dateId   = $dateId;
        $this->student  = $student;
        $this->parent   = $education;
        $this->courses   = $courses;
    }
}
?>
