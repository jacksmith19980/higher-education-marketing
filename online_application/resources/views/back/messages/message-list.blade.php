@php
    $owner = $message->owner;

    //if(!$currentRecipient = $message->recipient( (isset($applicant)) ? $applicant : $user)){
        //$currentRecipient = $owner;
    //}

    $currentRecipient = $message->recipient( (isset($applicant)) ? $applicant : $user);
    $isNew = DirectMessage::isNew($message , $currentRecipient , $owner);

    $allMessages = isset($allMessages) ? $allMessages : false;

@endphp

@if($currentRecipient->recipient)

<tr
    @if($isNew)
        class="unread"
    @endif
    data-message="{{$message->id}}"
    data-message-id="{{$message->id}}"
>

    <!-- User -->
    <td class="user-name d-flex">
        @if(isset($owner->avatar))
            <span>
                <img src="{{$owner->avatar}}" alt="user" class="mr-3 rounded-circle"  width="30">
            </span>
        @endif
            <span style="padding-top:8px">
                <h6 class="m-t-0">{{$owner->name}}
                    <span class="text-muted">
                        {{$message->replies->count() ? "(" . $message->replies->count() . ")" : ''}}
                    </span>
                    <p class="mt-1 d-block text-muted font-300">
                        {{__('to')}}: {{$currentRecipient->recipient->name}}
                    </p>
                </h6>
            </span>
    </td>


    <!-- Message -->
    <td class="max-texts">
    <a class="link d-flex"
        @if($front)
            href="{{route('school.messages.show' , [
                    'school' => $school ,
                    'message' => $message
            ])}}"
        @else
            href="javascript: void(0)" onclick="app.viewDirectMessage(this,{{($message->parent_id) ? $message->parent_id :  $message->id}},{{$currentRecipient->recipient_id}})"

        @endif
    >
    @if($isNew)
        <span class="label label-danger m-r-10">{{__('Not read')}}</span>
    @endif
    <span class="blue-grey-text text-darken-4">{{$message->subject}} </span>
    <span class="text-muted font-meduim">- {{ Str::limit(strip_tags($message->body), 50, $end='...') }} </span>
    </a>
    @if($currentRecipient->is_read)
        <span class="text-muted" data-toggle="tooltip" data-container="body" data-placement="top" title="{{$currentRecipient->is_read->format('Y-m-d H:i:s')}}" data-original-title="{{$currentRecipient->is_read->format('Y-m-d H:i:s')}}">{{__('Read')}}: {{$currentRecipient->is_read->diffForHumans()}}</span>
    @endif
    </td>
    <!-- Attachment -->

    <td class="clip">
        @if ($message->attachments->count())
            <i class="fa fa-paperclip"></i>
        @endif
    </td>

    <!-- Time -->
    <td class="time">{{$message->created_at->diffForHumans()}} </td>

    <td class="actions">
        @include('back.layouts.core.helpers.table-actions-permissions' , [
            'buttons'=> [
                    'view' => [
                        'text' => 'View',
                        'icon' => 'icon-eye',
                        'attr'  => "onclick=app.viewDirectMessage(this," . (isset($message->parent_id) ? $message->parent_id : $message->id ) . "," . $currentRecipient->recipient_id . ")",
                        'show'  => true,
                        'class' => '',
                    ],
                    'delete' => [
                        'text' => 'Delete',
                        'icon' => 'icon-trash text-danger',
                        'attr' => 'onclick=app.deleteElement("'.route('messages.destroy' , $message).'","","data-message-id")',
                        'show'  => ($front) ? false : true,
                        'class' => '',
                    ],
            ]
        ])
    </td>


</tr>
@endif
