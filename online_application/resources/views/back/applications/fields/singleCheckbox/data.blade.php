
    @include('back.layouts.core.forms.hidden-input',
        [
            'name'      => 'data',
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => 'custom_list'
        ]
    )


    @if (isset($field->data) && !empty($field->data))

            @php
                $order = 0;
                $showmore = true;
            @endphp

        @foreach ($field->data as $value=>$label)

            @if ($order > 0)

                @php
                    $showmore = false;
                @endphp

            @endif

            @include('back.applications.fields.checkbox.checkbox-options' , ['value' => $value , 'label' => $label , 'order' => $order , 'showmore' => $showmore ])

            @php

                $order++;

            @endphp

        @endforeach

    @else

    <div class="row">

        <div class="col-md-6">

            @include('back.layouts.core.forms.text-input',
            [
                'name'          => 'custom_data[0][label]',
                'label'         => null ,
                'placeholder'   => 'Label' ,
                'class'         =>'ajax-form-field' ,
                'required'      => true,
                'attr'          => '',
                'value'         => isset($label) ? $label : ''
            ])
        </div>

        <div class="col-md-6">
             @include('back.layouts.core.forms.text-input',
            [
                'name'              => 'custom_data[0][value]',
                'label'             => false,
                'placeholder'       => 'Value' ,
                'class'             =>'ajax-form-field' ,
                'required'          => true,
                'attr'              => '',
                'value'             => isset($value) ? $value : ''
            ])
        </div>
    </div>

    @endif
