<div class="col-md-10">
    <div class="card no-padding card-border">
        <div class="card-header">
            <h4 class="card-title">{{__('Hubspot')}}</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    @include('back.layouts.core.forms.select', [
                    'name' => 'hubspot[push]',
                    'label' => 'Push new accounts to Hubspot' ,
                    'class' => '',
                    'required' => false,
                    'attr' => '',
                    'placeholder' => 'Select',
                    'data' => [
                    'Yes' => 'Yes',
                    'No' => 'No',
                    ],
                    'value' => isset($settings['auth']['hubspot']['push'])? $settings['auth']['hubspot']['push'] :
                    'Yes',
                    ])
                </div>
            </div>
            <div class="row repeated_element_container">

                @php
                    if (isset($settings['auth']['hubspot']['custom_properties']) && count($settings['auth']['hubspot']['custom_properties'])){
                        $hsCustomProperties = $settings['auth']['hubspot']['custom_properties'];
                    }else{
                        $hsCustomProperties[]= [
                            'property'=> null,
                            'value'=> null,
                        ];
                    }
                @endphp

                <div class="col-xlg-3 offset-xlg-9 col-lg-4 offset-lg-8 m-b-30 col-md-4 offset-md-8 col-sm-5 offset-sm-7 col-xs-6 offset-xs-6">
                    @include('back.layouts.core.helpers.add-elements-button' , [
                        'text'          => 'Add Custom Property',
                        'action'        => 'setting.addHsCustomProperty',
                        'container'     => '.repeated_element_container',
                        'order'         => count($hsCustomProperties) + 1
                    ])
                </div>
                @foreach ($hsCustomProperties as $key => $property)
                    @include('back.settings._partials.integrations.hubspot-property-row' , [
                        'order'     => $key,
                        'property'  => $property
                    ])
                @endforeach

            </div>
        </div>
    </div>
</div>
