<?php

namespace App\Mail\Tenant;

use App\School;
use App\Tenant\Models\Instructor;
use App\Tenant\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendPasswordInstructorResetEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $instructor;
    public $token;
    public $school;
    public $settings;

    public function __construct($token, Instructor $instructor)
    {
        $this->settings = Setting::byGroup();
        $this->instructor = $instructor;
        $this->token = $token;
        $this->school = School::bySlug(request()->school);
    }

    public function build()
    {
        return $this
            ->from($this->settings['school']['from_email'], $this->settings['school']['from_name'])
            ->subject('Your Password Reset Link')->markdown('front.auth.mail.password-reset-instructor-email');
    }
}
