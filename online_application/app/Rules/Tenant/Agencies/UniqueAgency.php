<?php

namespace App\Rules\Tenant\Agencies;

use App\Tenant\Models\Agency;
use Illuminate\Contracts\Validation\Rule;

class UniqueAgency implements Rule
{
    protected $attribute;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($attribute = null)
    {
        $this->attribute = $attribute;
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
        if ($this->attribute) {
            $attribute = $this->attribute;
        }

        $agency = Agency::byNameOrEmail($attribute, $value)->first();

        if (isset($agency)) {
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
        return 'This agency already exists';
    }
}
