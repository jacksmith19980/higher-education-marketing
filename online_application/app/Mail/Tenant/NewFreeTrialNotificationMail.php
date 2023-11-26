<?php

namespace App\Mail\Tenant;

use Illuminate\Mail\Mailable;
use App\Tenant\Models\Setting;
use Illuminate\Queue\SerializesModels;

class NewFreeTrialNotificationMail extends Mailable
{
    use  SerializesModels;

    public $data;
    public $user;
    public $school;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $user, $school)
    {
        $this->data = $data;
        $this->user = $user;
        $this->school = $school;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from('ptaza@higher-education-marketing.com', 'Philippe Taza')
            ->subject(__('New Free Trial Account'))
            ->view('back.emails.free-trial-notification');
    }
}
