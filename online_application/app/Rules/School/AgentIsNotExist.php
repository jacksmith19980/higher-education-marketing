<?php

namespace App\Rules\School;

use App\Tenant\Models\Agent;
use Illuminate\Contracts\Validation\Rule;

class AgentIsNotExist implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $agent = Agent::where('email', $value)->first();

        if (isset($agent->email) && $agent->email == $value) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This email is not exists in our records';
    }
}
