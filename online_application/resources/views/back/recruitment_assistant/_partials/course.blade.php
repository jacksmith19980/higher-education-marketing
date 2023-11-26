<div class="col-md-6">
    @include('back.layouts.core.forms.checkbox',
[
    'name'          => 'properties[show_courses]',
    'label'         => '' ,
    'class'         => '' ,
    'required'      => false,
    'attr'          => '',
    'helper_text'   => 'Show courses',
    'value'         =>  (isset($assistantBuilder->properties['show_courses'])) ? $assistantBuilder->properties['show_courses'] : 0,
    'default'       => 1,
])
</div>

<div class="col-md-6">
    @include('back.layouts.core.forms.multi-select',
    [
        'name'      => 'properties[courses][]',
        'label'     => 'Courses' ,
        'class'     =>'select2' ,
        'required'  => false,
        'attr'      => '',
        'value'     => (isset($assistantBuilder->properties['courses'])) ? $assistantBuilder->properties['courses'] : [],
        'data'      => $courses
    ])
</div>

<div class="col-md-6">
    @include('back.layouts.core.forms.text-input',
    [
    'name' => 'properties[courses_step]',
    'label' => 'Step' ,
    'class' =>'' ,
    'required' => false,
    'attr' => '',
    'value' => (isset($assistantBuilder->properties['courses_step'])) ? $assistantBuilder->properties['courses_step'] : '',
    ])
</div>

<div class="col-md-6">
    @include('back.layouts.core.forms.text-input',
    [
    'name' => 'properties[courses_title]',
    'label' => 'Main Title' ,
    'class' =>'' ,
    'required' => false,
    'attr' => '',
    'value' => (isset($assistantBuilder->properties['courses_title'])) ? $assistantBuilder->properties['courses_title'] : '',
    ])
</div>

<div class="col-md-6">
    @include('back.layouts.core.forms.text-input',
    [
    'name' => 'properties[courses_sub_title]',
    'label' => 'Sub Title' ,
    'class' =>'' ,
    'required' => false,
    'attr' => '',
    'value' => (isset($assistantBuilder->properties['courses_sub_title'])) ? $assistantBuilder->properties['courses_sub_title'] : '',
    ])
</div>
<div class="col-md-6">
    @include('back.layouts.core.forms.text-input',
    [
    'name' => 'properties[courses_error_message]',
    'label' => 'Error Message' ,
    'class' =>'' ,
    'required' => false,
    'attr' => '',
    'value' => (isset($assistantBuilder->properties['courses_error_message'])) ? $assistantBuilder->properties['courses_error_message'] : '',
    ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.text-input',
    [
    'name' => 'properties[courses_help_title]',
    'label' => 'Help title' ,
    'class' =>'' ,
    'required' => false,
    'attr' => '',
    'value' => (isset($assistantBuilder->properties['courses_help_title'])) ? $assistantBuilder->properties['courses_help_title'] : '',
    ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.html', [
        'name'     => 'properties[courses_help_message]',
        'label'    => 'Help text' ,
        'class'    => '' ,
        'required' => false,
        'attr'     => '',
        'value' => (isset($assistantBuilder->properties['courses_help_message'])) ? $assistantBuilder->properties['courses_help_message'] : '',
    ])
</div>