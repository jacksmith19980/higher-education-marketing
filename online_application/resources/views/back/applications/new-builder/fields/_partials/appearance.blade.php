<div class="m-0 card">
    <div class="py-0 pt-2 pl-0 pr-1 ">
        <div class="mb-0 d-flex justify-content-between btn-toggler align-items-center" data-toggle="collapse"
        data-target="#WrapperAppearance" aria-expanded="false" aria-controls="app_pInfo">
        <h5>{{__('Wrapper')}}</h5>
            <i class="mdi mdi-plus text-primary"></i>
        </div>

    </div>

    <div id="WrapperAppearance" class="collapse" aria-labelledby="apph_pInfo" data-parent="#WrapperAppearance" style="">
        <div class="p-0 card-body">
                <div class="row">

        <div class="{{ $newBuilder ? "col-12 new-field" : "col-md-6" }}">
            @include('back.layouts.core.forms.select',
            [
                'name'      => 'properties[wrapper_columns]',
                'label'     => 'Columns' ,
                'class'     => 'ajax-form-field' ,
                'required'  => true,
                'attr'      => '',
                'data'      => [
                        '2'     => '2 Columns',
                        '3' 	=> '3 Columns',
                        '4' 	=> '4 Columns',
                        '5' 	=> '5 Columns',
                        '6' 	=> '6 Columns',
                        '7' 	=> '7 Columns',
                        '8' 	=> '8 Columns',
                        '9' 	=> '9 Columns',
                        '10' 	=> '10 Columns',
                        '11' 	=> '11 Columns',
                        '12'    => '12 Columns',
                ],

                'value'     => (isset(optional($field)->properties['wrapper']['columns']))? optional($field)->properties['wrapper']['columns'] : 6

            ])
        </div>


        <div class="{{ $newBuilder ? "col-12 new-field" : "col-md-6" }}">
            @include('back.layouts.core.forms.text-input',
            [
                'name'      => 'properties[wrapper_class]',
                'label'     => 'Wrapper Classes' ,
                'class'     => 'ajax-form-field' ,
                'required'  => false,
                'attr'      => '',
                'value'     => isset(optional($field)->properties['wrapper']['class'])? optional($field)->properties['wrapper']['class'] : ''
            ])
        </div>

        <div class="{{ $newBuilder ? "col-12 new-field" : "col-md-6" }}">
            @include('back.layouts.core.forms.text-input',
            [
                'name'      => 'properties[wrapper_attr]',
                'label'     => 'Wrapper Attributes' ,
                'class'     => 'ajax-form-field' ,
                'required'  => false,
                'attr'      => '',
                'value'     => isset(optional($field)->properties['wrapper']['attr']) ? optional($field)->properties['wrapper']['attr'] : ''
            ])
        </div>

    </div>
        </div>
    </div>

</div>

<div class="m-0 card">
    <div class="py-0 pt-2 pl-0 pr-1 ">
        <div class="mb-0 d-flex justify-content-between btn-toggler align-items-center" data-toggle="collapse"
        data-target="#LabelAppearance" aria-expanded="false" aria-controls="app_pInfo">
        <h5>{{__('Label')}}</h5>
            <i class="mdi mdi-plus text-primary"></i>
        </div>

    </div>

    <div id="LabelAppearance" class="collapse" aria-labelledby="apph_pInfo" data-parent="#LabelAppearance" style="">
        <div class="p-0 card-body">
            <div class="row">
                <div class="{{ $newBuilder ? "col-12 new-field" : "col-md-6" }}">
                    @include('back.layouts.core.forms.checkbox',
                    [
                        'name'          => 'properties[label_show]',
                        'label'         => false,
                        'class'         => 'ajax-form-field' ,
                        'required'      => true,
                        'attr'          => '',
                        'helper_text'   => 'Show Label',
                        'value'         => isset(optional($field)->properties['label']['show']) ? 1 : 0,
                        'default'       => 1
                    ])
                </div>

                <div class="{{ $newBuilder ? "col-12 new-field" : "col-md-6" }}">
                    @include('back.layouts.core.forms.translateable-text',
                    [
                        'name'      => 'properties[label_text]',
                        'label'     => 'Label' ,
                        'class'     => 'ajax-form-field' ,
                        'required'  => false,
                        'attr'      => '',
                        'value'     => isset(optional($field)->label_text) ? optional($field)->label_text : '',
                        "updateURL" => null
                    ])
                </div>

                <div class="{{ $newBuilder ? "col-12 new-field" : "col-md-6" }}">
                    @include('back.layouts.core.forms.text-input',
                    [
                        'name'      => 'properties[label_class]',
                        'label'     => 'Label Classes' ,
                        'class'     => 'ajax-form-field' ,
                        'required'  => false,
                        'attr'      => '',
                        'value'     => isset(optional($field)->properties['label']['class']) ? optional($field)->properties['label']['class'] : ''
                    ])
                </div>

                <div class="{{ $newBuilder ? "col-12 new-field" : "col-md-6" }}">
                    @include('back.layouts.core.forms.text-input',
                    [
                        'name'      => 'properties[label_attr]',
                        'label'     => 'Label Attributes' ,
                        'class'     => 'ajax-form-field' ,
                        'required'  => false,
                        'attr'      => '',
                        'value'     => isset(optional($field)->properties['label']['attr']) ? optional($field)->properties['label']['attr'] : ''
                    ])
                </div>
            </div>
        </div>
    </div>
</div>


<div class="m-0 card">
    <div class="py-0 pt-2 pl-0 pr-1 ">
        <div class="mb-0 d-flex justify-content-between btn-toggler align-items-center" data-toggle="collapse"
        data-target="#FieldAppearance" aria-expanded="false" aria-controls="app_pInfo">
        <h5>{{__('Field')}}</h5>
            <i class="mdi mdi-plus text-primary"></i>
        </div>

    </div>

    <div id="FieldAppearance" class="collapse" aria-labelledby="apph_pInfo" data-parent="#FieldAppearance" style="">
        <div class="p-0 card-body">
            <div class="row">

                <div class="{{ $newBuilder ? "col-12 new-field" : "col-md-6" }}">
                    @include('back.layouts.core.forms.text-input',
                    [
                        'name'      => 'properties[class]',
                        'label'     => 'Class' ,
                        'class'     => 'ajax-form-field' ,
                        'required'  => false,
                        'attr'      => '',
                        'value'     => isset(optional($field)->properties['class']) ? optional($field)->properties['class'] : ''
                    ])
                </div>


                <div class="{{ $newBuilder ? "col-12 new-field" : "col-md-6" }}
                ">
                    @include('back.layouts.core.forms.text-input',
                    [
                        'name'      => 'properties[attr]',
                        'label'     => 'Attributes' ,
                        'class'     => 'ajax-form-field' ,
                        'required'  => false,
                        'attr'      => '',
                        'value'     => isset(optional($field)->properties['attr']) ? optional($field)->properties['attr'] : ''
                    ])

                </div>
            </div>
        </div>
    </div>

</div>
