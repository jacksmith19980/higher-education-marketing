<?php

namespace App\Mail\Tenant;

use App\Helpers\Assistant\AssistantHelpers;
use App\Tenant\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Token;

class AssistantEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $assistantBuilder;
    public $data;
    public $assistant;
    public $settings;
    public $subject;
    public $content = null;

    public function __construct($data, $assistantBuilder, $assistant)
    {
        $this->settings = Setting::byGroup();
        $this->assistantBuilder = $assistantBuilder;
        $this->data = $data;
        $this->assistant = $assistant;

        $this->subject = isset($assistantBuilder->properties['thank_you_subject']) ? $assistantBuilder->properties['thank_you_subject'] : $this->assistantBuilder->title;

        $map = [
            'TITLE'             => $data['title'],
            'FIRST_NAME'        => $data['first_name'],
            'LAST_NAME'         => $data['last_name'],
            'EMAIL'             => $data['email'],
            'ASSISTANT_DETAILS'   => AssistantHelpers::getAssistantDetails($assistant->properties),
            'APPLY_BUTTON'    => $this->getApplyButton($assistant),
        ];

        if (isset($assistantBuilder->properties['thank_you_email'])) {
            $this->content = Token::replace($map, $assistantBuilder->properties['thank_you_email']);
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $props = $this->assistantBuilder->properties;

        $sender_name = isset($props['thank_you_sender_name']) ? $props['thank_you_sender_name'] : $this->settings['school']['from_name'];

        $sender_email = isset($props['thank_you_sender_email']) ? $props['thank_you_sender_email'] : $this->settings['school']['from_email'];

        return $this
            ->from($sender_email, $sender_name)
            ->subject($this->subject)
            ->markdown('front.recruitment_assistant.mail.assistant-email');
    }

    /**
     * Construct Assistant Button
     *
     * @param Assistant $assistant
     * @return string
     */
    protected function getApplyButton($assistant)
    {
        /*dd(route('assistants.recuperate.email', ['school' => request('school'),
            'assistant' => $assistant->id,
            'user' => $assistant->user_id]));*/
        return '<a style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;border-radius:3px;color:#fff;display:inline-block;text-decoration:none;background-color:#3097d1;border-top:10px solid #3097d1;border-right:18px solid #3097d1;border-bottom:10px solid #3097d1;border-left:18px solid #3097d1" href="'.route('assistants.recuperate.email', [
            'school' => request('school'),
            'assistant' => $assistant->id,
            'user' => $assistant->user_id,
        ]).'">Apply Now</a>';
    }
}
