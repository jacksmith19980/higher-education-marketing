<?php

namespace App\Listeners\Tenant;

use Hash;
use Mail;
use App\User;
use Illuminate\Support\Str;
use App\Tenant\Traits\ForSystem;
use Spatie\Permission\Models\Role;
use App\Helpers\School\SchoolHelper;
use App\Events\Tenant\UsersWereInvited;
use App\Mail\Tenant\UserInvitationEmail;

class InviteSchoolUsers
{
    use ForSystem;

    /**
     * Handle the event.
     *
     * @param  UsersWhereInvited  $event
     * @return void
     */
    public function handle(UsersWereInvited $event)
    {
        // Create Users
        $users = [];

        //get the school's SuperAdmin Role
        $role = $event->school->roles()->first();

        foreach ($event->users as $value) {
            $email = $value['email'];
            $name = $value['name'];


            $user = User::firstOrCreate(['email' => $email]);
            $data = [
                'name'      => $name,
                'email'     => $user->email,
            ];
            // if new User
            if (!$user->name) {
                $password = SchoolHelper::generatePassword(8);
                $token = Str::random(177);
                $data['password'] = $password;
                $data['token'] = $token;
                $user = $this->createNewUser($user, $data, $event->school->team_id);
            }

            $user->assignRole($role);
            $user->schools()->save($event->school);
            $users[$email] = $data;
        }


        // Send Invitation Email
        Mail::to(array_keys($users))->send(new UserInvitationEmail($data, $event->school));
    }

    /**
     * Create New User
     *
     * @param User $user
     * @param [array] $data
     * @return User $user
     */
    protected function createNewUser(User $user, $data, $team_id)
    {
        $user->name = $data['name'];
        $user->password = Hash::make($data['password']);
        $user->is_active = false;
        $user->team_id = $team_id;
        $user->activation_token = $data['token'];
        $user->save();

        return $user;
    }


}
