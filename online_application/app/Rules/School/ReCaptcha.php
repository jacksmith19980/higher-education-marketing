<?php

namespace App\Rules\School;

use App\Tenant\Models\Setting;
use Illuminate\Contracts\Validation\Rule;

class ReCaptcha implements Rule
{
    protected $settings;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
        $this->settings = Setting::byGroup();
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
        if (! isset($this->settings['auth']['recaptcha_site_secret']) || $this->settings['auth']['enable_recaptcha'] == 'No') {
            return true;
        }
        if (empty($value)) {
            return false;
        }
        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha_secret = $this->settings['auth']['recaptcha_site_secret'];

        $recaptcha = file_get_contents($recaptcha_url.'?secret='.$recaptcha_secret.'&response='.$value);

        $recaptcha = json_decode($recaptcha);

        if (! $recaptcha->success) {
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
        return 'Something went wrong, Please try again later.';
    }
}
