<div class="accordion-head bg-info text-white">Wrapper</div>
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
            'value'     => (isset(optional($field)->properties['wrapper']['columns']))? optional($field)->properties['wrapper']['columns'] : '6'
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
            'value'     => isset(optional($field)->properties['wrapper']['class']) ? optional($field)->properties['wrapper']['class'] : ''
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
