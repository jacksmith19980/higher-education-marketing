<div class="card-body border-bottom">
    <div class="d-flex justify-content-between">
        <div class="d-flex no-block align-items-center m-b-20">
            <div class="m-r-10"><img src="{{$owner->avatar}}" alt="user" class="rounded-circle" width="45"></div>
            <div class="">
                <h5 class="font-medium m-b-0 font-16">{{$owner->name}}</h5>
                <span>to {{$recipient->recipentDetails->name}}</span>
            </div>
        </div>



    </div>

    {!! $message->body !!}

    <hr />

    <div>
        <p class="text-muted" style="margin-bottom:0">
        <strong>{{(__('Sent:'))}}</strong>
        <span class="text-muted" data-toggle="tooltip" data-container="body" data-placement="top" title="{{$message->created_at->format('Y-m-d H:i:s')}}" data-original-title="{{$message->created_at->format('Y-m-d H:i:s')}}">    {{$message->created_at->diffForHumans()}}
        </span>

        @if($recipient->is_read)
                <strong>| {{(__('Read:'))}}</strong>
                <span class="text-muted" data-toggle="tooltip" data-container="body" data-placement="top" title="{{$recipient->is_read->format('Y-m-d H:i:s')}}" data-original-title="{{$recipient->is_read->format('Y-m-d H:i:s')}}"> {{$recipient->is_read->diffForHumans()}}
                </span>
        @endif

        </p>
    </div>

</div>
@php
    $attachments = $message->attachments
@endphp
@if($attachments->count())
    <div class="card-body">
        <h6 class="text-muted"><i class="fa fa-paperclip m-r-10 m-b-10"></i> {{Str::plural('Attachment' , $attachments->count())}} <span>({{$attachments->count()}})</span></h6>
        <div class="row">
            @foreach ($attachments as $attachment)
                @include('back.messages.message-attachment' , [
                    'attachment' => $attachment
                ])
            @endforeach
        </div>

    </div>
@endif
