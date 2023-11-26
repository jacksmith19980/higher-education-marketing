<?php

namespace App\Mail\Tenant;

use App\School;
use App\Tenant\Models\Setting;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserInvitationEmail extends Mailable
{
    use SerializesModels;

    public $data;
    public $school;
    public $settings;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data, School $school)
    {
        $this->data = $data;
        $this->school = $school;

        if(!is_object($school) && !$school instanceof School){
            $this->school = School::bySlug(request()->school);
        }

        $this->settings = empty(session('settings-'.session('tenant'))) ? Setting::byGroup() : session('settings-'.session('tenant')) ;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $fromEmail = isset($this->settings['school']['from_email']) ? $this->settings['school']['from_email'] : 'info@higher-education-marketing.com';

        $fromName = isset($this->settings['school']['from_name']) ? $this->settings['school']['from_name'] : 'HEM-SP';

        return $this
            ->subject('You are Invitied to Join HEM-SP')
            ->from(
                $fromEmail,
                $fromName
                )->markdown('back.emails.school.user-invitation-email');
    }
}
