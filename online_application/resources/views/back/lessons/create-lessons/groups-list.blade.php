<div class="row">
    @php
        $disabled = (count($courses) == 0) ? " disabled" : " ";
    @endphp
    <div class="col-md-6">
        @include('back.layouts.core.forms.select',
        [
            'name'          => 'course',
            'label'         => 'Course',
            'class'         => 'ajax-form-field',
            'required'      => true,
            'attr'          => "onchange=app.getMultipleLessons(this,'instructors',null)" . $disabled,
            'data'          => $courses,
            'placeholder'   => 'Select a Course',
            'value'         => ''
        ])
    </div>

    <div class="col-md-6" id="instructorsList">
        @include('back.layouts.core.forms.select',
        [
            'name'          => 'instructor',
            'label'         => 'Instructor',
            'class'         => 'ajax-form-field',
            'required'      => true,
            'data'          => [],
            'attr'          => 'disabled',
            'placeholder'   => 'Select an Instructor',
            'value'         => ''
        ])
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-6">
        <div class="form-group">
                <label for="semester">{{__('Cohors')}}<span class="text-danger">*</span></label>
        </div>
    </div>

    <div class="col-md-6 text-right pr-20 mb-4">

            <button onclick="app.resetSelection(this)" class="btn btn-light mr-3 uncheck-check-button" data-action="uncheck">{{__('Uncheck All')}}</button>


            <button onclick="app.resetSelection(this)" class="btn btn-light exclude-include-button" data-action="exclude" >{{__('Exclude Empty Cohorts')}}</button>


    </div>
    @if(count($groups))
        @foreach($groups as $group)
            <div class="col-md-3 col-sm-12">
                <p class="fomr-control">
                    <input
                        type="checkbox"
                        checked
                        id="{{$group->title}}"
                        name="groups[]"
                        value="{{$group->id}}"
                        data-count="{{$group->students_count}}"
                        /> <label for="{{$group->title}}">
                    {{$group->title}}
                    <small title="Students Count">
                        ({{$group->students_count}} {{ Str::plural('student', $group->students_count)}})
                    </small>
                    </label>
                </p>
            </div>
        @endforeach



    @else
        <div class="col-12">
            <div class="alert alert-warning">
                {{__('There are cohorts assigned to this program, Please select another program or add a new cohort')}}
            </div>
        </div>
    @endif
</div>


    <div class="row">
        <div class="col-md-6">
            @include('back.layouts.core.forms.select',
            [
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

     <div class="row">

        <div class="col-md-6">
            @include('back.layouts.core.forms.date-input', [
                'name'      => 'start_date',
                'label'     => 'Start Date',
                'class'     => 'ajax-form-field',
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
                'class'     => 'ajax-form-field',
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

        <div class="col-md-6" >
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

<script>
    $(".select2").select2();
    app.dateTimePicker();
</script>
