<form method="POST" id="sendMessageFrom" action="{{route('messages.store')}}" enctype="multipart/form-data">
<div class="row">
    <div class="col-12">
        @include('back.layouts.core.forms.text-input',
            [
            'name' => 'subject',
            'label' => 'Subject' ,
            'class' => 'ajax-form-field' ,
            'required' => true,
            'attr' => '',
            'value' => ''
        ])
    </div>
    @if(!$recipient)
        <div class="col-12">
            @include('back.layouts.core.forms.look-up',
                [
                'name'          => 'recipient',
                'label'         => 'To:' ,
                'class'         => 'indent' ,
                'required'      => true,
                'placeholder'   => 'Find a recipient',
                'attr'          => '',
                'action'        => 'message.findRecipient',
                'data'          => [
                    'limit' => 5
                ],
                'value'         => ''
            ])
        </div>
    @else
        @include('back.layouts.core.forms.hidden-input',
            [
            'name' => 'recipient',
            'label' => 'Recipient' ,
            'class' => 'ajax-form-field' ,
            'required' => true,
            'attr' => '',
            'value' => $recipient

            ])
    @endif
    <div class="col-12">
        @include('back.layouts.core.forms.text-area',
        [
        'name'      => 'body',
        'label'     => 'Message' ,
        'class'     =>'ajax-form-field' ,
        'value'     => '',
        'required'  => true,
        'attr'      => ''
        ])
    </div>
    <div class="col-12">
        @include('back.layouts.core.forms.filepond-uploader',
        [
        'name'      => 'attachments[]',
        'label'     => 'Attachments' ,
        'class'     =>'ajax-form-field' ,
        'value'     => '',
        'required'  => false,
        'attr'      => '',
        'multiple'  => true,
        'reorder'   => true,
        'maxSize'   => 10,
        'maxFiles'  => 5,

        'labelIdle'         => 'Drag & Drop your files or <span class=\'filepond--label-action\'> Browse </span><br/><small class=\'font-sm text-info\'>Max 5 files & file\'s size shouldn\'t exceed 10MB</small>',


        'uploadUrl' => route('messages.attachments.upload'),
        'deleteUrl' => route('messages.attachments.delete'),
        ])
    </div>
</div>

</form>
