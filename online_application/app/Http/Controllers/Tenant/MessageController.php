<?php

namespace App\Http\Controllers\Tenant;

use Response;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Facades\DirectMessage;
use App\Tenant\Models\Message;
use App\Tenant\Models\Student;
use App\Tenant\Models\Recipient;
use App\Http\Controllers\Controller;
use App\Tenant\Models\Attachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class MessageController extends Controller
{
    public function index(Request $request)
    {
        $message = $request->filled('message') ? Message::find($request->message) : null;

        $params = [
            'modelName' => Message::getModelName(),
        ];
        $user = Auth::guard('web')->user();
        $messages = DirectMessage::getAllMessages($user , $page = $request->filled('page') ? $request->page : 1);

        return view('back.messages.index' , compact('messages' , 'user' , 'params' , 'message'));
    }


    public function create(Request $request)
    {
        $recipient = $request->recipient;
        return view('back.messages.create' , compact('recipient') );
    }

    public function store(Request $request)
    {

        // get Current User
        $sender = $this->getUser();
        $recipient = $applicant = $this->getRecipient((int) $request->recipient);
        $request->validate(
            [
                'subject'           => 'required',
                'body'              => 'required',
                'recipient'         => 'required',
            ]
        );
        list ($message , $rec) = DirectMessage::store($sender , $recipient  ,$request);

        $front = false;
        $html = view('back.messages.message-list' , compact('applicant' , 'message' , 'front'))->render();

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => [
                    'html'      => $html,
                    'recipient' => $request->recipient,
                    'message'   => "Your message to ". $recipient->name ." was sent successfully!"
            ],
        ]);
    }


    public function show(Message $message , Request $request)
    {
        //
    }


    public function storeReplay($payload)
    {
        if(!$payload['message']){
            return false;
        }
        $message  = Message::find($payload['message']);
        $sender =$this->getUser();
        $recipient =  $this->getRecipient($payload['recipient']);

        $request = new Request([
            'subject'       => null,
            'body'          => $payload['body'],
            'parent_id'     => $message->id,
            'attachments'   => isset($payload['attachments']) ? $payload['attachments'] : null
        ]);

        list ($replay , $rec) = DirectMessage::store($sender , $recipient  ,$request);

        // return All Replies
        $html = view('back.messages.replies' , [
            'replies'   => $message->replies,
            'message'   => $message,
            'recipient' => $message->recipient(),
            'front'     => false
        ])->render();

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => [
                    'html'      => $html
            ],
        ]);
    }


    public function view($payload)
    {
        $user = $this->getUser();
        if(!$payload['message']){
            return false;
        }
        $message  = Message::find($payload['message']);

        $owner = $message->owner;

        $recipient = $message->recipient($payload['recipient']);

        //Mark Message or replies as read
        DirectMessage::read($message , $user);
        $front = false;
        $html = view('back.messages.message' , compact('recipient' , 'message' , 'owner' , 'front'))->render();

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => [
                    'html'      => $html
            ],
        ]);
    }


    public  function search($payload)
    {

        if(Str::contains(request()->server('HTTP_REFERER'), 'submissions/applicants')){
            $recipient = $applicant = $this->getRecipient($payload['recipient']);
        }else{
            $recipient = $applicant = null;
        }

        $user = $this->getUser();

        if($payload['query']){
            $messages = DirectMessage::search( $payload['query'] , $recipient, $user);
        }else{
            $messages = DirectMessage::getAllMessages($user , $recipient);
        }

        $html = view('back.messages.messages-table' , compact('messages' , 'applicant' , 'user'))->render();

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => [
                    'html'      => $html
            ],
        ]);

    }


    public  function findRecipient($payload)
    {
        if($payload['query']){
            $recipients = DirectMessage::searchRecipients($payload['query'] , null);
        }else{
            $recipients = [];
        }
        $html = view('back.messages.recipients-lookup-result' , compact('recipients'))->render();
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

    public function destroy(Message $message ,Request $request)
    {
        $id = $message->id;
        $recipients = $message->recipients;
        foreach ($recipients as $recipient) {
            $recipient->delete();
        }
        if ($response = $message->delete()) {
            return Response::json(
                [
                    'status'   => 200,
                    'response' => 'success',
                    'extra'    => ['removedId' => $id],
                ]
            );
        } else {
            return Response::json(
                [
                    'status'   => 404,
                    'response' => $response,
                ]
            );
        }
    }


    protected function getRecipient($id)
    {
        // TODO Get the recipient based on the sender
        return Student::find($id);

    }


    protected function getUser($id = null)
    {
        return Auth::guard('web')->user();
    }


}
