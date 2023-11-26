<?php

namespace App\Events\Tenant\Student;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StudentCreated
{
    use Dispatchable, SerializesModels;

    public $student;
    public $sendNotification;
    public $contactType;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($student, $sendNotification, $data, $contactType = 'Applicant')
    {
        $this->student = $student;
        $this->sendNotification = $sendNotification;
        $this->data = $data;
        $this->contactType = $contactType;
    }
}
