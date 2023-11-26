<div class="row">

        <div class="col-md-12">
            @include('back.layouts.core.forms.select',
                [
                    'name'      => 'properties[dates_type]',
                    'label'     => '' ,
                    'class'     => 'select2' ,
                    'required'  => true,
                    'attr'      => 'onchange=app.getProgramDates(this)',
                    'value'     => '',
                        'data'      => [
                        ''                        => 'Select Start/End Dates Type',  
                        'start-end-dates'         => 'Define Start/End Dates',
                        'start-duration'          => 'Define Start Date & Duration',
                        /* 'start-end-week-day'      => 'Start/End Week\'s Day', */
                    ]
                ]
            )
        </div>

        
    </div> <!-- row -->

    <div class="row loadDateType"></div>