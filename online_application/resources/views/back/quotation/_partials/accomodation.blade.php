<div class="row">
    <div class="col-md-4">
        @include('back.layouts.core.forms.checkbox',
            [
                'name'          => 'properties[enable_accommodation]',
                'label'         => 'Accommodation options' ,
                'class'         => '' ,
                'required'      => false,
                'attr'          => 'onchange=app.enableOptions(this) data-option=accommodation',
                'helper_text'   => 'Enable accommodation options',
                'value'         =>  (isset($quotation->properties['enable_accommodation'])) ? $quotation->properties['enable_accommodation'] : 0,
                'default'       =>  1,
            ])
    </div>

    @php 
        $hidden = ( isset($quotation->properties['enable_accommodation']) ) ? '' : 'hidden';
    @endphp

    <div class="col-md-4 options_group_accommodation {{$hidden}}">
        @include('back.layouts.core.forms.checkbox',
            [
                'name'          => 'properties[enable_accommodation_multiselect]',
                'label'         => 'Enable Multi-select' ,
                'class'         => '' ,
                'required'      => false,
                'attr'          => '',
                'helper_text'   => 'Let user select multiple options',
                'value'         =>  (isset($quotation->properties['enable_accommodation_multiselect'])) ? $quotation->properties['enable_accommodation_multiselect'] : 0,
                'default'       =>  1,
            ])
    </div>

        
    <div class="col-md-4 options_group_accommodation {{$hidden}}">
        
        @include('back.layouts.core.forms.select',
            [
                'name'          => 'properties[accommodation_cost_template]',
                'label'         => 'Pricing Template' ,
                'class'         => '' ,
                'required'      => false,
                'attr'          => '',
                'value'         => (isset($quotation->properties['accommodation_cost_template'])) ? $quotation->properties['accommodation_cost_template'] : 'weekly',
                'data'          =>  QuotationHelpers::getPricingOptions(),
            ])
    </div>
</div>
<div class="row options_group_accommodation {{$hidden}}">
    <div class="col-md-3 offset-9 m-b-20 m-t-20">
        @include('back.layouts.core.helpers.add-elements-button' , [
            'action'    => 'course.addAccommodationOption',
            'container' => '#accommodation_options_wrapper',
            'text'      => 'Add Accommodation Option'
            ])
    </div>
</div>        

<div class="row options_group_accommodation {{$hidden}} d-flex" id="accommodation_options_wrapper"></div>

<div class="row options_group_accommodation {{$hidden}}">
    
    @if(isset($quotation->properties['accomodation_options']))
        
        @foreach($quotation->properties['accomodation_options'] as $key => $option)

                @php
                    $data = [
                        'option'    => $option,
                        'key'       => $key,
                        'price'     => $quotation->properties['accomodation_options_price'][$key],
                        'remove'    => true
                    ];
                @endphp

                @include('back.quotation._partials.accommodation-row' , $data)

        @endforeach
    
    @endif

</div>
