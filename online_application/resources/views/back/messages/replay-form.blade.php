
<form id="replayContainer" enctype="multipart/form-data">
    @include('back.layouts.core.forms.hidden-input',
        [
        'name'      => 'payload[message]',
        'label'     => '' ,
        'class'     =>'ajax-form-field' ,
        'value'     => $message->id,
        'required'  => true,
        'attr'      => '',
        ])
     @include('back.layouts.core.forms.hidden-input',
        [
        'name'      => 'payload[recipient]',
        'label'     => '' ,
        'class'     => 'ajax-form-field' ,
        'value'     => isset($recipient) ? $recipient->recipient->id : (isset($recipientId) ? $recipientId : null),
        'required'  => true,
        'attr'      => '',
        ])


    <div class="row">
        <div class="col-12">
            @include('back.layouts.core.forms.text-area',
            [
            'name'      => 'payload[body]',
            'label'     => '' ,
            'class'     =>'ajax-form-field replayBody' ,
            'value'     => '',
            'required'  => true,
            'attr'      => '',
            ])
        </div>

        <div class="col-12">
        @include('back.layouts.core.forms.filepond-uploader',
        [
            'name'              => 'payload[attachments][]',
            'label'             => 'Attachments' ,
            'class'             =>'ajax-form-field' ,
            'value'             => '',
            'required'          => false,
            'attr'              => '',
            'multiple'          => true,
            'reorder'           => true,
            'maxSize'           => 10,
            'maxFiles'          => 5,

            'labelIdle'         => 'Drag & Drop your files or <span class=\'filepond--label-action\'> Browse </span><br/><small class=\'font-sm text-info\'>Max 5 files & file\'s size shouldn\'t exceed 10MB</small>',

            'acceptedFileTypes' => json_encode(['.jpeg', '.png', '.pdf', '.jpg', '.bmp' , '.doc' , '.docx' , '.xlsx' , '.csv']),

            'uploadUrl'         => ($front) ? route('school.messages.attachments.upload' , ['school' => $school])  : route('messages.attachments.upload') ,

            'deleteUrl' => ($front) ? route('school.messages.attachments.delete' , ['school' => $school])  : route('messages.attachments.delete'),
        ])
        </div>

        <div class="col-12">
            <button type="button"

                @if(isset($front) && $front)
                    onclick="message.saveDirectMessageReplay(this)"
                @else
                    onclick="app.saveDirectMessageReplay(this)"
                @endif
                id="saveReplay" disabled class="btn btn-primary">
                {{__('Replay')}}
            </button>
        </div>
    </div>
</form>
