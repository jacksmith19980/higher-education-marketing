@extends('back.layouts.core.helpers.table' , [
'show_buttons' => false,
'title' => 'Messages',
])
@section('table-content')
    <div id="directMessages">
        <div class="row">
            <div class="text-right m-b-20 col-12">
                <div class="d-flex justify-content-between">

                    <div>
                        <a id="back_to_inbox"
                            class="btn btn-outline-secondary font-18 m-r-10" href="javascript:void(0)"
                            onclick="app.refreshMessagesList(this,null)"
                            >
                            <i class="icon-refresh"></i>
                        </a>
                    </div>

                    <div class="d-flex w-50">
                        @include('back.messages.search-form' , [
                            'attr'   => 'onkeydown=app.searchMessages(this,null)',
                        ])
                        <button type="button"
                        class="btn btn-primary"
                        onclick="app.sendDirectMessage('{{route('messages.create' , ['recipient' =>  null ])}}' , '{{json_encode(['button'=>'Send','cancelButton'=>'Cancel'])}}' , '{{__('Send Message')}}' , this)">
                            <i class="fa fa-plus"></i> {{__('Send Message')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @include('back.messages.messages-table' , [
                'messages'      => $messages ,
                'user'          => Auth::guard('web')->user(),
                'allMessages'   => true
                ])
    </div>
    <div id="MessageDetails">
    </div>
@endsection

@section('scripts')
    @if($message)
        <script type="text/javascript">
            app.viewDirectMessage('',{{$message->id}}, {{$message->recipient()->recipient_id}})
        </script>
    @endif
@endsection
