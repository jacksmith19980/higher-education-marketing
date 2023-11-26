<div class="col-md-4">
        @include('back.layouts.core.forms.date-input',
    [
        'name'          => 'properties[start_date][]',
        'label'         => 'Start date' ,
        'class'         => '' ,
        'required'      => false,
        'attr'          => '',
        'value'         => '',
        'data'          => ''
        ])
    </div>
    
    <div class="col-md-4">
        @include('back.layouts.core.forms.date-input',
    [
        'name'          => 'properties[end_date][]',
        'label'         => 'End date' ,
        'class'         => '' ,
        'required'      => false,
        'attr'          => '',
        'value'         => '',
        'data'          => ''
        ])
    </div>

     <div class="col-md-3">
         @include('back.layouts.core.forms.text-input',
        [
            'name'          => 'properties[date_price][]',
            'label'         => 'Price' ,
            'class'         => '' ,
            'required'      => false,
            'attr'          => '',
            'value'         => '',
            'data'          => '',
            'hint_after'    => 'CAD',
            ])
        </div>

    <div class="col-md-1 action_wrapper">
        <div class="form-group m-t-27">
                        <button class="btn btn-danger" type="button" onclick="">
                            <i class="fa fa-minus"></i>
                        </button>
        </div>
    </div>