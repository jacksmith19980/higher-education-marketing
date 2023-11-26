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
            'attr'          => "onchange=app.getMultipleLessons(this,'instructors',$lesson->id)" . $disabled,
            'data'          => $courses,
            'placeholder'   => 'Select a Course',
            'value'         => $lesson->course->id
        ])
    </div>

    <div class="col-md-6" id="instructorsList">
        @include('back.layouts.core.forms.select',
        [
            'name'          => 'instructor',
            'label'         => 'Instructor',
            'class'         => 'ajax-form-field',
            'required'      => true,
            'data'          => (isset($instructors)) ? $instructors : [],
            'attr'          => (!isset($instructors)) ?? 'disabled',
            'placeholder'   => 'Select an Instructor',
            'value'         => $lesson->instructor->id
        ])
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
                <label for="semester">{{__('Cohors')}}<span class="text-danger">*</span></label>
        </div>
    </div>

    <div class="pr-20 mb-4 text-right col-md-6">

            <button onclick="app.resetSelection(this)" class="mr-3 btn btn-light uncheck-check-button" data-action="uncheck">{{__('Uncheck All')}}</button>


            <button onclick="app.resetSelection(this)" class="btn btn-light exclude-include-button" data-action="exclude" >{{__('Exclude Empty Cohorts')}}</button>


    </div>
    @php
        $lessonGroups = $lesson->groups()->pluck('groups.id' , 'groups.title')->toArray();
    @endphp
    @if(count($groups))
        @foreach($groups as $group)
            <div class="col-md-3 col-sm-12">
                <p class="fomr-control">
                    <input
                        type="checkbox"
                        {{ in_array($group->id , $lessonGroups) ? ' checked' : ''}}
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
            'data'          => $classrooms,
            'attr'          => 'onchange=app.clearSlots(\'.slots\')',
            'placeholder'   => 'Select a Classroom',
            'value'         => $lesson->classroom->id,
        ])
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        @include('back.layouts.core.forms.date-input', [
            'name'      => 'date',
            'label'     => 'Date',
            'class'     => 'ajax-form-field',
            'required'  => true,
            'attr'      => '',
            'value'     => $lesson->date,
            'data'      => ''
        ])
    </div>
    <div class="col-md-6" >
        @include('back.layouts.core.forms.select', [
            'name'          => 'classroom_slot',
            'label'         => 'Classroom Slot',
            'class'         => 'ajax-form-field',
            'required'      => true,

            'data'          => (isset($classroom_slots)) ? $classroom_slots : [],
            'attr'          => (!isset($classroom_slots)) ?? 'disabled',

            'placeholder'   => 'Select a Classroom Slot',
            'value'         => $lesson->classroomSlot->id
        ])
    </div>

</div>
