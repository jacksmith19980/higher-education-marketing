<?php

namespace App\Rules\School;

use App\Tenant\Models\Agent;
use Illuminate\Contracts\Validation\Rule;

class AgentExist implements Rule
{
    protected $exception;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($exception = null)
    {
        $this->exception = $exception;
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
        // check if value equals exception
        if ($this->exception == $value) {
            return true;
        }

        $agent = Agent::where('email', $value)->first();

        if (isset($agent->email) && $agent->email == $value) {
            return false;
        }

        return true;
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
