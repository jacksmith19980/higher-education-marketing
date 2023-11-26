<form method="POST" action="{{ route($route) }}" class="validation-wizard text_input_field">
    @csrf
    @include('back.layouts.core.forms.hidden-input',
    [
        'name'      => 'submissions',
        'label'     => 'submissions' ,
        'class'     => 'ajax-form-field' ,
        'required'  => true,
        'attr'      => '',
        'value'     => json_encode($submissions)
    ])

    <div class="row">
            <div class="col-md-6">
                @include('back.layouts.core.forms.select',
                [
                    'name'          => 'status',
                    'label'         => 'Status',
                    'class'         => 'ajax-form-field' ,
                    'required'      => false,
                    'attr'          => '',
                    'placeholder'   => __('Select Status'),
                    'data'          => $statuses,
                    'value'         => ''
                ])
            </div>

            <div class="col-md-6">
                @include('back.layouts.core.forms.select',
                [
                    'name'          => 'stage',
                    'label'         => 'Stage',
                    'class'         => 'ajax-form-field' ,
                    'required'      => false,
                    'attr'          => '',
                    'placeholder'   => __('Select Stage'),
                    'data'          => $stages,
                    'value'         => ''
                ])
            </div>

        </div>

</form>
