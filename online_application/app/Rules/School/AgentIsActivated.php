<?php

namespace App\Rules\School;

use App\School;
use App\Tenant\Models\Agent;
use Illuminate\Contracts\Validation\Rule;

class AgentIsActivated implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $school;

    public function __construct()
    {
        $this->school = School::byUuid(session('tenant'))->first();
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
        return Agent::where('email', $value)->where('active', true)->first();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Check your email and password or activate your account,</br />
        <a href="'.route('school.agent.resend.activation', ['school' => $this->school]).'">Click here to resend activation email</a>';
    }
}
