@php
    $disabled = (!$permissions['edit|settings']) ? 'disabled="disabled"' : '';
@endphp

<h4 class="m-b-15">{{__('Login and Register Settings')}}</h4>

<form method="POST" action="{{route('settings.store')}}" class="needs-validation" novalidate=""
    enctype="multipart/form-data">
    <div class="row">
        @include('back.layouts.core.forms.hidden-input',
        [
        'name' => 'group',
        'value' => 'auth',
        'class' => '',
        'required' => '',
        'attr' => $disabled,
        ])
        @csrf

        @include('back.settings.auth.main' , [
        'settings'  => $settings,
        'disabled'  => $disabled
        ])



        @include('back.settings.auth.form-fields' , [
        'settings'  => $settings,
        'disabled'  => $disabled
        ])

        @include('back.settings.auth.welcome-email' , [
        'settings'  => $settings,
        'disabled'  => $disabled
        ])

        @if (in_array('mautic' , $crm))
            @include('back.settings._partials.integrations.mautic' , [
            'settings'  => $settings,
            'disabled'  => $disabled
            ])
        @endif

        @if (in_array('hubspot' , $crm))
            @include('back.settings._partials.integrations.hubspot' , [
            'settings'  => $settings,
            'disabled'  => $disabled
            ])
        @endif

        @if (in_array('campuslogin' , $crm))
            @include('back.settings._partials.integrations.campuslogin' , [
            'settings'  => $settings,
            'disabled'  => $disabled
            ])
        @endif

        <div class="col-md-10">
            <div class="card no-padding card-border">
                <div class="card-header">
                    <h4 class="card-title">{{__('reCaptcha')}}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @include('back.layouts.core.forms.select', [
                            'name' => 'enable_recaptcha',
                            'label' => 'Enable reCaptcha' ,
                            'class' => 'settings_enable_recaptcha',
                            'required' => false,
                            'attr' => $disabled,
                            'placeholder' => '',
                            'data' => [
                            'Yes' => 'Yes',
                            'No' => 'No'
                            ],
                            'value' => isset($settings['auth']['enable_recaptcha'])?
                            $settings['auth']['enable_recaptcha'] : 'No',
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('back.layouts.core.forms.text-input',
                            [
                            'name' => 'recaptcha_site_key',
                            'label' => 'reCaptcha Site Key' ,
                            'class' => '',
                            'required' => false,
                            'attr' => $disabled,
                            'value' => isset($settings['auth']['recaptcha_site_key'])?
                            $settings['auth']['recaptcha_site_key'] : ''
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('back.layouts.core.forms.text-input',
                            [
                            'name' => 'recaptcha_site_secret',
                            'label' => 'reCaptcha Site Secret' ,
                            'class' => '',
                            'required' => false,
                            'attr' => $disabled,
                            'value' => isset($settings['auth']['recaptcha_site_secret'])?
                            $settings['auth']['recaptcha_site_secret'] :
                            ''
                            ])
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-10">
            <button data-name="settings_login_save_button" {{$disabled}} class="float-right btn btn-success">{{__('Save')}}</button>
        </div>
    </div>
    <div id="login_settings_div"></div>
</form>
