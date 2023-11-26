<?php

namespace App\Rules\School;

use App\Tenant\Models\Instructor;
use Illuminate\Contracts\Validation\Rule;

class InstructorExist implements Rule
{
    protected $attribute;
    protected $instructor;
    protected $exception;

    public function __construct($attribute, $instructor = null, $exception = null)
    {
        $this->exception = $exception;
        $this->attribute = $attribute;
        $this->instructor = $instructor;
    }

    /**
     * {@inheritdoc}
     */
    public function passes($attribute, $value)
    {
        if ($this->exception == $value) {
            return true;
        }

        $instructor = Instructor::where($attribute, $value)->first();

        $update = false;
        if (isset($instructor) && $this->instructor !== null) {
            $update = $instructor->id == $this->instructor->id ? true : false;
        }

        if (! isset($instructor) || $update) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function message()
    {
        return 'This value is already used';
    }
}
