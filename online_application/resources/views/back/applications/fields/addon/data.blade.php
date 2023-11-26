
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

        @foreach ($field->data as $value=>$data)

            @if ($order > 0)

                @php
                    $showmore = false;
                @endphp

            @endif

            @include('back.applications.fields.addon.checkbox-options' , [
                'value' => $value,
                'label' => $data['label'],
                'price' => $data['price'],
                'order' => $order,
                'showmore' => $showmore,
                'selected' => isset($data['selected']) ? $data['selected'] : ''
            ])

            @php

                $order++;

            @endphp

        @endforeach

    @else

    <div class="row">

        <div class="col-md-3">

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

        <div class="col-md-3">
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

        <div class="col-md-2">
            @include('back.layouts.core.forms.text-input',
           [
               'name'              => 'custom_data[0][price]',
               'label'             => false,
               'placeholder'       => 'Price' ,
               'class'             =>'ajax-form-field' ,
               'required'          => true,
               'attr'              => '',
               'value'             => isset($price) ? $price : ''
           ])
        </div>

        <div class="col-md-3">
            @include('back.layouts.core.forms.select',
            [
                'name'      => 'custom_data[0][selected]',
                'label'     => false,
                'class'     => 'ajax-form-field' ,
                'required'  => true,
                'attr'      => '',
                'data'      =>  ['no_selected' => 'No Selected' , 'selected' => 'Selected'] ,
                'value'     =>  isset($selected) ? $selected : ''
            ])
        </div>

        <div class="col-md-1 action_wrapper">
            <div class="form-group action_button">
                <button class="btn btn-success" type="button" onclick="app.repeatElement('field.addon' ,'repeated_fields_wrapper' )"><i class="ti ti-plus"></i></button>
            </div>
        </div>

    </div>



    @endif


<div class="repeated_fields_wrapper"></div><!-- repeated_fields_wrapper -->
