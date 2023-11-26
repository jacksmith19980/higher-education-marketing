<?php

namespace App\Mail\Tenant;

use App\Http\Controllers\Tenant\PDFController;
use App\School;
use App\Tenant\Models\Setting;
use App\Tenant\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicationSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $submission;
    protected $content;
    protected $attachment;
    public $settings;
    public $student;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Submission $submission, $content, $attachment)
    {
        $this->submission = $submission;
        $this->content = $content;
        $this->attachment = $attachment;
        $this->school = School::bySlug(request()->school);
        $this->settings = Setting::byGroup();
        $this->student = $submission->student;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'Application Submitted - '.$this->student->first_name.' '.$this->student->last_name;

        return $this
        ->subject($subject)
        ->from($this->settings['school']['from_email'], $this->settings['school']['from_name'])
        ->view('back.emails.application-submitted', ['submission' => $this->submission, 'content' => $this->content, 'attachment' => $this->attachment]);
    }
}
