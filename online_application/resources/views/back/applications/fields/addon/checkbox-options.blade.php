<div class="row" data-repeated="{{$order}}">
    <div class="col-md-3">

        @include('back.layouts.core.forms.text-input',
        [
            'name'          => 'custom_data['.$order.'][label]',
            'label'         => null ,
            'placeholder'   => 'Label' ,
            'class'         =>'ajax-form-field' ,
            'required'      => true,
            'attr'          => '',
            'value'         => isset($label) ? $label : ''
        ])
    </div>

    <div class="col-md-3">
         @include('back.layouts.core.forms.text-input',
        [
            'name'              => 'custom_data['.$order.'][value]',
            'label'             => false,
            'placeholder'       => 'Value' ,
            'class'             =>'ajax-form-field' ,
            'required'          => true,
            'attr'              => '',
            'value'             => isset($value) ? $value : ''
        ])
    </div>

    <div class="col-md-2">
        @include('back.layouts.core.forms.text-input',
       [
           'name'              => 'custom_data['.$order.'][price]',
           'label'             => false,
           'placeholder'       => 'Price' ,
           'class'             =>'ajax-form-field' ,
           'required'          => true,
           'attr'              => '',
           'value'             => isset($price) ? $price : ''
       ])
    </div>

    <div class="col-md-3">
        @include('back.layouts.core.forms.select',
        [
            'name'      => 'custom_data['.$order.'][selected]',
            'label'     => false,
            'class'     => 'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'data'      =>  ['no_selected' => 'No Selected' , 'selected' => 'Selected'] ,
            'value'     =>  isset($selected) ? $selected : ''
        ])
    </div>

    @if (!isset($showmore) || !$showmore)
    <div class="col-md-1 action_wrapper">
        <div class="form-group">
            <button class="btn btn-danger" type="button" onclick="app.removeRepeatedElement(this , {{$order}})">
                    <i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    @else

    <div class="col-md-1 action_wrapper">
            <div class="form-group action_button">
                <button class="btn btn-success" type="button" onclick="app.repeatElement('field.addon' ,'repeated_fields_wrapper' )"><i class="ti ti-plus"></i></button>
            </div>
    </div>

    @endif

</div>
