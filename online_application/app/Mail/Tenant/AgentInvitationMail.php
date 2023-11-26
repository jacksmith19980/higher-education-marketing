<?php

namespace App\Mail\Tenant;

use App\School;
use App\Tenant\Models\Agency;
use App\Tenant\Models\Agent;
use App\Tenant\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AgentInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $agent;
    public $agency;
    public $school;
    public $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Agent $agent, Agency $agency, School $school, $password)
    {
        $this->agent = $agent;
        $this->agency = $agency;
        $this->school = $school;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */

    //
    public function build()
    {
        $settings = Setting::byGroup();

        $formName = isset($settings['school']['from_name']) ? $settings['school']['from_name'] : 'Higher Education Marketing';

        $formEmail = isset($settings['school']['from_email']) ? $settings['school']['from_email'] : 'no-replay@higher-education-marketing.com';

        try {
            return $this->markdown('front.agent.mail.agent-invitation')->from($formEmail, $formName);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
