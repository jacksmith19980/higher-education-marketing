@if (isset($quotation->properties['enable_transfer']))

    @php
        $args = [
            'name'          => "transfer[]",
            'label'         => '',
            'class'         => 'select2' ,
            'required'      => false,
            'attr'          => '',
            'value'         => null,
            'placeholder'   => __('We will organise the transfe' ),
            'data'          => $quotation->properties['transfer_options'],
        ];       
        $inputType = 'select';
    @endphp

        {{-- if Multiple selection is enabled --}}
        @if (isset($quotation->properties['enable_transfer_multiselect']))
            @php
                $args['helper'] = 'You can select multiple options';
                $inputType = 'multi-select';
            @endphp

        @endif

    <h5>{{__('Transfer Options')}}</h5>
    <div class="form-group">

        @include('back.layouts.core.forms.'.$inputType , $args)
    </div>
@endif