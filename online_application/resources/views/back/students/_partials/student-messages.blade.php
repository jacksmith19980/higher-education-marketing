<div class="p-4 tab-pane fade l-psuedu-border bg-grey-1" id="nav-messages" role="tabpanel" aria-labelledby="pills-messages-tab">
    <div id="directMessages">
    <div class="row">
        <div class="text-right m-b-20 col-12">
            <div class="d-flex justify-content-between">

                <div>
                    <a id="back_to_inbox"
                        class="btn btn-outline-secondary font-18 m-r-10" href="javascript:void(0)"
                        onclick="app.refreshMessagesList(this,{{$applicant->id}})"

                        >
                        <i class="icon-refresh"></i>
                    </a>
                </div>

                <div class="d-flex w-50">
                    @include('back.messages.search-form' , [
                        'attr'   => 'onkeydown=app.searchMessages(this,'.$applicant->id.')',
                    ])
                    <button type="button"
                    class="btn btn-primary"
                    onclick="app.sendDirectMessage('{{route('messages.create' , ['recipient' => $applicant->id])}}','{{json_encode(['button'=>'Send','cancelButton'=>'Cancel'])}}' , '{{__('Send Message')}}' , this)">
                        <i class="fa fa-plus"></i> {{__('Send Message')}}
                    </button>
                </div>
            </div>
        </div>
    </div>

    @include('back.messages.messages-table' , [
        'messages'   => $messages,
    ])
    </div>

    <div id="MessageDetails" style="display: none;"></div>


</div>
