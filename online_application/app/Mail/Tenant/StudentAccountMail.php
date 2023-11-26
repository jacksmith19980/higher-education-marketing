<?php

namespace App\Mail\Tenant;

use App;
use App\School;
use App\Tenant\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StudentAccountMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $settings;
    public $school;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->settings = Setting::byGroup();
        $this->data = $data;
        $this->school = School::bySlug(request()->school);
        App::setLocale($this->data['language']);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $view = ($this->data['language'] == 'en') ? 'front.agent.mail.student-created-email.en' : 'front.agent.mail.student-created-email.'.$this->data['language'];

        $subject = __('Your Online Application is ready!');

        return $this
        ->from($this->settings['school']['from_email'], $this->settings['school']['from_name'])
        ->subject($subject)
        ->view($view);
    }
}
