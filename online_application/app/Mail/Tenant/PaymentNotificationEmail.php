<?php

namespace App\Mail\Tenant;

use Illuminate\Mail\Mailable;
use App\Tenant\Models\Setting;
use Illuminate\Queue\SerializesModels;

class PaymentNotificationEmail extends Mailable
{
    use  SerializesModels;

    public $settings;
    public $response;
    public $student;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($response , $student)
    {
        $this->response = $response;
        $this->student = $student;
        $this->settings = Setting::byGroup();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from($this->settings['school']['from_email'], $this->settings['school']['from_name'])
            ->subject(__('New Payment'))
            ->view('back.emails.payment-notification');

    }
}
