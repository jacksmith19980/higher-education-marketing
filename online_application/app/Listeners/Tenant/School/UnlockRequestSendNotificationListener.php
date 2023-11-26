<?php

namespace App\Listeners\Tenant\School;

use App\Events\Tenant\School\UnlockRequestEvent;
use App\Mail\Tenant\SendUnlockRequestMail;
use App\Tenant\Models\Setting;

class UnlockRequestSendNotificationListener
{
    private $settings;

    public function __construct()
    {
        $this->settings = Setting::byGroup();
    }

    public function handle()
    {
        if (isset($this->settings['school']['to_emails'])) {
            $emails = explode(',', $this->settings['school']['to_emails']);
            if (count($emails) > 0) {
                foreach ($emails as $email) {
                    \Mail::to(trim($email))
                        ->send(new SendUnlockRequestMail());
                }
            }
        }
    }
}
