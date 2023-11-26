<div class="row repeated_fields" data-repeated="{{$order}}">
    <div class="col-md-6 new-field">
        @include('back.layouts.core.forms.text-input',
        [
        'name' => 'properties[product]['.$order.'][title]',
        'label' => 'Title' ,
        'class' =>'ajax-form-field' ,
        'required' => false,
        'attr' => '',
        'value' => ( isset($product['title']) ) ? $product['title'] : '',
        ])
    </div>

    <div class="col-md-3 new-field">
        @include('back.layouts.core.forms.text-input',
        [
        'name' => 'properties[product]['.$order.'][price]',
        'label' => 'Price' ,
        'class' =>'ajax-form-field' ,
        'required' => false,
        'attr' => '',
        'hint_after' => isset($settings['school']['default_currency'])? $settings['school']['default_currency'] : 'CAD',
        'value' => ( isset($product['price']) ) ? $product['price'] : '',
        ])
    </div>
    <div class="col-md-2 new-field">
        @include('back.layouts.core.forms.text-input',
        [
        'name' => 'properties[product]['.$order.'][quantity]',
        'label' => 'Quantity' ,
        'class' =>'ajax-form-field' ,
        'required' => false,
        'attr' => '',
        'value' => ( isset($product['quantity']) ) ? $product['quantity']
        : '',
        ])
    </div>
    <div class="col-md-1 action_wrapper">

        @if($order == 0)

        <div class="form-group action_button">
            <label>&nbsp;</label>
            <button class="btn btn-outline-success mt-1" type="button"
                onclick="app.repeatElement('payment.addProduct' ,'repeated_fields_wrapper' , true )">
                <i class="fa fa-plus"></i>
            </button>
        </div>
        @else

        <div class="form-group action_button">
            <label>&nbsp;</label>
            <button class="btn btn-outline-danger mt-1" type="button"
                onclick="app.removeRepeatedElement(this, {{$order}} )">
                <i class="fa fa-minus"></i>
            </button>
        </div>

        @endif
    </div>
</div>
