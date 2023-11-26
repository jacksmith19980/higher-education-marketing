<?php
namespace App\Listeners\Tenant\Message;


use App\Tenant\Models\Attachment;
use App\Events\Tenant\Message\MessageSent;


class SaveAttachments
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
        $attachments = $event->attachments;
        $message = $event->message;

        if(!$attachments)
        {
            return;
        }

        foreach($attachments as $name => $att) {
            $att = json_decode($att, true);
            $attachment = new Attachment();
            $attachment->name = $att['fileName'];
            $attachment->url = $att['url'];
            $attachment->object_id = $message->id;
            $attachment->object_type = get_class($message);
            $attachment->save();
        }

    }

}
