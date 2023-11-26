<div class="col-md-6">
    @include('back.layouts.core.forms.checkbox',
[
    'name'          => 'properties[show_financial]',
    'label'         => '' ,
    'class'         => '' ,
    'required'      => false,
    'attr'          => '',
    'helper_text'   => 'Show financial',
    'value'         =>  (isset($assistantBuilder->properties['show_financial'])) ? $assistantBuilder->properties['show_financial'] : 0,
    'default'       => 1,
])
</div>

<div class="col-md-6">
    @include('back.layouts.core.forms.multi-select',
    [
        'name'      => 'properties[financial][]',
        'label'     => 'Financial Aid' ,
        'class'     =>'select2' ,
        'required'  => false,
        'attr'      => '',
        'value'     => (isset($assistantBuilder->properties['financial'])) ? $assistantBuilder->properties['financial'] : [],
        'data'      => [
            'Student loans'              => 'Student loans',
            'I will pay for this myself' => 'I will pay for this myself',
            'In house financing'         => 'In house financing',
            'Second career'              => 'Second career',
            'Use government aid'         => 'Use government aid'
        ]
    ])
</div>

<div class="col-md-6">
    @include('back.layouts.core.forms.text-input',
    [
    'name' => 'properties[financial_step]',
    'label' => 'Step' ,
    'class' =>'' ,
    'required' => false,
    'attr' => '',
    'value' => (isset($assistantBuilder->properties['financial_step'])) ? $assistantBuilder->properties['financial_step'] : '',
    ])
</div>

<div class="col-md-6">
    @include('back.layouts.core.forms.text-input',
    [
    'name' => 'properties[financial_title]',
    'label' => 'Main Title' ,
    'class' =>'' ,
    'required' => false,
    'attr' => '',
    'value' => (isset($assistantBuilder->properties['financial_title'])) ? $assistantBuilder->properties['financial_title'] : '',
    ])
</div>

<div class="col-md-6">
    @include('back.layouts.core.forms.text-input',
    [
    'name' => 'properties[financial_sub_title]',
    'label' => 'Sub Title' ,
    'class' =>'' ,
    'required' => false,
    'attr' => '',
    'value' => (isset($assistantBuilder->properties['financial_sub_title'])) ? $assistantBuilder->properties['financial_sub_title'] : '',
    ])
</div>
<div class="col-md-6">
    @include('back.layouts.core.forms.text-input',
    [
    'name' => 'properties[financial_error_message]',
    'label' => 'Error Message' ,
    'class' =>'' ,
    'required' => false,
    'attr' => '',
    'value' => (isset($assistantBuilder->properties['financial_error_message'])) ? $assistantBuilder->properties['financial_error_message'] : '',
    ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.text-input',
    [
    'name' => 'properties[financial_help_title]',
    'label' => 'Help title' ,
    'class' =>'' ,
    'required' => false,
    'attr' => '',
    'value' => (isset($assistantBuilder->properties['financial_help_title'])) ? $assistantBuilder->properties['financial_help_title'] : '',
    ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.html', [
        'name'     => 'properties[financial_help_message]',
        'label'    => 'Help text' ,
        'class'    => '' ,
        'required' => false,
        'attr'     => '',
        'value' => (isset($assistantBuilder->properties['financial_help_message'])) ? $assistantBuilder->properties['financial_help_message'] : '',
    ])
</div>