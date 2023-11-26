<div class="row">

    <div class="col-md-4">
        @include('back.layouts.core.forms.checkbox',
            [
                'name'          => 'properties[enable_misc]',
                'label'         => 'Miscellaneous options' ,
                'class'         => '' ,
                'required'      => false,
                'attr'          => 'onchange=app.enableOptions(this) data-option=misc',
                'helper_text'   => 'Enable miscellaneous options',
                'value'         =>  (isset($quotation->properties['enable_misc'])) ? $quotation->properties['enable_misc'] : 0,
                'default'       =>  1,
            ])
    </div>

    @php 
        $hidden = ( isset($quotation->properties['enable_misc']) ) ? '' : 'hidden';
    @endphp

    <div class="col-md-4 options_group_misc {{$hidden}}">
        @include('back.layouts.core.forms.checkbox',
            [
                'name'          => 'properties[enable_mics_multiselect]',
                'label'         => 'Enable Multi-select' ,
                'class'         => '' ,
                'required'      => false,
                'attr'          => '',
                'helper_text'   => 'Let user select multiple options',
                'value'       => (isset($quotation->properties['enable_mics_multiselect'])) ? $quotation->properties['enable_mics_multiselect'] : 0,
                'default'         =>  1,
            ])
    </div>

    <div class="col-md-4 options_group_misc {{$hidden}}">
        @include('back.layouts.core.forms.select',
            [
                'name'          => 'properties[mics_cost_template]',
                'label'         => 'Pricing Template' ,
                'class'         => '' ,
                'required'      => false,
                'attr'          => '',
                'value'       => (isset($quotation->properties['mics_cost_template'])) ? $quotation->properties['mics_cost_template'] : 'weekly',
                'data'         =>  QuotationHelpers::getPricingOptions(),
            ])
    </div>
</div>

<div class="row">
    <!-- Mics Step -->
    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
        'name' => 'properties[mics_step]',
        'label' => 'Step' ,
        'class' =>'' ,
        'required' => false,
        'attr' => '',
        'value' => (isset($quotation->properties['mics_step'])) ? $quotation->properties['mics_step'] : '',
        ])
    </div>

    <!-- Mics Title -->
    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
        'name' => 'properties[mics_title]',
        'label' => 'Main Title' ,
        'class' =>'' ,
        'required' => false,
        'attr' => '',
        'value' => (isset($quotation->properties['mics_title'])) ? $quotation->properties['mics_title'] : '',
        ])
    </div>

    <!-- Mics Sub Title -->
    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
        'name' => 'properties[mics_sub_title]',
        'label' => 'Sub Title' ,
        'class' =>'' ,
        'required' => false,
        'attr' => '',
        'value' => (isset($quotation->properties['mics_sub_title'])) ? $quotation->properties['mics_sub_title'] :
        '',
        ])
    </div>

    <!-- Mics error message -->
    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
        'name' => 'properties[mics_error_message]',
        'label' => 'Error Message' ,
        'class' =>'' ,
        'required' => false,
        'attr' => '',
        'value' => (isset($quotation->properties['mics_error_message'])) ?
        $quotation->properties['mics_error_message']
        : '',
        ])
    </div>
</div>

<hr>
<h4 class="m-t-20">{{__('Mics Options')}}</h4>
<div class="row options_group_misc {{$hidden}}">
    <div class="col-md-3 offset-9 m-b-20 m-t-20">
        @include('back.layouts.core.helpers.add-elements-button' , [
            'action'    => 'course.addMiscOption',
            'container' => '#misc_options_wrapper',
            'text'      => 'Add New Option'
            ])
    </div>
</div> 

<div class="row options_group_misc {{$hidden}} d-flex" id="misc_options_wrapper"></div>

<div class="row options_group_misc {{$hidden}}">
    @if(isset($quotation->properties['misc_options']))
        
        @foreach($quotation->properties['misc_options'] as $key => $option)

                @php
                    
                    $data = [
                        'option'    => $option,
                        'key'       => $key,
                        'price'     => $quotation->properties['misc_options_price'][$key],
                        'remove'    => true
                    ];

                @endphp

                @include('back.quotation._partials.miscellaneous.miscellaneous-row' , $data)

        @endforeach
    @endif
</div>
