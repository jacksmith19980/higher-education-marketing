<a href="{{route('school.messages.show' , ['school' => $school ,
    'message' =>   ($message->parent) ? $message->parent: $message
])}}" class="message-item">
    <span class="user-img"> <img src="{{$message->owner->avatar}}"
        alt="user" class="rounded-circle">
            <span class="profile-status online pull-right"></span>
    </span>
    <div class="mail-contnet">
        <h5 class="message-title">{{$message->owner->name}}</h5>
        <span class="mail-desc">
            {{  ($message->parent) ? $message->parent->subject : $message->subject}}
        </span>
        <span class="time">{{$message->created_at->diffForHumans()}}</span> </div>
</a>
