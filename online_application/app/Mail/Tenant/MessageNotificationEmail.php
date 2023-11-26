<?php

namespace App\Mail\Tenant;

use App\School;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Tenant\Models\Message;
use App\Tenant\Models\Setting;
use Illuminate\Queue\SerializesModels;

class MessageNotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $message;
    public $school;
    public $recipient;
    public $settings;
    public $route;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Message $message, School $school , $recipient)
    {
        $this->message = $message;
        $this->school = $school;
        $this->recipient = $recipient;
        $this->settings = Setting::byGroup();
        $this->route  = $this->getReadRoute($recipient,$message);
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

        return $this->subject(_('You\'ve got a new message!'))->markdown('back.emails.messages.notification-email')->from($formEmail, $formName);
    }

    protected function getReadRoute($recipient,$message)
    {
        if(get_class($recipient)== 'App\User'){

            return route('applicants.show' , ['school' => $this->school , 'student'  => $message->owner]);
        }
        return route('school.messages.show' , ['school' => $this->school , 'message'  => $message]);
    }
}
