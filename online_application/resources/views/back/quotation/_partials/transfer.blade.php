<div class="row">
    <div class="col-md-6">
         @include('back.layouts.core.forms.checkbox',
        [
            'name'          => 'properties[enable_transfer]',
            'label'         => 'Transfer options' ,
            'class'         => '' ,
            'required'      => false,
            'attr'          => 'onchange=app.enableOptions(this) data-option=transfer',
            'helper_text'   => 'Enable transfer options',
            'value'         =>  (isset($quotation->properties['enable_transfer'])) ? $quotation->properties['enable_transfer'] : 0,
            'default'       => 1,
        ])
    </div>

     @php 
        $hidden = ( isset($quotation->properties['enable_transfer']) ) ? '' : 'hidden';
    @endphp

    <div class="col-md-6 options_group_transfer {{$hidden}}">
        @include('back.layouts.core.forms.checkbox',
            [
                'name'          => 'properties[enable_transfer_multiselect]',
                'label'         => 'Enable Multi-select' ,
                'class'         => '' ,
                'required'      => false,
                'attr'          => '',
                'helper_text'   => 'Let user select multiple options',
                'value'         =>  (isset($quotation->properties['enable_transfer_multiselect'])) ? $quotation->properties['enable_transfer_multiselect'] : 0,
                'default'       =>  1,
            ])
    </div>
</div>

<div class="row options_group_transfer {{$hidden}}">
    <div class="col-md-3 offset-9 m-b-20 m-t-20">
        @include('back.layouts.core.helpers.add-elements-button' , [
            'action'    => 'course.addTransferOption',
            'container' => '#transfer_options_wrapper',
            'text'      => 'Add Transfer Option'
            ])
    </div>
</div>   

<div class="row options_group_transfer d-flex {{$hidden}}" id="transfer_options_wrapper"></div>

<div class="row options_group_transfer {{$hidden}}">


        {{-- @include('back.quotation._partials.transfer-row') --}}

        @if(isset($quotation->properties['transfer_options']))
        
            @foreach($quotation->properties['transfer_options'] as $key => $option)

                    @include('back.quotation._partials.transfer-row' , [
                        'option'    => $option,
                        'key'       => $key,
                        'price'     => $quotation->properties['transfer_options_price'][$key],
                        'remove'    => true
                    ])

            @endforeach
    
        @endif
        
</div>
