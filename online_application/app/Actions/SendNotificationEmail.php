<?php

namespace App\Actions;

use App\Actions\Action;
use App\Http\Controllers\Tenant\PDFController;
use App\Mail\Tenant\ApplicationSubmittedMail;
use Mail;

class SendNotificationEmail extends Action
{
    /**
     * Run Application Action
     *
     * @return bool
     */
    public function handle()
    {
        $to = array_filter(explode(',', str_replace(' ', ',', $this->action->properties['send_to'])));

        if (isset($this->action->properties['attach_pdf'])) {
            $attachment = app(PDFController::class)->pdf($this->submission, 'email');
        } else {
            $attachment = null;
        }

        Mail::to($to)
        ->send(new ApplicationSubmittedMail($this->submission, $this->action->properties['email'], $attachment));
    }
}
