<?php

namespace App\Events\Tenant\Parent;

use App\School;
use App\Tenant\Models\Student;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class ParentRegistred
{
    use Dispatchable, SerializesModels;

    public $parent;
    public $school;
    public $contactType;
    public $request;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Student $parent, $school, $contactType, Request $request)
    {
        $this->parent = $parent;
        $this->school = $school;
        $this->contactType = $contactType;
        $this->request = $request;
    }
}
