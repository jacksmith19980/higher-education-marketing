<?php

namespace App\Events\Tenant\Education;

use App\Tenant\Models\Semester;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class SemesterCreated
{
    use Dispatchable, SerializesModels;
    public $semester;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Semester $semester)
    {
        $this->semester  = $semester;

    }
}

?>
