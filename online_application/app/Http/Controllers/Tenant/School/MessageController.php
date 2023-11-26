<?php

namespace App\Http\Controllers\Tenant\School;

use App\School;
use Illuminate\Http\Request;
use App\Facades\DirectMessage;
use App\Tenant\Models\Message;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class MessageController extends Controller
{
    public function index(School $School, Request $Request)
    {
        $student = Auth::guard('student')->user();
        $messages = $student->receivedMessages()->reverse();
        return view('front.messages.index' , compact('messages' , 'student'));
    }


    public function show(School $School, Message $message  , Request $Request)
    {

        $student = Auth::guard('student')->user();

        //Mark Message or replies as read
        DirectMessage::read($message , $student);

        $owner = $message->owner;
        $recipient = $message->recipient($student->id);
        return view('front.messages.message' , compact('message' , 'recipient' , 'owner' , 'student'));
    }

    public function storeReplay($request)
    {

        $request->validate(
            [
                'payload.body'  => 'required',
            ]
        );
        $OriginalMessage = Message::find($request->payload['message']);
        $recipient = $OriginalMessage->owner;
        $sender = Auth::guard('student')->user();

        $request = new Request([
            'body'          => $request->payload['body'],
            'subject'       => null,
            'parent_id'     => $OriginalMessage->id,
            'attachments'   => isset($request->payload['attachments']) ? $request->payload['attachments'] : null
        ]);

        // Creare a new message
        list ($message , $rec) = DirectMessage::store($sender , $recipient  ,$request);

        $html = view('back.messages.replies' , [
            'replies'       => $OriginalMessage->replies,
            'recipientId'   => $recipient->id,
            'message'       => $OriginalMessage,
            'front'         => true
        ])->render();

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => [
                    'html'      => $html
            ],
        ]);
    }

    public function uploadAttachment(Request $request)
    {

        app('debugbar')->disable();
        $uploads = [];
        if($request->hasFile('attachments') || $request->hasFile('payload.attachments')){
            if(!$files = $request->file('attachments')){
                $files = $request->file('payload.attachments');
            }
            foreach ($files as $file) {
                $fileName = $file->getClientOriginalName();
                $url = Storage::putFile( '/'.session('tenant') .'/attachments/messages' , $file);
                $uploads = [
                    'url'        => $url,
                    'fileName'   => $fileName,
                ];
            }
        }
        return Response::json($uploads);
    }

    public function deleteAttachment(Request $request)
    {
        if($request->filled('url')){
             if(Storage::exists($request->url)){
                Storage::delete($request->url);
                return Response::json([
                    'success'   => true,
                    'response'  => 'success',
                    'deleteFile' => $request->all()
                ]);
            }else{
                return Response::json([
                    'success'   => false,
                    'response'  => 'failed',
                ],500);
            }
            // Delete attachments
            $attachment= Attachment::whereName($request->fileName)->first();
            if($attachment){
                $attachment->delete();
            }
        }
        return Response::json([
            'success'   => false,
            'response'  => 'failed',
        ],500);


    }

    public  function search(Request $request)
    {
        // Search for All message Sent FROM/To this Student
        $recipient = $applicant = $user  = $this->getUser();

        if($request->payload['query']){
            $messages = DirectMessage::search( $request->payload['query'] , $recipient, $user )->reverse();

        }else{
            $messages = $applicant->receivedMessages()->reverse();
        }
        $front = true;
        $html = view('back.messages.messages-table' , compact('messages' , 'applicant' , 'front'))->render();

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => [
                    'html'      => $html
            ],
        ]);
    }

    protected function getUser($id = null)
    {
        return Auth::guard('student')->user();
    }

}

?>
