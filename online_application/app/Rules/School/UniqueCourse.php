<?php

namespace App\Rules\School;

use App\Tenant\Models\Course;
use Illuminate\Contracts\Validation\Rule;

class UniqueCourse implements Rule
{
    protected $attribute;
    protected $course;

    public function __construct($attribute, $course = null)
    {
        $this->attribute = $attribute;
        $this->course = $course;
    }

    /**
     * {@inheritdoc}
     */
    public function passes($attribute, $value)
    {
        if ($this->attribute) {
            $attribute = $this->attribute;
        }

        $course = Course::where($attribute, $value)->first();

        if (isset($course) && $this->course !== null) {
            $update = $course->id == $this->course->id ? true : false;
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
        return 'This course slug already exist';
    }
}
