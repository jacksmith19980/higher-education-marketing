<div class="col-md-10">
    <div class="card no-padding card-border">
        <div class="card-header">
            <h4 class="card-title">{{__('Registeration Form')}}</h4>
        </div>
        <div class="card-body">
            <div class="row">
                {{--  <div class="col-10 offset-1">
                    <input type="text" placeholder="Search for a field.." class="search-input">

                    @foreach ($settings['auth']['regsiter_form'] as $name=>$field)

                        <div class="col-12">
                            <div class="reg-field-input">
                                <strong>{{$field['label']}}</strong>
                                <span>
                                    <i class="icon-trash text-danger"></i>
                                </span>
                            </div>
                        </div>

                    @endforeach

                </div>  --}}


                <div class="col-md-6">
                    @include('back.layouts.core.forms.select',
                    [
                    'name' => 'phone_register',
                    'label' => 'Show phone field in registeration from',
                    'class' => '' ,
                    'required' => false,
                    'attr' => $disabled,
                    'data' => [
                        'Yes'   => 'Yes',
                        'No'    => 'No'
                    ],
                    'value' => isset($settings['auth']['phone_register'])? $settings['auth']['phone_register'] :
                    'No',
                    ])
                </div>
                <div class="col-md-6">
                    @include('back.layouts.core.forms.select',
                        [
                            'name'     => 'default_country',
                            'label'     => 'Default Country Code' ,
                            'class'     => '' ,
                            'required'  => false,
                            'attr'      => $disabled,
                            'data'      => ApplicationHelpers::getCountryCode(),
                            'value'     =>isset($settings['auth']['default_country'])? $settings['auth']['default_country'] :'CAN',
                        ])
                </div>

                <div class="col-md-6">
                    @include('back.layouts.core.forms.select',
                    [
                    'name' => 'show_country',
                    'label' => 'Show Country field in registeration from',
                    'class' => '' ,
                    'required' => false,
                    'attr' => $disabled,
                    'data' => [
                        'Yes' => 'Yes',
                        'No' => 'No'
                    ],
                    'value' => isset($settings['auth']['show_country'])? $settings['auth']['show_country'] :
                    'No',
                    ])
                </div>
                <div class="col-md-6">
                    @include('back.layouts.core.forms.select',
                    [
                    'name' => 'show_campus',
                    'label' => 'Show Campus field in registeration from',
                    'class' => '' ,
                    'required' => false,
                    'attr' => $disabled,
                    'data' => [
                        'Yes' => 'Yes',
                        'No' => 'No'
                    ],
                    'value' => isset($settings['auth']['show_campus'])? $settings['auth']['show_campus'] :
                    'No',
                    ])
                </div>
                <div class="col-md-6">
                    @include('back.layouts.core.forms.select',
                    [
                    'name' => 'show_program',
                    'label' => 'Show Program field in registeration from',
                    'class' => '' ,
                    'required' => false,
                    'attr' => $disabled,
                    'data' => [
                        'Yes' => 'Yes',
                        'No' => 'No'
                    ],
                    'value' => isset($settings['auth']['show_program'])? $settings['auth']['show_program'] :
                    'No',
                    ])
                </div>
            </div>
        </div>
    </div>
</div>
