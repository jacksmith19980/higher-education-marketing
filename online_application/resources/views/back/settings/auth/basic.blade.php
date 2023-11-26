<div class="col-md-6">
    @include('back.layouts.core.forms.color-input',
    [
    'name' => 'background_color',
    'label' => 'Form Background Color' ,
    'class' => '',
    'required' => false,
    'attr' => $disabled,
    'value' => isset($settings['auth']['background_color'])?
    $settings['auth']['background_color'] : '',
    ])
</div>

<div class="col-md-6">
    @include('back.layouts.core.forms.color-input',
    [
    'name' => 'text_color',
    'label' => 'Form Text Color' ,
    'class' => $disabled,
    'required' => false,
    'attr' => $disabled,
    'value' => isset($settings['auth']['text_color'])? $settings['auth']['text_color'] : '',
    ])
</div>
<div class="col-md-6">
    @include('back.layouts.core.forms.select',
    [
    'name' => 'welcome_message_login',
    'label' => 'Show welcome message at login page' ,
    'class' => '' ,
    'required' => false,
    'attr' => $disabled,
    'helper_text' => '',
    'data' => [
    'Yes' => 'Yes',
    'No' => 'No'
    ],
    'value' => isset($settings['auth']['welcome_message_login'])?
    $settings['auth']['welcome_message_login'] :
    'No',
    ])
</div>
<div class="col-md-6">
    @include('back.layouts.core.forms.select',
    [
    'name' => 'return_to_website',
    'label' => 'Show return to website link' ,
    'class' => '' ,
    'required' => false,
    'attr' => $disabled,
    'helper_text' => '',
    'data' => [
    'Yes' => 'Yes',
    'No' => 'No'
    ],
    'value' => isset($settings['auth']['return_to_website'])?
    $settings['auth']['return_to_website'] :
    'No',
    ])
</div>
<div class="col-md-6">
    @include('back.layouts.core.forms.select',
    [
    'name' => 'parent_login',
    'label' => 'Enable Parents login' ,
    'class' => '' ,
    'required' => false,
    'attr' => $disabled,
    'helper_text' => 'Parents can submit applications on behalf of students',
    'data' => [
    'Yes' => 'Yes',
    'No' => 'No'
    ],
    'value' => isset($settings['auth']['parent_login'])? $settings['auth']['parent_login'] :
    'No',
    ])
</div>
<div class="col-md-12">
    @include('back.layouts.core.forms.html',
    [
    'name' => 'welcome_message',
    'label' => 'Welcome Message & Guideline' ,
    'class' => '' ,
    'required' => false,
    'attr' => $disabled,
    'value' => isset($settings['auth']['welcome_message']) ?
    $settings['auth']['welcome_message'] : ''
    ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.html',
    [
    'name' => 'terms_conditions',
    'label' => 'Terms & Conditions' ,
    'class' =>'' ,
    'required' => false,
    'attr' => $disabled,
    'value' => isset($settings['auth']['terms_conditions']) ?
    $settings['auth']['terms_conditions'] : ''
    ])
</div>
