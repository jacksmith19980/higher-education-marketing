<?php

namespace App\Events\Tenant;

use App\School;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UsersWereInvited
{
    use Dispatchable;
    use SerializesModels;

    public $school;
    public $users;
    public $isNewSchool;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(School $school, $users_emails, $users_names, $users_roles , $isNewSchool = false)
    {
        $this->school = $school;
        $this->isNewSchool = $isNewSchool;

        $users = [];
        foreach ($users_emails as $key => $email) {
            $users[] = ['email' => $email, 'name' => $users_names[$key], 'role' => $users_roles[$key]];
        }
        $this->users = $users;
    }
}
