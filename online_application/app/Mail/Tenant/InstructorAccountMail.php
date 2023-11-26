<?php

namespace App\Mail\Tenant;

use App\School;
use App\Tenant\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class InstructorAccountMail extends Mailable
{
    use Queueable;
    use SerializesModels;

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

        $this->data['language'] = isset($this->data['language']) ? $this->data['language'] : 'en';
        App::setLocale($this->data['language']);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $view = ($this->data['language'] == 'en') ? 'front.instructor.mail.instructor-created-email.en' : 'front.agent.mail.instructor-created-email.'.$this->data['language'];

        $subject = __('Account created!');

        return $this
            ->from($this->settings['school']['from_email'], $this->settings['school']['from_name'])
            ->subject($subject)
            ->view($view);
    }
}
