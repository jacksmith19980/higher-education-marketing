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
    'label'     => 'Password' ,
    'class'     =>'' ,
    'required'  => true,
    'attr'      => '',
    'value'     => (isset($user->settings["mail"]["password"])) ? $user->settings["mail"]["password"] : "",
    'helper'    => '<a href="https://account.live.com/proofs/manage/additional?mkt=en-US&refd=account.microsoft.com&refp=security&uaid=6fd179194b5a468fa88dfe8c0583b9b9" target="_blank">Click here</a> to get your app password'
    ])
</div>
