<form action="{{route('addon.store' , $course)}}" method="post">
    @csrf
    <input type="hidden" class="ajax-form-field" name="key" value="{{$key}}" />
    <div class="row">
        <div class="col-md-6">
            @include('back.layouts.core.forms.select',
            [
                'name' => 'category',
                'label' => 'Category' ,
                'class' =>'ajax-form-field' ,
                'required' => true,
                'attr' => '',
                'value' => isset($addon->category) ? $addon->category : '',
                'data' => QuotationHelpers::getAddonsList(),
            ])
        </div>
    
        <div class="col-md-6">
            @include('back.layouts.core.forms.text-input',
            [
            'name' => 'title',
            'label' => 'Title' ,
            'class' => 'ajax-form-field' ,
            'required' => true,
            'attr' => '',
            'value' => isset($addon->title) ? $addon->title : '',
            'data' => ''
            ])
        </div>
    
        <div class="col-md-6">
            @include('back.layouts.core.forms.text-input',
            [
            'name' => 'price',
            'label' => 'Price' ,
            'class' =>'with-currency ajax-form-field' ,
            'required' => true,
            'attr' => '',
            'value' => isset($addon->price) ? $addon->price : '',
            'data' => '',
            'hint_after' => isset($settings['school']['default_currency'])? $settings['school']['default_currency'] :
            'CAD'
    
            ])
        </div>
    
        <div class="col-md-6">
            @include('back.layouts.core.forms.select',
            [
            'name' => 'price_type',
            'label' => 'Pricing Type' ,
            'class' => 'ajax-form-field' ,
            'required' => true,
            'attr' => '',
            'value' => isset($addon->price_type) ? $addon->price_type : '',
            'data' => QuotationHelpers::getPricingOptions(),
    
    
            ])
        </div>
    
    </div>
</form>