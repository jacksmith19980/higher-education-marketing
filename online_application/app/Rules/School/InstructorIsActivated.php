<?php

namespace App\Rules\School;

use App\School;
use App\Tenant\Models\Instructor;
use Illuminate\Contracts\Validation\Rule;

class InstructorIsActivated implements Rule
{
    public $school;

    public function __construct()
    {
        $this->school = School::byUuid(session('tenant'))->first();
    }

    /**
     * {@inheritdoc}
     */
    public function passes($attribute, $value)
    {
        return Instructor::where('email', $value)->where('is_active', true)->first();
    }

    /**
     * {@inheritdoc}
     */
    public function message()
    {
        return 'Check your email and password or activate your account,</br />
        <a href="'.route('school.instructor.resend.activation', ['school' => $this->school]).'">Click here to resend activation email</a>';
    }
}
