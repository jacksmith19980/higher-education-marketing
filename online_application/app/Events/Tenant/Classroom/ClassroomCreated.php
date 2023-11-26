<?php

namespace App\Events\Tenant\Classroom;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClassroomCreated
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    private $classroom;
    private $slots;

    public function __construct($classroom, $slots)
    {
        $this->classroom = $classroom;
        $this->slots = $slots;
    }

    public function getSlots()
    {
        return $this->slots;
    }

    public function getClassroom()
    {
        return $this->classroom;
    }
}
