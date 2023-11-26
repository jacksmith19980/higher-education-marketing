<div class="col-md-12">
            
        <div class="row repeated_fields">
        <div class="col-md-6">
            <div class="form-group">
                @include('back.layouts.core.forms.select',
                [
                    'name'      => 'properties[program_start]',
                    'label'     => 'Starts Every' ,
                    'class'     =>'' ,
                    'required'  => true,
                    'attr'      => '',
                    'value'     => '',
                    'data'      => [
                        'saturday'  => 'Staurday',   
                        'sunday'    => 'Sunday',   
                        'monday'    => 'Monday',   
                        'tuesday'   => 'Tuesday',   
                        'wednesday' => 'Wednesday',   
                        'thursday'  => 'Thursday',   
                        'friday'    => 'Friday',   
                    ]
                ])
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                    @include('back.layouts.core.forms.text-input',
                [
                    'name'          => 'properties[program_duration]',
                    'label'         => 'Duration' ,
                    'class'         => '' ,
                    'required'      => true,
                    'attr'          => '',
                    'value'         => '',
                    'data'          => '',
                    'hint_after'    => 'Weeks'
                ])
            </div>
        </div>
    </div>
</div>