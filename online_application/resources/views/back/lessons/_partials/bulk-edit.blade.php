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

    <div class="row">
        <div class="col-md-6">
            @include('back.layouts.core.forms.select',
            [
                'name'          => 'program',
                'label'         => 'Program' ,
                'class'         => 'ajax-form-field  program-field',
                'required'      => false,
                'attr'          => 'onchange=app.courseModulesGroup(this)',
                'data'          => $programs,
                'placeholder'   => 'Select a Program',
                'value'         => ''
            ])
        </div>

        <div class="col-md-6 courses"></div>

        <div class="col-md-12 semester_group_radio"></div>

        <div class="col-md-6 semesters"></div>

        <div class="col-md-6 groups"></div>

        <div class="col-md-6 instructors"></div>
    </div>

    <div class="row">
        <div class="col-md-6">
            @include('back.layouts.core.forms.select', [
                'name'          => 'classroom',
                'label'         => 'Classroom',
                'class'         => 'ajax-form-field',
                'required'      => false,
                'attr'          => '',
                'data'          => $classrooms,
                'attr'          => 'onchange=app.clearSlots(\'.slots\')',
                'placeholder'   => 'Select a Classroom',
                'value'         => ''
            ])
        </div>
    </div>
    <div class="row default_slots">
        <div class="col-md-6">
            @include('back.layouts.core.forms.select', [
                'name'          => 'week[0]',
                'label'         => 'Week day',
                'class'         => 'ajax-form-field',
                'required'      => true,
                'attr'          => 'onchange=app.searchMultiSlots(this)',
                'placeholder'   => 'Select a week day',
                'value'         => '',
                'data'          => [
                    'Monday'        => 'Monday',
                    'Tuesday'       => 'Tuesday',
                    'Wednesday'     => 'Wednesday',
                    'Thursday'      => 'Thursday',
                    'Friday'        => 'Friday',
                    'Saturday'      => 'Saturday',
                    'Sunday'        => 'Sunday'
                ]
            ])
        </div>

        <div class="col-md-6">
            @include('back.layouts.core.forms.select', [
                'name'          => 'classroom_slot[0]',
                'label'         => 'Classroom Slot',
                'class'         => 'ajax-form-field',
                'required'      => true,
                'attr'          => '',
                'data'          => [],
                'attr'          => '',
                'placeholder'   => 'Select a Classroom Slot',
                'value'         => ''
            ])
        </div>


    </div>

        <div class="slots">
        </div>
</form>
