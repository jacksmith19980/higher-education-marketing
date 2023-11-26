<?php

namespace App\Rules\School;

use App\Tenant\Models\Program;
use Illuminate\Contracts\Validation\Rule;

class UniqueProgram implements Rule
{
    protected $attribute;
    protected $program;

    public function __construct($attribute, $program = null)
    {
        $this->attribute = $attribute;
        $this->program = $program;
    }

    /**
     * {@inheritdoc}
     */
    public function passes($attribute, $value)
    {
        $update = false;
        if ($this->attribute) {
            $attribute = $this->attribute;
        }

        $course = Program::where($attribute, $value)->first();

        if (isset($course) && $this->program !== null) {
            $update = $course->id == $this->program->id ? true : false;
        }

        if (! isset($course) || $update) {
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
        return 'This program code is already exist';
    }
}
