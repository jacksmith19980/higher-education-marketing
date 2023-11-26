<form method="POST" action="{{ route($route) }}" class="validation-wizard text_input_field">
    @csrf

    <div class="accordion-head bg-info text-white">{{__('Add Multiple Lessons for the same Cohort')}}</div>

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
                    'class'         => 'ajax-form-field',
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
                @include('back.layouts.core.forms.select', [
                    'name'          => 'group',
                    'label'         => 'Cohort' ,
                    'class'         => 'ajax-form-field',
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
                @include('back.layouts.core.forms.select', [
                    'name'          => 'classroom',
                    'label'         => 'Classroom',
                    'class'         => 'ajax-form-field' ,
                    'required'      => false,
                    'attr'          => '',
                    'data'          => $classrooms,
                    'attr'          => 'onchange=app.clearSlots(\'.slots\')',
                    'placeholder'   => 'Select a Classroom',
                    'value'         => ''
                ])
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                @include('back.layouts.core.forms.date-input', [
                    'name'      => 'start_date',
                    'label'     => 'Start Date',
                    'class'     => 'ajax-form-field' ,
                    'required'  => true,
                    'attr'      => '',
                    'value'     => '',
                    'data'      => ''
                ])
            </div>

            <div class="col-md-6">
                @include('back.layouts.core.forms.date-input', [
                    'name'      => 'end_date',
                    'label'     => 'End Date',
                    'class'     => 'ajax-form-field' ,
                    'required'  => true,
                    'attr'      => '',
                    'value'     => '',
                    'data'      => ''
                ])
            </div>
        </div>

        <div class="row default_slots">
            <div class="col-md-5">
                @include('back.layouts.core.forms.select', [
                    'name'          => 'week[0]',
                    'label'         => 'Week day' ,
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
                    'class'         => 'ajax-form-field' ,
                    'required'      => true,
                    'attr'          => '',
                    'data'          => [],
                    'attr'          => '',
                    'placeholder'   => 'Select a Classroom Slot',
                    'value'         => ''
                ])
            </div>

            <div class="col-md-1 action_wrapper">
                <label>&nbsp;</label>
                <div class="form-group">
                    <button type="button" class="btn btn-success btn-block"
                            onclick="app.addElements(this)" data-action="classroomSlot.weekDaySlot" data-container=".slots">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="slots">
        </div>
    </div>
</form>
