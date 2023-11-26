<div class="row">
    <div class="col-md-12">
        @include('back.layouts.core.forms.select',
        [
            'name'          => 'properties[dates_type]',
            'label'         => 'Program Starting/Ending Dates' ,
            'class'         =>'' ,
            'required'      => false,
            'attr'          => 'onchange=app.getProgramDates(this)',
            'value'         => isset($program->properties['dates_type']) ? $program->properties['dates_type'] : '',
            'data'          => [
                    ''                  => 'Select Program Dates',
                    'specific-dates'    => 'Specific Dates',
                    'specific-intakes'  => 'Specific Intakes',
                    'all-year'          => 'All Years',
                ]
            ])


    </div>
</div>
<div class="loadDateType">

    @if (isset($program->properties['dates_type']))

        @include('back.programs._partials.dates-template.' . $program->properties['dates_type'])
    @endif
</div>
