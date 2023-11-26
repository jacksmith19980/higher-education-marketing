<div class="col-md-6">
    @include('back.layouts.core.forms.checkbox',
[
    'name'          => 'properties[show_addons_select]',
    'label'         => 'Addons Selection' ,
    'class'         => '' ,
    'required'      => false,
    'attr'          => '',
    'helper_text'   => 'Show addons',
    'value'         =>  (isset($quotation->properties['show_addons_select'])) ? $quotation->properties['show_addons_select'] : 0,
    'default'       => 1,
])
</div>
<div class="col-md-6">
    @include('back.layouts.core.forms.multi-select',
        [
            'name'      => 'properties[addons][]',
            'label'     => 'Addons' ,
            'class'     =>'select2' ,
            'required'  => false,
            'attr'      => '',
            'value'     => (isset($quotation->properties['addons'])) ? $quotation->properties['addons'] : [],
            'data'      => QuotationHelpers::getAddonsList(),
            ])
</div>
<div class="col-md-6">
    @include('back.layouts.core.forms.text-input',
    [
    'name' => 'properties[addons_step]',
    'label' => 'Step' ,
    'class' =>'' ,
    'required' => false,
    'attr' => '',
    'value' => (isset($quotation->properties['addons_step'])) ? $quotation->properties['addons_step'] : '',
    ])
</div>

<div class="col-md-6">
    @include('back.layouts.core.forms.text-input',
    [
    'name' => 'properties[addons_title]',
    'label' => 'Main Title' ,
    'class' =>'' ,
    'required' => false,
    'attr' => '',
    'value' => (isset($quotation->properties['addons_title'])) ? $quotation->properties['addons_title'] : '',
    ])
</div>

<div class="col-md-6">
    @include('back.layouts.core.forms.text-input',
    [
    'name' => 'properties[addons_sub_title]',
    'label' => 'Sub Title' ,
    'class' =>'' ,
    'required' => false,
    'attr' => '',
    'value' => (isset($quotation->properties['addons_sub_title'])) ? $quotation->properties['addons_sub_title'] :
    '',
    ])
</div>
<div class="col-md-6">
    @include('back.layouts.core.forms.text-input',
    [
    'name' => 'properties[addons_error_message]',
    'label' => 'Error Message' ,
    'class' =>'' ,
    'required' => false,
    'attr' => '',
    'value' => (isset($quotation->properties['addons_error_message'])) ?
    $quotation->properties['addons_error_message']
    : '',
    ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.text-area',
    [
        'name' => 'properties[addons_instructions]',
        'label' => 'Instructions' ,
        'class' =>'' ,
        'required' => false,
        'attr' => '',
        'value' => (isset($quotation->properties['addons_instructions'])) ? $quotation->properties['addons_instructions'] : '',
        'data' => ''
    ])
</div>