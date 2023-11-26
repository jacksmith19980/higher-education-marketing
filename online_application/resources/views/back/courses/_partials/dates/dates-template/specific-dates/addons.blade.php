@if (!isset($subkey))
    @php
        $subkey = Str::random(10);    
    @endphp
@endif
<div class="row course_date_addon" data-key="{{$subkey}}">
    
        @if (!isset($edit))
            <div class="col-md-12">
                <div class="d-flex no-block align-items-center justify-content-center card-title-wrapper">
                        <h4 class="card-title d-block">
                            @if (isset($addon['title']))
                                {{$addon['title']}}
                            @else
                                {{__('Addon')}}
                            @endif
                        </h4>
                        <div class="ml-auto">
                            <a href="javascript:void(0)" class="btn btn-light" 
                            onclick="app.removeBlock('{{$subkey}}')">
                                <i class="icon-close"></i>
                            </a>
                        </div>
                    </div>
                </div>
        @endif

        <div class="col-md-4">
            @include('back.layouts.core.forms.select',
            [
        
            'name'      => 'properties[addons]['.$subkey.'][category]',
            'label'     => 'Category' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => isset($addon['category']) ? $addon['category'] : '',
            'data'      => QuotationHelpers::getAddonsList(),
            ])
        </div>
        
        <div class="col-md-8">
            @include('back.layouts.core.forms.text-input',
            [
            'name'      => 'properties[addons]['.$subkey.'][title]',
            'label'     => 'Title' ,
            'class'     => 'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value' => isset($addon['title']) ? $addon['title'] : '',
            'data'      => ''
            ])
        </div>
        
        <div class="col-md-6">
            @include('back.layouts.core.forms.text-input',
            [
            'name'        => 'properties[addons]['.$subkey.'][price]',
            'label'       => 'Price' ,
            'class'       =>'with-currency ajax-form-field' ,
            'required'    => true,
            'attr'        => '',
            'value' => isset($addon['price']) ? $addon['price'] : '',
            'data'        => '',
            'hint_after'  => isset($settings['school']['default_currency'])? $settings['school']['default_currency'] : 'CAD'
        
            ])
        </div>
        
        <div class="col-md-6">
            @include('back.layouts.core.forms.select',
            [
            'name'        => 'properties[addons]['.$subkey.'][price_type]',
            'label'       => 'Pricing Type' ,
            'class'       => 'ajax-form-field' ,
            'required'    => true,
            'attr'        => '',
            'value'       => isset($addon['price_type']) ? $addon['price_type'] : '',
                'data'        => QuotationHelpers::getPricingOptions(),
            ])
        </div>
        <div class="col-md-6">
            @include('back.layouts.core.forms.date-input',
            [
                'name' => 'properties[addons]['.$subkey.'][date]',
                'label' => 'Date' ,
                'class' => 'ajax-form-field' ,
                'required' => false,
                'attr' => '',
                'value' => isset($addon['date']) ? $addon['date'] : '',
                'data' => ''
            ])
        </div>

</div>