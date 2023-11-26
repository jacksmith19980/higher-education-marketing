<div class="col-md-6">
    @include('back.layouts.core.forms.checkbox',
[
    'name'          => 'properties[enable_multi_program]',
    'label'         => 'Multiple Programs' ,
    'class'         => '' ,
    'required'      => false,
    'attr'          => '',
    'helper_text'   => 'Let users select multiple programs',
    'value'         =>  (isset($quotation->properties['enable_multi_program'])) ? $quotation->properties['enable_multi_program'] : 0,
    'default'       => 1,
])
</div>
<div class="col-md-6">
    @include('back.layouts.core.forms.checkbox',
[
    'name'          => 'properties[hide_program_select]',
    'label'         => 'Program selection' ,
    'class'         => '' ,
    'required'      => false,
    'attr'          => '',
    'helper_text'   => 'Hide program select',
    'value'         =>  (isset($quotation->properties['hide_program_select'])) ? $quotation->properties['hide_program_select'] : 0,
    'default'       => 1,
])
</div>
<div class="col-md-6">
    @include('back.layouts.core.forms.multi-select',
    [
        'name'      => 'properties[courses][]',
        'label'     => 'Courses' ,
        'class'     =>'select2' ,
        'required'  => true,
        'attr'      => '',
        'value'     => (isset($quotation->properties['courses'])) ? $quotation->properties['courses'] : [],
        'data'      => $courses
    ])
</div>

<div class="col-md-6">
</div>

<div class="col-md-6">
    @include('back.layouts.core.forms.text-input',
    [
    'name' => 'properties[programs_step]',
    'label' => 'Step' ,
    'class' =>'' ,
    'required' => false,
    'attr' => '',
    'value' => (isset($quotation->properties['programs_step'])) ? $quotation->properties['programs_step'] : '',
    ])
</div>

<div class="col-md-6">
    @include('back.layouts.core.forms.text-input',
    [
    'name' => 'properties[programs_title]',
    'label' => 'Main Title' ,
    'class' =>'' ,
    'required' => false,
    'attr' => '',
    'value' => (isset($quotation->properties['programs_title'])) ? $quotation->properties['programs_title'] : '',
    ])
</div>

<div class="col-md-6">
    @include('back.layouts.core.forms.text-input',
    [
    'name' => 'properties[programs_sub_title]',
    'label' => 'Sub Title' ,
    'class' =>'' ,
    'required' => false,
    'attr' => '',
    'value' => (isset($quotation->properties['programs_sub_title'])) ? $quotation->properties['programs_sub_title'] : '',
    ])
</div>
<div class="col-md-6">
    @include('back.layouts.core.forms.text-input',
    [
    'name' => 'properties[programs_error_message]',
    'label' => 'Error Message' ,
    'class' =>'' ,
    'required' => false,
    'attr' => '',
    'value' => (isset($quotation->properties['programs_error_message'])) ? $quotation->properties['programs_error_message']
    : '',
    ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.text-area',
    [
        'name' => 'properties[programs_instructions]',
        'label' => 'Instructions' ,
        'class' =>'' ,
        'required' => false,
        'attr' => '',
        'value' => (isset($quotation->properties['programs_instructions'])) ? $quotation->properties['programs_instructions'] :
        '',
        'data' => ''
    ])
</div>
{{-- 
<div class="col-md-6">
    @include('back.layouts.core.forms.multi-select',
    [
        'name'      => 'properties[programs][]',
        'label'     => 'Programs' ,
        'class'     =>'select2' ,
        'required'  => false,
        'attr'      => '',
        'value'     => (isset($quotation->properties['programs'])) ? $quotation->properties['programs'] : [],
        'data'      => $programs
    ])
</div>
 --}}
