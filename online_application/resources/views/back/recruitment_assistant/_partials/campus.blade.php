<div class="col-md-6">
    @include('back.layouts.core.forms.checkbox',
[
    'name'          => 'properties[show_campus]',
    'label'         => '' ,
    'class'         => '' ,
    'required'      => false,
    'attr'          => '',
    'helper_text'   => 'Show campus',
    'value'         =>  (isset($assistantBuilder->properties['show_campus'])) ? $assistantBuilder->properties['show_campus'] : 0,
    'default'       => 1,
])
</div>

<div class="col-md-6">
    @include('back.layouts.core.forms.multi-select', [
        'name'      => 'properties[campuses][]',
        'label'     => 'Campus' ,
        'class'     =>'select2' ,
        'required'  => true,
        'attr'      => '',
        'value'     => (isset($assistantBuilder->properties['campuses'])) ? $assistantBuilder->properties['campuses'] : [],
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
    'value' => (isset($assistantBuilder->properties['campus_step'])) ? $assistantBuilder->properties['campus_step'] : '',
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
    'value' => (isset($assistantBuilder->properties['campus_title'])) ? $assistantBuilder->properties['campus_title'] : '',
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
    'value' => (isset($assistantBuilder->properties['campus_sub_title'])) ? $assistantBuilder->properties['campus_sub_title'] : '',
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
    'value' => (isset($assistantBuilder->properties['campus_error_message'])) ? $assistantBuilder->properties['campus_error_message'] : '',
    ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.text-input',
    [
    'name' => 'properties[campus_help_title]',
    'label' => 'Help title' ,
    'class' =>'' ,
    'required' => false,
    'attr' => '',
    'value' => (isset($assistantBuilder->properties['campus_help_title'])) ? $assistantBuilder->properties['campus_help_title'] : '',
    ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.html', [
        'name'     => 'properties[campus_help_message]',
        'label'    => 'Help text' ,
        'class'    => '' ,
        'required' => false,
        'attr'     => '',
        'value' => (isset($assistantBuilder->properties['campus_help_message'])) ? $assistantBuilder->properties['campus_help_message'] : '',
    ])
</div>
