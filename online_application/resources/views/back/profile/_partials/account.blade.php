<div class="row">
    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
        'name' => 'name',
        'label' => 'Name' ,
        'class' =>'' ,
        'required' => true,
        'attr' => '',
        'value' => $user->name
        ])
    </div>
    <div class="col-md-6">
        @include('back.layouts.core.forms.email-input',
        [
        'name' => 'email',
        'label' => 'Email' ,
        'class' =>'' ,
        'required' => true,
        'attr' => '',
        'value' => $user->email
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
        'name' => 'phone',
        'label' => 'Phone' ,
        'class' =>'' ,
        'required' => false,
        'attr' => '',
        'value' => $user->phone
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
        'name' => 'position',
        'label' => 'Position' ,
        'class' =>'' ,
        'required' => false,
        'attr' => '',
        'value' => $user->position
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.select', [
        'name' => 'language',
        'label' => 'Language',
        'class' => '',
        'required' => false,
        'attr' => '',
        'value' => isset($user->language) ? $user->language  : '',
        'placeholder' => 'Use the school\'s default language',
        'data' => SchoolHelper::languages(),
        'helper' => 'Leave Empty to use the school\'s default language'
        ])
    </div>
    </div>
    <div class="row">
    <div class="col-md-6">
        @include('back.layouts.core.forms.password',
        [
        'name' => 'password',
        'label' => 'Password' ,
        'class' =>'' ,
        'required' => false,
        'attr' => 'autocomplete=false',
        'value' => '',
        'helper' => 'Leave blank to keep your current password'
        ])
    </div>
    <div class="col-md-6">
        @include('back.layouts.core.forms.password',
        [
        'name' => 'password_confirmation',
        'label' => 'Confirm Password' ,
        'class' =>'' ,
        'required' => false,
        'attr' => 'autocomplete=false',
        'value' => '',
        'helper' => 'At least one letter, uppercase, number, symbol and a minimum of 8 characters'
        ])
    </div>
    
</div>
