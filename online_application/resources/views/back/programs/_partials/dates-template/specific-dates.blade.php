<div class="row">
    <div class="col-md-2 offset-10 m-b-30">
        @include('back.layouts.core.helpers.add-elements-button' , [
            'text'          => 'Add Dates',
            'action'        => 'course.addSpecificDates',
            'container'     => '#specific_dates_wrapper'
        ])
    </div>
</div>

<div class="row" id="specific_dates_wrapper">


    @if (isset($program->properties['start_date']) && isset($program->properties['end_date']) && !empty($program->properties['start_date']))
        @foreach ($program->properties['start_date'] as $order => $startDate )

            @php
                $dateDetails[$order] = [
                    'start_date'    => $startDate,
                    'end_date'      => $program->properties['end_date'][$order],
                    'date_schudel'  => isset($program->properties['date_schudel'][$order]) ?  $program->properties['date_schudel'][$order]: null,
                    'date_campus'  => isset($program->properties['date_campus'][$order]) ? $program->properties['date_campus'][$order] : null,
                    'date_price'  => isset($program->properties['date_price'][$order]) ? $program->properties['date_price'][$order] : null,
                ];
            @endphp

            @include('back.programs._partials.dates-template.start-end-dates' , ['dateDetails'=> $dateDetails , 'order' => $order ] )
        @endforeach
    @endif
</div>
