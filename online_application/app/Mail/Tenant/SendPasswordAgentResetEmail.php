<?php

namespace App\Mail\Tenant;

use App\School;
use App\Tenant\Models\Agent;
use App\Tenant\Models\Setting;
use App\Tenant\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendPasswordAgentResetEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $agent;
    public $token;
    public $school;
    public $settings;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token, Agent $agent)
    {
        $this->settings = Setting::byGroup();
        $this->agent = $agent;
        $this->token = $token;
        $this->school = School::bySlug(request()->school);
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
        ->subject('Your Password Reset Link')->markdown('front.auth.mail.password-reset-agent-email');
    }
}
