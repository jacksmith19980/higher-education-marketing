@php
    $disabled = (!$permissions['edit|settings']) ? 'disabled="disabled"' : '';
@endphp
<h4 class="m-b-15">{{__('Quotation Settings')}}</h4>

<form method="post" action="{{route('settings.store')}}" class="needs-validation" novalidate=""
    enctype="multipart/form-data">
    <div class="row">

        @csrf

        @include('back.layouts.core.forms.hidden-input', [
            'name'          => 'group',
            'label'         => '' ,
            'class'         => '',
            'required'      => false,
            'attr'          => $disabled,
            'value'         => 'quotation',
        ])


        <div class="col-md-10">
            <div class="card no-padding card-border">
                <div class="card-header">
                    <h4 class="card-title">{{__('Main')}}</h4>
                </div>
                <div class="card-body">
                    <div class="row">

{{--                        <div class="col-md-6">--}}
{{--                            @include('back.layouts.core.forms.text-input', [--}}
{{--                            'name' => 'global_registeration_fees',--}}
{{--                            'label' => 'Global Registration Fee' ,--}}
{{--                            'class' => 'with-currency',--}}
{{--                            'required' => false,--}}
{{--                            'attr' => '',--}}
{{--                            'value' => isset($settings['quotation']['global_registeration_fees'])?--}}
{{--                            $settings['quotation']['global_registeration_fees'] : '',--}}
{{--                            'hint_after' => isset($settings['school']['default_currency'])? $settings['school']['default_currency'] :--}}
{{--                            'CAD'--}}
{{--                            ])--}}
{{--                        </div>--}}

{{--                        <div class="col-md-6">--}}
{{--                            @include('back.layouts.core.forms.text-input', [--}}
{{--                            'name' => 'global_materials_fees',--}}
{{--                            'label' => 'Global Materials Fee' ,--}}
{{--                            'class' => 'with-currency',--}}
{{--                            'required' => false,--}}
{{--                            'attr' => '',--}}
{{--                            'value' => isset($settings['quotation']['global_materials_fees'])? $settings['quotation']['global_materials_fees'] :--}}
{{--                            '',--}}
{{--                            'hint_after' => isset($settings['school']['default_currency'])? $settings['school']['default_currency'] :--}}
{{--                            'CAD'--}}
{{--                            ])--}}
{{--                        </div>--}}

                        <div class="col-md-6">
                            @include('back.layouts.core.forms.select', [
                                'name' => 'week_start',
                                'label' => 'Week Starts at' ,
                                'class' => '',
                                'required' => false,
                                'attr' => $disabled,
                                'value' => isset($settings['quotation']['week_start'])? $settings['quotation']['week_start'] : 'monday',
                                'data' => [
                                    '0' => 'Sunday',
                                    '1' => 'Monday',
                                    '2' => 'Tuesday',
                                    '3' => 'Wednesday',
                                    '4' => 'Thursday',
                                    '5' => 'Friday',
                                    '6' => 'Staurday',
                                ]
                            ])
                        </div>

                        <div class="col-md-6">
                            @include('back.layouts.core.forms.select', [
                                'name' => 'week_end',
                                'label' => 'Week Ends at' ,
                                'class' => '',
                                'required' => false,
                                'attr' => $disabled,
                                'value' => isset($settings['quotation']['week_end'])? $settings['quotation']['week_end'] : 'friday',
                                'data' => [
                                    '0' => 'Sunday',
                                    '1' => 'Monday',
                                    '2' => 'Tuesday',
                                    '3' => 'Wednesday',
                                    '4' => 'Thursday',
                                    '5' => 'Friday',
                                    '6' => 'Staurday',
                                ]
                            ])
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                @include('back.layouts.core.forms.date-input', [
                                    'name' => 'hiseason_start_dates',
                                    'label' => 'High Season Start Date' ,
                                    'class' =>'' ,
                                    'required' => false,
                                    'attr' => $disabled,
                                    'value' => isset($settings['quotation']['hiseason_start_dates'])? $settings['quotation']['hiseason_start_dates']
                                    : '',
                                    'data' => ''
                                ])

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                @include('back.layouts.core.forms.date-input',[
                                    'name' => 'hiseason_end_dates',
                                    'label' => 'High Season End Date' ,
                                    'class' =>'' ,
                                    'required' => false,
                                    'attr' => $disabled,
                                    'value' => isset($settings['quotation']['hiseason_end_dates'])? $settings['quotation']['hiseason_end_dates'] :
                                    '',
                                    'data' => ''
                                ])

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <button class="float-right btn btn-success" {{$disabled}}>
                {{__('Save')}}
            </button>
        </div>
    </div>
</form>
