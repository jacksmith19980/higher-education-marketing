<div class="row">
    <div class="col-md-6">
        @include('back.layouts.core.forms.multi-select',
            [
            'name' => 'properties[allowed][]',
            'label' => 'Allowed files' ,
            'class' =>'ajax-form-field select2' ,
            'required' => false,
            'attr' => '',
            'data' => [
                'jpg' => 'jpg',
                'jpeg' => 'jpeg',
                'png' => 'png',
                'doc' => 'doc',
                'pdf' => 'pdf',
                'docx' => 'docx',
            ],
            'value' =>  'pdf'
            ])
    </div>
</div>
