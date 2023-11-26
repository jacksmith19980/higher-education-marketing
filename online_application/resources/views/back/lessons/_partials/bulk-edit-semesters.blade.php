<form method="POST" action="{{ route($route) }}" class="validation-wizard text_input_field">
    @csrf
    @include('back.layouts.core.forms.hidden-input',
    [
        'name'      => 'lessons',
        'label'     => 'Lessons' ,
        'class'     => 'ajax-form-field' ,
        'required'  => true,
        'attr'      => '',
        'value'     => json_encode($lessons)
    ])


    @include('back.layouts.core.forms.hidden-input',
    [
        'name'      => 'action',
        'label'     => 'action' ,
        'class'     => 'ajax-form-field' ,
        'required'  => true,
        'attr'      => '',
        'value'     => $action
    ])

<div class="row">
    <div class="col-12" id="semstersList">
        @include('back.layouts.core.forms.select',
            [
                'name'          => 'semester',
                'label'         => 'Semester' ,
                'class'         => 'ajax-form-field  program-field',
                'required'      => true,
                'attr'          => "",
                'data'          => $semesters,
                'placeholder'   => 'Select a Semester',
                'value'         => null
            ])
    </div>
</div>
</form>
