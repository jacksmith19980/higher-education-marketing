<?php
namespace App\Listeners\Tenant\Message;

use App\School;
use Illuminate\Support\Facades\Mail;
use App\Events\Tenant\Message\MessageSent;
use App\Mail\Tenant\MessageNotificationEmail;


class SendNotificationEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MessageSent  $event
     * @return void
     */
    public function handle(MessageSent $event)
    {
        $recipient = $event->recipient->recipient;
        $school = School::byUuid(session('tenant'))->first();

        //Send Notification Email
        Mail::to($recipient->email)->send(new MessageNotificationEmail($event->message, $school , $recipient));

    }




}
