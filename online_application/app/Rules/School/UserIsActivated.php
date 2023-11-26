<?php

namespace App\Rules\School;

use App\User;
use Illuminate\Contracts\Validation\Rule;

class UserIsActivated implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $school;

    public function __construct()
    {
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
        return User::where([
            'email'             => $value,
            'is_active'         =>  true,
            'activation_token'  => null,
            ])->first();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Check your email and password or activate your account,</br />
        <a href="#">Click here to resend activation email</a>';
    }
}
