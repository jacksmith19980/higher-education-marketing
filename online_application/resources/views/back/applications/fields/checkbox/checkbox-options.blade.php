<div class="row" data-repeated="{{$order}}">
    <div class="col-md-6">

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

    <div class="col-md-5">
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
                <button class="btn btn-success" type="button" onclick="app.repeatElement('field.checkbox' ,'repeated_fields_wrapper' )"><i class="ti ti-plus"></i></button>
            </div>
    </div>

    @endif

</div>
