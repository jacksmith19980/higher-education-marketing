<?php

namespace App\Policies;

use App\User;
use App\Tenant\Models\Message;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function view($user, Message $message)
    {


        return false;
    }
}
