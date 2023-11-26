<h4 class="m-b-15">{{__('Applications Settings')}}</h4>

<form method="post" action="{{route('settings.store')}}" class="needs-validation" novalidate="" enctype="multipart/form-data">
    <div class="row">
        @csrf
        @include('back.layouts.core.forms.hidden-input',
                [
                    'name'          => 'group',
                    'value'         => 'applications',
                    'class'         => '',
                    'required'      => '',
                    'attr'          => '',
        ])

        @if(isset($settings['integrations']['integration_mautic']))
            <div class="col-md-10">
                <div class="card no-padding card-border">
                    <div class="card-header">
                        <h4 class="card-title">{{__('Main')}}</h4>
                    </div>
                    <div class="card-body" style="border:1px solid #f7f7f7;">
                        <div class="row">
                            {{--   Contact Type is valid only for Mautic  --}}

                                <div class="col-md-12">
                                    @include('back.layouts.core.forms.multi-select',
                                    [
                                    'name' => 'contact_type[]',
                                    'label' => 'Contact Types' ,
                                    'class' => 'select2',
                                    'required' => true,
                                    'attr' => '',
                                    'value' => isset($settings['applications']['contact_type'])? $settings['applications']['contact_type'] : [ 'lead'
                                    =>'Lead'],
                                    'placeholder' => '',
                                    'data' => ApplicationHelpers::getContactTypes()
                                    ])
                                </div>

                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-md-10">
            <div class="card no-padding card-border">
                <div class="card-header">
                    <h4 class="card-title">{{__('Application')}} {{__('Color')}}</h4>
                </div>
                <div class="card-body" style="border:1px solid #f7f7f7;">
                    <div class="row">
                        <div class="col-md-6">
                            @include('back.layouts.core.forms.color-input', [
                                'name' => 'step_color',
                                'label' => 'Steps Color' ,
                                'class' => '',
                                'required' => false,
                                'attr' => '',
                                'value' => isset($settings['applications']['step_color'])? $settings['applications']['step_color'] : '',
                                'helper_text' => 'Used for steps in applications'
                            ])
                        </div>

                        <div class="col-md-6">
                            @include('back.layouts.core.forms.color-input', [
                                'name' => 'header_line_color',
                                'label' => 'Header Line Color' ,
                                'class' => '',
                                'required' => false,
                                'attr' => '',
                                'value' => isset($settings['applications']['header_line_color'])? $settings['applications']['header_line_color'] : '',
                                'helper_text' => 'Used for Header Line Color in applications'
                            ])
                        </div>

                        <div class="col-md-6">
                            @include('back.layouts.core.forms.color-input', [
                                'name' => 'footer_link_color',
                                'label' => 'Footer Link Color' ,
                                'class' => '',
                                'required' => false,
                                'attr' => '',
                                'value' => isset($settings['applications']['footer_link_color'])? $settings['applications']['footer_link_color'] : '',
                                'helper_text' => 'Used for Footer Link Color in applications'
                            ])
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-10">
            <button class="float-right btn btn-success">Save</button>
        </div>
    </div>
</form>
