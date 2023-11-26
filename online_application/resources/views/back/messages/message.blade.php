<div class="mail-details bg-light w-100" style="padding:0 15px;">
<div class="card-body bg-light" style="padding-left:0">

    <a id="back_to_inbox" class="btn btn-outline-secondary font-18 m-r-10"

        @if(isset($front) && $front)
            href="{{route('school.messages.index', ['school'=>$school])}}"
        @else
            href="javascript:void(0)"
            onclick=app.showDirectMessagesList(this,{{$recipient->recipient_id}})
        @endif
    ><i class="mdi mdi-arrow-left"></i></a>
</div>

<div class="card-body border-bottom">
    <h4 class="m-b-0">{{$message->subject}}</h4>
</div>

    @include('back.messages.message-block' , [
            'message'   => $message,
            'owner'     => $owner
    ])

    <div id="messageReplies">
        @include('back.messages.replies',
        [
            'replies'   => $message->replies,
            'recipient' => $recipient,
            'front'     => $front

        ])


    </div>



</div>
