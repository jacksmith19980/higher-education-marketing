<?php

namespace App\Rules\School;

use App\User;
use App\Tenant\Models\Agent;
use Illuminate\Contracts\Validation\Rule;

class UniqueUser implements Rule
{
    protected $user;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($user = null)
    {
        $this->user = $user;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $user = User::where( [
                [$attribute , '=', $value],
                [$attribute , '<>' , $this->user->email]
                ])->first();
        return (is_null($user)) ? true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This email is already used';
    }
}
