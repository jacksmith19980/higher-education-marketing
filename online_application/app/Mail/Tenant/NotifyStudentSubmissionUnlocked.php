<?php

namespace App\Mail\Tenant;

use App\School;
use App\Tenant\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyStudentSubmissionUnlocked extends Mailable
{
    use Queueable, SerializesModels;

    private $student;
    private $school;

    public function __construct(Student $student, School $school)
    {
        $this->student = $student;
        $this->school = $school;
    }

    public function build()
    {
        $settings = $this->settings;

        $formName = isset($settings['school']['from_name']) ? $settings['school']['from_name'] : 'Higher Education Marketing';

        $formEmail = isset($settings['school']['from_email']) ? $settings['school']['from_email'] : 'no-replay@higher-education-marketing.com';

        return $this->subject('Submission Unlocked')
            ->markdown('front.student.mail.submission_unlocked')
            ->from($formEmail, $formName);
    }
}
