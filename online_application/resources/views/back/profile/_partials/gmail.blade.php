<div class="col-md-6">
    @include('back.layouts.core.forms.text-input',
    [
    'name'      => 'settings[mail][username]',
    'label'     => 'Username' ,
    'class'     =>'' ,
    'required'  => true,
    'attr'      => '',
    'value'     => (isset($user->settings["mail"]["username"])) ? $user->settings["mail"]["username"] : "",
    'helper'    => 'Your email address'
    ])
</div>
<div class="col-md-6">
    @include('back.layouts.core.forms.password',
    [
'name'      => 'settings[mail][password]',
    'label'     => 'App Password' ,
    'class'     =>'' ,
    'required'  => true,
    'attr'      => '',
    'value'     => (isset($user->settings["mail"]["password"])) ? $user->settings["mail"]["password"] : "",
    'helper'    => '<a href="https://myaccount.google.com/security" target="_blank">Click here</a> to get your app password'
    ])
</div>
