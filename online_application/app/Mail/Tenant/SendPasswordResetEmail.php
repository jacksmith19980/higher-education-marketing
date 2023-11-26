<?php

namespace App\Mail\Tenant;

use App\School;
use App\Tenant\Models\Setting;
use App\Tenant\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendPasswordResetEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $token;
    public $school;
    public $settings;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token, Student $student)
    {
        $this->settings = Setting::byGroup();
        $this->student = $student;
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
        $locale = \Config::get('app.locale');
        $markdown = ($locale == 'en') ? 'front.auth.mail.password-reset-email' : 'front.auth.mail.password-reset-email-'.$locale;
        $subject = __('Your Password Reset Link');

        return $this
        ->from($this->settings['school']['from_email'], $this->settings['school']['from_name'])
        ->subject($subject)
        ->markdown($markdown);
    }
}
