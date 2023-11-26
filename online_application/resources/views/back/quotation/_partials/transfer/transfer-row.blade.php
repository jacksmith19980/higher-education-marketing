<div class="col-md-6">
    <div class="form-group">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'properties[transfer_options][]',
            'label'     => 'Option' ,
            'class'     =>'' ,
            'required'  => true,
            'attr'      => '',
            'value'     => ( isset($option) ) ? $option : '',
            'data'      => ''
        ])
    </div>
</div>

<div class="col-md-5">
    <div class="form-group">
            @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'properties[transfer_options_price][]',
            'label'     => 'Cost' ,
            'class'     =>'with-currency' ,
            'required'  => true,
            'attr'      => '',
            'value'     => (isset($price)) ? $price : '' ,
            'data'      => '',
            'hint_after'=> isset($settings['school']['default_currency'])? $settings['school']['default_currency'] : 'CAD'
        ])
    </div>
</div>

<div class="col-md-1 action_wrapper">
    <div class="form-group m-t-27">
            <button class="btn btn-danger btn-larg" type="button" onclick="">
                <i class="fa fa-minus"></i>
            </button>
    </div>
</div>