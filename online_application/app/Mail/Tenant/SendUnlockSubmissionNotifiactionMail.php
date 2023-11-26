<?php

namespace App\Mail\Tenant;

use App\Tenant\Models\Setting;
use Illuminate\Mail\Mailable;

class SendUnlockSubmissionNotifiactionMail extends Mailable
{
    private $settings;

    public function __construct()
    {
        $this->settings = Setting::byGroup();
    }

    public function build()
    {
        if (isset($this->settings['school']['from_email'])) {
            return $this
                ->from($this->settings['school']['from_email'], $this->settings['school']['from_name'])
                ->subject(__('Your Application Form Is Now Open To Edit'))
                ->markdown('back.schools.mail.unlocking_submission');
        }
    }
}
