<div class="col-md-12">
            
        <div class="row repeated_fields">
        <div class="col-md-6">
            <div class="form-group">
                @include('back.layouts.core.forms.select',
                [
                    'name'      => 'start_dates',
                    'label'     => 'Start at' ,
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
                @include('back.layouts.core.forms.select',
                [
                    'name'      => 'end_dates',
                    'label'     => 'End at' ,
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
        
    </div>
</div>