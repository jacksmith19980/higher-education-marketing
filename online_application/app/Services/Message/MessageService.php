<?php

namespace App\Services\Message;

use App\User;
use Carbon\Carbon;

use App\Tenant\Models\Message;
use App\Tenant\Models\Student;
use App\Tenant\Models\Recipient;
use App\Helpers\School\PaginationHelper;
use App\Events\Tenant\Message\MessageSent;

class MessageService
{

    public static function getAllMessages($user = null ,  $recipient = null  ,$page = 1 )
    {

        if(!$user){
            return collect();
        }

        if($recipient && is_object($recipient))
        {
            return self::getAllRecipientMessages($recipient , $page);
        }
        // get All Messages
        $messages =   Message::where(function($qb) use($user){
            $qb->where('sender_id' , $user->id)
            ->where('sender_type' , get_class($user))
            ->whereNull('parent_id');

        })
        ->orWhere(function($q) use($user){
            $q->whereHas('recipients' , function($qb) use($user){
                $qb->where('recipient_id' , $user->id)
                ->where('recipient_type' , get_class($user));
        })
        ->whereNull('parent_id');
        })->latest('updated_at')
        ->paginate(10);

        return $messages;
    }

    // get All Messages sent or received by recipients
    public static function getAllRecipientMessages($recipient = null)
    {
        if(!$recipient){
            return collect();
        }

         $messages =   Message::where(function($qb) use($recipient){
            $qb->where('sender_id' , $recipient->id)
            ->where('sender_type' , get_class($recipient))
            ->whereNull('parent_id');

        })
        ->orWhere(function($q) use($recipient){
            $q->whereHas('recipients' , function($qb) use($recipient){
                $qb->where('recipient_id' , $recipient->id)
                ->where('recipient_type' , get_class($recipient));
        })
        ->whereNull('parent_id');
        })
        ->latest('updated_at')
        ->paginate(10);

        return $messages;
    }

    /**
     * Get New Messages
     *
     * @param [type] $user
     * @return Message
     */
    public static function getNewMessages($user = null , $order = true)
    {
        if(!$user){
            return collect();
        }
        $main = false;
        return collect();
        
        $messages = Message::whereHas('recipients' , function($q) use($user){
            $q->where('recipient_id', $user->id)
            ->where('recipient_type', get_class($user))
            ->whereNull('is_read');
        })
        ->main($main)
        ->with('replies' , 'recipients')->get();


/*
        $messages = $user->receiveable()->whereNull('is_read')->get()->map(function ($receiveable) use ($main) {
            return $receiveable->message()->main($main)->with('replies' , 'recipients')->get();
        })->flatten();
 */

        if($order){
            return $messages->reverse();
        }
        return $messages;
    }


    /**
     * Store a message or a replay
     *
     * @param [type] $sender
     * @param [type] $recipient
     * @param [type] $request
     * @return Array
     */
    public static function store($sender , $recipient  ,$request)
    {
        $message = new Message();
        $message->subject =  $request->subject;
        $message->body = $request->body;
        $message->sender()->associate($sender);
        $message->parent_id = $request->filled('parent_id') ? $request->parent_id : null;
        $message->updated_at = Carbon::now();

        if($request->filled('parent_id')){
            $parentMessage = Message::find($request->parent_id);
            $parentMessage->updated_at = Carbon::now();
            $parentMessage->save();
        }


        if($message->save()){
            $rec = new Recipient();
            $rec->recipient()->associate($recipient);
            $rec->message()->associate($message);
            $rec->save();
        }

        // Dispatch New Message Created
        event(new MessageSent($message , $rec , $request->attachments));

        return [$message , $rec];
    }

    /**
     * Search Messages By a string
     *
     * @param [type] $searchTerm
     * @param [type] $recipient
     * @param [type] $user
     * @return Collection
     */
    public static function search($searchTerm, $recipient , $user)
    {

        if(!$recipient)
        {
            $recipient = $user;
        }

        $messages = Message::where(function($qb) use($searchTerm){
            $qb->where('body' , 'LIKE' , '%' . $searchTerm . '%')
            ->orWhere('subject' , 'LIKE' , '%' . $searchTerm . '%');
        })
        ->where(function($qb) use ($recipient, $user) {

            $qb->whereHas('recipients' , function($query) use($recipient, $user) {
                $query->where( function($q) use($recipient){
                        $q->where('recipient_id' , $recipient->id)
                        ->where('recipient_Type' , get_class($recipient));
                })
                ->orWhere(function($q) use($user){
                        $q->where('recipient_id' , $user->id)
                        ->where('recipient_Type' , get_class($user));
                });

            })
            ->orWhereHas('sender' , function($query) use($recipient, $user){

                $query->where( function($q) use($recipient){
                    $q->where('sender_id' , $recipient->id)
                    ->where('sender_Type' , get_class($recipient));
                    })
                ->orWhere(function($q) use($user){
                    $q->where('sender_id' , $user->id)
                    ->where('sender_Type' , get_class($user));
                });

            });
        })
        ->latest('updated_at')
        ->paginate(100)
        ->keyBy('id');

        $results = collect();
        foreach ($messages as $message) {
            if($message->parent_id){
                $message = Message::find($message->parent_id);
            }
            $results->put($message->id , $message);
        }
        return PaginationHelper::paginate($results , 100);
    }

    /**
     * Marke Message as read
     *
     * @param Message $message
     * @param [type] $user
     * @return boolean
     */
    public static function read(Message $message, $user)
    {
        // if the owner is reading the message
        $owner = $message->owner;

         $replies = $message->replies()->with(['recipients' => function($query) use ($user){
            $query->where('recipient_id', $user->id)
            ->where('recipient_type', get_class($user))
            ->whereNull('is_read');
        }])->get();


        if(get_class($owner) == get_class($user) && $owner->id == $user->id && !$replies->count())
        {
            return;
        }

        $receivable = $message->recipient($user);

        if($receivable){
            if(!$receivable->is_read){
                $receivable->is_read = Carbon::now();
                $receivable->save();
            }
        }




        foreach($replies as $reply) {
            if($reply->recipients){
                foreach($reply->recipients as $receivable){
                    if(!$receivable->is_read){
                        $receivable->is_read = Carbon::now();
                        $receivable->save();
                    }
                }
            }
        }

        return true;
    }

    /**
     * Check if the message is not Read or has a new replat for a specific user/student/agent
     *
     * @param Message $message
     * @param [type] $recipient
     * @return boolean
     */

    public static function isNew(Message $message , $recipient = null , $owner)
    {

        if(!$recipient){
            return false;
        }

        // check if the recipient didn't read the message
        if(!$recipient->is_read){
            return true;
        }

        // Check if the message has unread replies
        $repliesRecipinets = $message->replies->pluck('recipients');
        foreach ($repliesRecipinets as $replayRecipinets) {
            foreach($replayRecipinets as $replayRecipient)
            {
                if(!$replayRecipient->is_read){
                    return true;
                }
            }
        }
        return false;
    }

    public static function searchRecipients($query , $type = null , $limit = 5)
    {
        $query = explode (" " , $query);

        return Student::
        where(function($q) use ($query){
            foreach ($query as  $value) {
                $q->orWhere('first_name' , 'LIKE', '%' . $value . '%');
            }
        })

        ->orWhere(function($q) use ($query){
            foreach ($query as  $value) {
                $q->orWhere('last_name' , 'LIKE', '%' . $value . '%');
            }
        })

        ->orWhere(function($q) use ($query){
            foreach ($query as  $value) {
                $q->orWhere('email' , 'LIKE', '%' . $value . '%');
            }
        })

        ->take($limit)->get();
    }





}
