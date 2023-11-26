<?php

namespace App\Rules\School;

use Illuminate\Contracts\Validation\Rule;

class PromocodeType implements Rule
{
    private $type;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $type)
    {
        $this->type = $type;
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
        switch ($this->type) {
            case 'percentage':
                return $value <= 100;
                break;
            case 'flat':
                return $value < 10000;
                break;
            default:
                return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        switch ($this->type) {
            case 'percentage':
                return "This {$this->type} value most be between 1 - 100";
            case 'flat':
                return "This {$this->type} value most be less than 10000";
            default:
                return 'Wrong value';
        }
    }
}
