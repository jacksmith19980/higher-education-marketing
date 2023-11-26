<?php

namespace App\Mail\Tenant;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Tenant\Models\Setting;
use App\Tenant\Models\Submission;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApplicationSubmittedTYEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $submission;
    protected $content;
    protected $settings;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Submission $submission, $content)
    {
        $this->submission = $submission;
        $this->content = $content;
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


        return $this
        ->subject('Thank you for your application')
        ->view('back.emails.application-submitted-ty', ['submission' => $this->submission, 'content' => $this->content])
        ->from($formEmail, $formName);
    }
}
