<div class="row">
    <div class="col-md-12">
        @include('back.layouts.core.forms.select',
        [
            'name'          => 'properties[dates_type_courses]',
            'label'         => 'Course Starting/Ending Dates' ,
            'class'         =>'' ,
            'required'      => false,
            'attr'          => 'onchange=app.getCourseDates(this)',
            'value'         => isset($course->properties['dates_type_courses']) ? $course->properties['dates_type_courses'] : '',
            'data'          => [
                    ''                  => 'Select Course Dates',
                    'specific-dates'    => 'Specific Dates',
                    'single-day'        => 'Single Day',
                    'all-year'          => 'All Years',
                    //'specific-intakes'  => 'Specific Intakes',
                ]
            ])

    </div>
</div>

<div class="loadDateType">
    @if (isset($course->properties['dates_type_courses']))

        @include('back.courses._partials.dates-template.' . $course->properties['dates_type_courses'])
    @endif
</div>
