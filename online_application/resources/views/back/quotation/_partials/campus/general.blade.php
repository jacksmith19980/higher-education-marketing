

<div class="col-md-6">
    @include('back.layouts.core.forms.checkbox',
[
    'name'          => 'properties[hide_campus_select]',
    'label'         => 'Campus selection' ,
    'class'         => '' ,
    'required'      => false,
    'attr'          => '',
    'helper_text'   => 'Hide campus select',
    'value'         =>  (isset($quotation->properties['hide_campus_select'])) ? $quotation->properties['hide_campus_select'] : 0,
    'default'       => 1,
])
</div>
<div class="col-md-6">
    @include('back.layouts.core.forms.multi-select',
        [
            'name'      => 'properties[campuses][]',
            'label'     => 'Campuses' ,
            'class'     =>'select2' ,
            'required'  => false,
            'attr'      => '',
            'value'     => (isset($quotation->properties['campuses'])) ? $quotation->properties['campuses'] : [],
            'data'      => $campuses
            ])
</div>
<div class="col-md-6">
    @include('back.layouts.core.forms.text-input',
    [
    'name' => 'properties[campus_step]',
    'label' => 'Step' ,
    'class' =>'' ,
    'required' => false,
    'attr' => '',
    'value' => (isset($quotation->properties['campus_step'])) ? $quotation->properties['campus_step'] : '',
    ])
</div>

<div class="col-md-6">
    @include('back.layouts.core.forms.text-input',
    [
    'name' => 'properties[campus_title]',
    'label' => 'Main Title' ,
    'class' =>'' ,
    'required' => false,
    'attr' => '',
    'value' => (isset($quotation->properties['campus_title'])) ? $quotation->properties['campus_title'] : '',
    ])
</div>

<div class="col-md-6">
    @include('back.layouts.core.forms.text-input',
    [
    'name' => 'properties[campus_sub_title]',
    'label' => 'Sub Title' ,
    'class' =>'' ,
    'required' => false,
    'attr' => '',
    'value' => (isset($quotation->properties['campus_sub_title'])) ? $quotation->properties['campus_sub_title'] : '',
    ])
</div>
<div class="col-md-6">
    @include('back.layouts.core.forms.text-input',
    [
    'name' => 'properties[campus_error_message]',
    'label' => 'Error Message' ,
    'class' =>'' ,
    'required' => false,
    'attr' => '',
    'value' => (isset($quotation->properties['campus_error_message'])) ? $quotation->properties['campus_error_message'] : '',
    ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.text-area',
        [
            'name' => 'properties[campus_instructions]',
            'label' => 'Instructions' ,
            'class' =>'' ,
            'required' => false,
            'attr' => '',
            'value' => (isset($quotation->properties['campus_instructions'])) ?
            $quotation->properties['campus_instructions'] :
            '',
            'data' => ''
        ])
</div>