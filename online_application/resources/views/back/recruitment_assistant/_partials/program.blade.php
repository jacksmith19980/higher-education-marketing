<div class="col-md-6">
    @include('back.layouts.core.forms.checkbox',
[
    'name'          => 'properties[show_programs]',
    'label'         => '' ,
    'class'         => '' ,
    'required'      => false,
    'attr'          => '',
    'helper_text'   => 'Show programs',
    'value'         =>  (isset($assistantBuilder->properties['show_programs'])) ? $assistantBuilder->properties['show_programs'] : 0,
    'default'       => 1,
])
</div>

<div class="col-md-6">
    @include('back.layouts.core.forms.multi-select', [
        'name'        => 'properties[programs][]',
        'label'       => 'Programs' ,
        'class'       =>'select2' ,
        'required'    => false,
        'attr'        => '',
        'value'       => (isset($assistantBuilder->properties['programs'])) ? $assistantBuilder->properties['programs'] : [],
        'placeholder' => '`',
        'data'        => $programs
    ])
</div>

<div class="col-md-6">
    @include('back.layouts.core.forms.text-input',
    [
    'name' => 'properties[programs_step]',
    'label' => 'Step' ,
    'class' =>'' ,
    'required' => false,
    'attr' => '',
    'value' => (isset($assistantBuilder->properties['programs_step'])) ? $assistantBuilder->properties['programs_step'] : '',
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
    'value' => (isset($assistantBuilder->properties['programs_title'])) ? $assistantBuilder->properties['programs_title'] : '',
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
    'value' => (isset($assistantBuilder->properties['programs_sub_title'])) ? $assistantBuilder->properties['programs_sub_title'] : '',
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
    'value' => (isset($assistantBuilder->properties['programs_error_message'])) ? $assistantBuilder->properties['programs_error_message'] : '',
    ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.text-input',
    [
    'name' => 'properties[programs_help_title]',
    'label' => 'Help title' ,
    'class' =>'' ,
    'required' => false,
    'attr' => '',
    'value' => (isset($assistantBuilder->properties['programs_help_title'])) ? $assistantBuilder->properties['programs_help_title'] : '',
    ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.html', [
        'name'     => 'properties[programs_help_message]',
        'label'    => 'Help text' ,
        'class'    => '' ,
        'required' => false,
        'attr'     => '',
        'value' => (isset($assistantBuilder->properties['programs_help_message'])) ? $assistantBuilder->properties['programs_help_message'] : '',
    ])
</div>
