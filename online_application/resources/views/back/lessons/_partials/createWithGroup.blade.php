<form method="POST" action="{{ route($route) }}" class="validation-wizard text_input_field">
    @csrf

    <div class="accordion-head bg-info text-white">{{__('Add Lesson')}}</div>

    <div class="accordion-content accordion-active">

        <div class="row">
            <div class="col-md-6">
                @php
                    $disabled = $program_courses == null ? 'disabled' : '';
                @endphp
                @include('back.layouts.core.forms.select',
                [
                    'name'          => 'course',
                    'label'         => 'Course' ,
                    'class'         => 'ajax-form-field' ,
                    'required'      => true,
                    'attr'          => 'onchange=app.courseInstructors(this) ' . $disabled,
                    'data'          => $program_courses == null ? $courses : $program_courses,
                    'placeholder'   => 'Select a Course',
                    'value'         => $program_courses == null ? $course->id : ''
                ])
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 groups">
                @include('back.layouts.core.forms.select',
                [
                    'name'          => 'group',
                    'label'         => 'Cohort' ,
                    'class'         => 'ajax-form-field' ,
                    'required'      => true,
                    'attr'          => '',
                    'data'          => $groups,
                    'attr'          => 'disabled',
                    'placeholder'   => 'Select a Cohort',
                    'value'         => $group->id
                ])
            </div>

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
                    'attr'          => 'onchange=app.searchAvailableSlots(this)',
                    'placeholder'   => 'Select a Classroom',
                    'value'         => ''
                ])

            </div>

            <div class="col-md-6">
                @include('back.layouts.core.forms.date-input',
                [
                'name'  => 'date',
                'label' => 'Date' ,
                'class' => 'ajax-form-field' ,
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
