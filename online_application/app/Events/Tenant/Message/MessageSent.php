<?php
namespace App\Events\Tenant\Message;

use App\Tenant\Models\Message;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class MessageSent
{
    use Dispatchable, SerializesModels;
    public $message;
    public $recipient;
    public $attachments;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Message $message , $recipient , $attachments = false)
    {
        $this->message  = $message;
        $this->recipient  = $recipient;
        $this->attachments  = $attachments;
    }
}
?>
