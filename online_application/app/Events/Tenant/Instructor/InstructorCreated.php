<?php

namespace App\Events\Tenant\Instructor;

use App\Tenant\Models\Instructor;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InstructorCreated
{
    use Dispatchable;
    use SerializesModels;

    public $instructor;
    public $data;
    public $sendNotification;
    public $contactType;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Instructor $instructor, $sendNotification, $data, $contactType = 'Applicant')
    {
        $this->instructor = $instructor;
        $this->sendNotification = $sendNotification;
        $this->data = $data;
        $this->contactType = $contactType;
    }
}
