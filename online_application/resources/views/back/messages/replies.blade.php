
@if($message->replies->count())
    @foreach ($replies as $replay)

        @include('back.messages.message-block' , [
            'message'       => $replay,
            'recipient'     => $replay->recipient(),
            'owner'         => $replay->owner
        ])
    @endforeach
@endif
@include('back.messages.replay-form')
