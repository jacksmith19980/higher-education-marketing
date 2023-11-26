 @include('back.layouts.core.forms.hidden-input',

            [

                'name'          => 'group',

                'label'         => '' ,

                'class'         => '',

                'required'      => false,

                'attr'          => '',

                'value'         => 'quotation',

            ])



<div class="row">

    <div class="col-md-6">



                @include('back.layouts.core.forms.select',

                    [

                        'name'          => 'default_currency',

                        'label'         => 'Default Currency' ,

                        'class'         => '',

                        'required'      => false,

                        'attr'          => 'onchange=app.changeDefaultCurrencyHint(this)',

                        'value'         => isset($settings['school']['default_currency'])? $settings['school']['default_currency'] : 'CAD',

                        'placeholder'   => '',

                        'data'          => QuotationHelpers::getDefaultCurrency()

                    ])

    </div>



    <div class="col-md-6">

            @include('back.layouts.core.forms.text-input',

            [

                'name'          => 'global_registeration_fees',

                'label'         => 'Registration Fee' ,

                'class'         => 'with-currency',

                'required'      => false,

                'attr'          => '',

                'value'         => isset($settings['quotation']['global_registeration_fees'])? $settings['quotation']['global_registeration_fees'] : '',

                'hint_after'    => isset($settings['school']['default_currency'])? $settings['school']['default_currency'] : 'CAD'

            ])

    </div>



    <div class="col-md-6">

        @include('back.layouts.core.forms.text-input',

        [

            'name'          => 'global_materials_fees',

            'label'         => 'Materials Fee' ,

            'class'         => 'with-currency',

            'required'      => false,

            'attr'          => '',

            'value'         => isset($settings['quotation']['global_materials_fees'])? $settings['quotation']['global_materials_fees'] : '',

            'hint_after'    => isset($settings['school']['default_currency'])? $settings['school']['default_currency'] : 'CAD'

        ])

    </div>



    <div class="col-md-6"></div>



    <div class="col-md-6">

            @include('back.layouts.core.forms.select',

            [

                'name'          => 'week_start',

                'label'         => 'Week Starts at' ,

                'class'         => '',

                'required'      => false,

                'attr'          => '',

                'value'         => isset($settings['quotation']['week_start'])? $settings['quotation']['week_start'] : 'monday',

                'data'      => [

                    '0'  => 'Sunday',   

                    '1'  => 'Monday',   

                    '2'  => 'Tuesday',   

                    '3'  => 'Wednesday',   

                    '4'  => 'Thursday',   

                    '5'  => 'Friday',   

                    '6'  => 'Staurday',     

                ]

            ])

    </div>



    <div class="col-md-6">

            @include('back.layouts.core.forms.select',

            [

                'name'          => 'week_end',

                'label'         => 'Week Ends at' ,

                'class'         => '',

                'required'      => false,

                'attr'          => '',

                'value'         => isset($settings['quotation']['week_end'])? $settings['quotation']['week_end'] : 'friday',

                'data'   => [

                    '0'  => 'Sunday',   

                    '1'  => 'Monday',   

                    '2'  => 'Tuesday',   

                    '3'  => 'Wednesday',   

                    '4'  => 'Thursday',   

                    '5'  => 'Friday',   

                    '6'  => 'Staurday',   

                ]

            ])

    </div>

</div>