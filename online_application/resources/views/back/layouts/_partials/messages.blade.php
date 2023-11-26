<li class="nav-item dropdown">

    <a class="nav-link dropdown-toggle waves-effect waves-dark" href="javascript:void(0)" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="position:relative">
        <i class="font-24 mdi mdi-comment-processing">
        @if($newMessages->count())
        <span style="position:absolute;top:-11px;left:29px">
            <i class="font-18 text-danger mdi mdi-checkbox-blank-circle"></i>
        </span>
        @endif
    </i>

    </a>
    <div class="dropdown-menu dropdown-menu-right mailbox" aria-labelledby="2">
        <span class="with-arrow"><span class="bg-danger"></span></span>
        <ul class="list-style-none">
            <li>
                <div class="drop-title text-white bg-danger">
                    <h4 class="m-b-0 m-t-5">{{$newMessages->count()}} {{__('New')}}</h4>
                    <span class="font-light">
                        {{__(Str::plural('Message', $newMessages->count()))}}
                    </span>
                </div>
            </li>
            <li>
                <div class="message-center message-body">
                    @foreach ($newMessages as $newMessage)
                        @include('back.layouts._partials.message-notification' , ['message' => $newMessage])
                    @endforeach
                </div>
            </li>
            <li>
                <a class="nav-link text-center link" href="{{route('messages.index')}}"> <b>{{__('See all messages')}}</b> <i class="fa fa-angle-right"></i> </a>
            </li>
        </ul>
    </div>
</li>
