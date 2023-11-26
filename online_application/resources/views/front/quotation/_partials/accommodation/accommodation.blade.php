@if (isset($quotation->properties['enable_accommodation']))
    <h5>{{__('Accommodation Options')}}</h5>
    <div class="form-group">

        @include('back.layouts.core.forms.select',
        [
            'name'          => "accommodation[]",
            'label'         => '',
            'class'         => '' ,
            'required'      => false,
            'attr'          => '',
            'value'         => null,
            'placeholder'   => __('We will arrange the Accommodation' ),
            'data'          => $quotation->properties['accommodation_options'],
        ])
    </div>
@endif