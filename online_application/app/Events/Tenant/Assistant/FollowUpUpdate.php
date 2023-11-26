<?php

namespace App\Events\Tenant\Assistant;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FollowUpUpdate
{
    use Dispatchable, SerializesModels;

    public $type;
    public $request;
    public $status;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($type, $request, $status)
    {
        $this->type = $type;
        $this->request = $request;
        $this->status = $status;
    }
}
