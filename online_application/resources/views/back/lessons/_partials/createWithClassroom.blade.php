<form method="POST" action="{{ route($route) }}" class="validation-wizard text_input_field">
    @csrf

    <div class="accordion-head bg-info text-white">{{__('Add Lesson')}}</div>

    <div class="accordion-content accordion-active">

        <div class="row">
            <div class="col-md-6">
                @include('back.layouts.core.forms.select',
                [
                    'name'          => 'program',
                    'label'         => 'Program' ,
                    'class'         => 'ajax-form-field' ,
                    'required'      => true,
                    'attr'          => 'onchange=app.courseModulesGroup(this)',
                    'data'          => $programs,
                    'placeholder'   => 'Select a Program',
                    'value'         => ''
                ])
            </div>

            <div class="col-md-6 courses"></div>

{{--            <div class="col-md-6 modules"></div>--}}

            <div class="col-md-6 groups"></div>

            <div class="col-md-6 instructors"></div>
        </div>

        <div class="row">
            <div class="col-md-6">
                @include('back.layouts.core.forms.select',
                [
                    'name'          => 'classroom',
                    'label'         => 'Classroom' ,
                    'class'         => 'ajax-form-field' ,
                    'required'      => false,
                    'attr'          => '',
                    'data'          => $classrooms,
                    'attr'          => 'disabled',
                    'placeholder'   => 'Select a Classroom',
                    'value'         => $classroom->id
                ])

            </div>

            <div class="col-md-6">
                @include('back.layouts.core.forms.date-input',
                [
                'name'  => 'date',
                'label' => 'Date' ,
                'class' => 'ajax-form-field',
                'required' => true,
                'attr' => 'onchange=app.searchAvailableSlots(this)',
                'value' => '',
                'data' => ''
                ])
            </div>
            <div class="col-md-6 slots-available"></div>
        </div>

    </div>
</form>
