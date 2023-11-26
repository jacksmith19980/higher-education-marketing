<?php

namespace App\Events\Tenant;

use App\School;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class TenantDeleted
{
    use Dispatchable, SerializesModels;

    public $school;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(School $school)
    {
        $this->school = $school;
    }
}
