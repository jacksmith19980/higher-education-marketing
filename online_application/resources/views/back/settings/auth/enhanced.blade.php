<div class="col-md-6">
    @include('back.layouts.core.forms.color-input',
    [
    'name' => 'background_color_one',
    'label' => 'Background Color I' ,
    'class' => '',
    'required' => false,
    'attr' => $disabled,
    'value' => isset($settings['auth']['background_color_one'])?
    $settings['auth']['background_color_one'] : '',
    ])
</div>
<div class="col-md-6">
    @include('back.layouts.core.forms.color-input',
    [
    'name' => 'background_color_two',
    'label' => 'Background Color II' ,
    'class' => '',
    'required' => false,
    'attr' => $disabled,
    'value' => isset($settings['auth']['background_color_two'])?
    $settings['auth']['background_color_two'] : '',
    ])
</div>

<div class="row">
<!-- login page side title text and color input -->



<div class="col-md-12">
    @include('back.layouts.core.forms.html',
    [
    'name' => 'welcome_login_title_text',
    'label' => 'Login Title Text' ,
    'class' => '' ,
    'required' => false,
    'attr' => $disabled,
    'value' => isset($settings['auth']['welcome_login_title_text']) ?
    $settings['auth']['welcome_login_title_text'] : ''
    ])
</div>
<div class="col-md-12">
    @include('back.layouts.core.forms.color-input',
    [
    'name' => 'login_title_text_color',
    'label' => 'Login Title Text Color' ,
    'class' => '',
    'required' => false,
    'attr' => $disabled,
    'value' => isset($settings['auth']['login_title_text_color'])?
    $settings['auth']['login_title_text_color'] : '',
    ])
</div>

<!-- login page side description text -->

<div class="col-md-12">
    @include('back.layouts.core.forms.html',
    [
    'name' => 'welcome_login_desc_text',
    'label' => 'Login Description Text' ,
    'class' => '' ,
    'required' => false,
    'attr' => $disabled,
    'value' => isset($settings['auth']['welcome_login_desc_text']) ?
    $settings['auth']['welcome_login_desc_text'] : ''
    ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.color-input',
    [
    'name' => 'login_desc_text_color',
    'label' => 'Login Description Text Color' ,
    'class' => '',
    'required' => false,
    'attr' => $disabled,
    'value' => isset($settings['auth']['login_desc_text_color'])?
    $settings['auth']['login_desc_text_color'] : '',
    ])
</div>

<!-- register page side title text -->
<div class="col-md-12">
    @include('back.layouts.core.forms.html',
    [
    'name' => 'welcome_reg_title_text',
    'label' => 'Register Title Text' ,
    'class' => '' ,
    'required' => false,
    'attr' => $disabled,
    'value' => isset($settings['auth']['welcome_reg_title_text']) ?
    $settings['auth']['welcome_reg_title_text'] : ''
    ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.color-input',
    [
    'name' => 'reg_title_text_color',
    'label' => 'Register Title Text Color' ,
    'class' => '',
    'required' => false,
    'attr' => $disabled,
    'value' => isset($settings['auth']['reg_title_text_color'])?
    $settings['auth']['reg_title_text_color'] : '',
    ])
</div>

<!-- register page side description text -->
<div class="col-md-12">
    @include('back.layouts.core.forms.html',
    [
    'name' => 'welcome_reg_desc_text',
    'label' => 'Register Description Text' ,
    'class' => '' ,
    'required' => false,
    'attr' => $disabled,
    'value' => isset($settings['auth']['welcome_reg_desc_text']) ?
    $settings['auth']['welcome_reg_desc_text'] : ''
    ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.color-input',
    [
    'name' => 'reg_desc_text_color',
    'label' => 'Register Description Text Color' ,
    'class' => '',
    'required' => false,
    'attr' => $disabled,
    'value' => isset($settings['auth']['reg_desc_text_color'])?
    $settings['auth']['reg_desc_text_color'] : '',
    ])
</div>
</div>
