<?php

namespace App\Mail\Tenant;

use App\School;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class SendUnlockRequestMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $student;

    public function __construct()
    {
        $this->student = Auth::guard('student')->user();
    }

    public function build()
    {
        return $this->subject(__('Unlocking request submission'))
            ->markdown('front.school.mail.unlocking_request_submission')->with(['student' => $this->student]);
    }
}
