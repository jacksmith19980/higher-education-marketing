<div class="row">
    <div class="col-md-12">
        @include('back.layouts.core.forms.select',
        [
            'name'          => 'properties[dates_type]',
            'label'         => 'Course Starting/Ending Dates' ,
            'class'         =>'' ,
            'required'      => false,
            'attr'          => 'onchange=app.loadCourseDates(this)',
            'value'         => isset($course->properties['dates_type']) ? $course->properties['dates_type'] : '',
            'data'          => [
                    ''               => 'Select Cours Dates',
                    'specific-dates' => 'Specific Dates',
                    'all-year'       => 'All Years'
                ]
            ])
    </div>
</div>
<div class="loadCourseDates">
    @if (isset($course->properties['dates_type']))
        @include('back.courses._partials.dates-template.' . $course->properties['dates_type'])
    @endif
</div>