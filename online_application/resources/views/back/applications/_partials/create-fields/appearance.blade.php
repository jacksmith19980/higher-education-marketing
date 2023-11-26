<div class="accordion-head bg-info text-white">{{__('Field')}}</div>
<div class="accordion-content accordion-active">
    <div class="row">
        <div class="col-md-6">
            @include('back.layouts.core.forms.text-input',
            [
                    'name'      => 'properties[class]',
                'label'     => 'Classe' ,
                'class'     => 'ajax-form-field' ,
                'required'  => false,
                'attr'      => '',
                'value'     => isset(optional($field)->properties['class']) ? optional($field)->properties['class'] : ''
            ])
        </div>
        <div class="col-md-6">
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
</div><!-- accordion-content -->
<div class="accordion-head bg-info text-white">{{__('Wrapper')}}</div>
<div class="accordion-content">
    <div class="row">
        <div class="col-md-6">
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
        <div class="col-md-6">
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
        <div class="col-md-6">
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
</div> <!-- accordion-content -->
<div class="accordion-head bg-info text-white">{{__('Label')}}</div>
<div class="accordion-content">
    <div class="row">
        <div class="col-md-6">
            @include('back.layouts.core.forms.checkbox',
            [
                'name'          => 'properties[label_show]',
                'label'         => 'Show Label',
                'class'         => 'ajax-form-field' ,
                'required'      => true,
                'attr'          => '',
                'helper_text'   => 'Yes',
                'value'         => isset(optional($field)->properties['label']['show']) ? (bool) optional($field)->properties['label']['show'] : '',
                'default'       => 1
            ])
        </div>
        <div class="col-md-6">
            @include('back.layouts.core.forms.text-input',
            [
                    'name'      => 'properties[label_text]',
                'label'     => 'Label' ,
                'class'     => 'ajax-form-field' ,
                'required'  => false,
                'attr'      => '',
                'value'     => isset(optional($field)->properties['label']['text']) ? optional($field)->properties['label']['text'] : ''
            ])
        </div>
        <div class="col-md-6">
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
        <div class="col-md-6">
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
    </div> <!-- row -->
</div> <!-- accordion-content -->
