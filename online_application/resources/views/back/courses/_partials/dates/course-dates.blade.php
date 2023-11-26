<form action="{{route('date.store' , $course)}}" method="post">
@csrf
@if (!isset($course->properties['dates_type']))
    <div class="row">
        <div class="col-md-12">
            @include('back.layouts.core.forms.select',
            [
                'name'          => 'properties[dates][dates_type]',
                'label'         => 'Course schedule type' ,
                'class'         => 'ajax-form-field',
                'required'      => false,
                'attr'          => 'disabled',
                'value'         => isset($course->properties['dates_type']) ? $course->properties['dates_type'] : '',
                'data'          => [
                        ''               => 'Select Course Dates',
                        'specific-dates' => 'Specific Dates',
                        'all-year'       => 'All Years'
                    ]
                ])
        </div>
    </div>
@endif
<div class="loadCourseDates">
    @if (isset($course->properties['dates_type']))
        @include('back.courses._partials.dates.dates-template.' . $course->properties['dates_type'] . '.index' , [
            'course' => $course,
            'key'    => Str::random(10)
        ])
    @endif

</div>


</form>