<?php

namespace App\Events\Tenant\Student;

use App\School;
use App\Tenant\Models\Student;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChildAccountCreated
{
    use Dispatchable, SerializesModels;

    public $child;
    public $school;
    public $contactType;
    public $booking;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Student $child, School $school, $contactType, $booking)
    {
        $this->child = $child;
        $this->school = $school;
        $this->contactType = $contactType;
        $this->booking = $booking;
    }
}
