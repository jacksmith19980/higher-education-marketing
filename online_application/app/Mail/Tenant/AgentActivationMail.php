<?php

namespace App\Mail\Tenant;

use App\School;
use App\Tenant\Models\Agent;
use App\Tenant\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AgentActivationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $agent;
    public $school;
    public $settings;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Agent $agent, School $school)
    {
        $this->agent = $agent;
        $this->school = $school;
        $this->settings = $settings = Setting::byGroup();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $settings = $this->settings;

        $formName = isset($settings['school']['from_name']) ? $settings['school']['from_name'] : 'Higher Education Marketing';

        $formEmail = isset($settings['school']['from_email']) ? $settings['school']['from_email'] : 'no-replay@higher-education-marketing.com';

        return $this->subject('Activate your Account')->markdown('front.agent.mail.agent-activation')->from($formEmail, $formName);
    }
}
