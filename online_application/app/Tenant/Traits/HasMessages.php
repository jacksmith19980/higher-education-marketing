<?php
namespace App\Tenant\Traits;

use App\Tenant\Models\Message;
use App\Tenant\Models\Recipient;
use App\Tenant\Traits\ForTenants;

trait HasMessages
{

    public function sentMessages()
    {
        return $this->morphMany(Message::class, 'sender');
    }


    public function receiveable()
    {
        return $this->morphMany(Recipient::class, 'recipient');
    }

    public function receivedMessages($main = false)
    {
        $messages = $this->receiveable->map(function ($receiveable) use ($main) {
            return $receiveable->message()->main()->with('replies', 'recipients' , 'replies.recipients')->get();
        })->flatten();
        return $messages;
    }

    public function newMessages($main = false)
    {
        $messages = $this->receiveable()->whereNull('is_read')->get()->map(function ($receiveable) use ($main) {
            return $receiveable->message()->main()->with('replies' , 'recipients')->get();
        })->flatten();
        return $messages;
    }

}
