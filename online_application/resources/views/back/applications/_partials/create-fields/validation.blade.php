 @include('back.layouts.core.forms.hidden-input',
        [
            'name'      => 'validation',
            'label'     => '' ,
            'class'     => 'ajax-form-field' ,
            'required'  => false,
            'attr'      => '',
            'data'      => [],
            'value'     => ''
        ])


<div class="row">

    <div class="col-md-6">
        @include('back.layouts.core.forms.select',
        [
            'name'      => 'validation_rules_select',
            'label'     => 'Validation Rule' ,
            'class'     => '' ,
            'required'  => false,
            'attr'      => 'onchange=app.addValidationRule(this)',
            'data'      => [
                    0               => 'No validation rule selected',
                    'required'      => 'Is Required',
                    'email'         => 'Is Valid Email',
                    'maxlength'     => 'Maximum Length',
                    'minlength'     => 'Minimum Length',
            ],
            'value'     => ''
        ])
    </div>
</div>

<div class="ValidationRules">

        @if (isset($field->properties['validation']))
            @foreach ( $field->properties['validation'] as $type => $message)
                @include('back.applications._partials.validation.'.$type , [
                            'message' => $message ,
                            'type' => $type
                ])
            @endforeach
        @endif
</div>
