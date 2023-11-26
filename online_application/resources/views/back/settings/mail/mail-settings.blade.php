<h4 class="m-b-15">{{__('Mail')}}</h4>
@php
    $disabled = (!$permissions['edit|settings']) ? 'disabled="disabled"' : '';
@endphp

<form method="POST" action="{{route('settings.store')}}" class="needs-validation" novalidate=""
    enctype="multipart/form-data">
    <div class="row">
        @csrf
        @include('back.layouts.core.forms.hidden-input', [
        'name' => 'group',
        'value' => 'mail',
        'class' => '',
        'required' => '',
        'attr' => '',
        ])

          <div class="col-md-10">
            <div class="card no-padding card-border">
                <div class="card-header">
                    <h4 class="card-title">{{__('Email Verification')}}</h4>
                </div>
                <div class="card-body" style="border:1px solid #f7f7f7;">

                    <div class="row">
                        <div id="validationStatus" class="col-12">

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            @include('back.layouts.core.forms.text-input', [
                            'name' => 'sending_domain',
                            'label' => 'Sending Domain',
                            'class' => 'sending_domain',
                            'required' => false,
                            'attr' => $disabled,
                            'value' => isset($settings['mail']['sending_domain'])? $settings['mail']['sending_domain'] : '',
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('back.layouts.core.forms.text-input', [
                            'name' => 'verification_mailbox',
                            'label' => 'Verification Mailbox',
                            'class' => 'verification_mailbox',
                            'required' => false,
                            'attr' => $disabled,
                            'value' => isset($settings['mail']['verification_mailbox'])? $settings['mail']['verification_mailbox'] : '',
                            ])
                        </div>

                        <div class="col-md-6">
                            @include('back.layouts.core.forms.text-input', [
                            'name' => 'mandrill_username',
                            'label' => 'Mandrill Username',
                            'class' => 'mandrill_username',
                            'required' => false,
                            'attr' => $disabled,
                            'value' => isset($settings['mail']['mandrill_username'])? $settings['mail']['mandrill_username'] : '',
                            ])
                        </div>

                        <div class="col-md-6">
                            @include('back.layouts.core.forms.text-input', [
                            'name' => 'mandrill_api_key',
                            'label' => 'Mandrill API Key',
                            'class' => 'mandrill_api_key',
                            'required' => false,
                            'attr' => $disabled,
                            'value' => isset($settings['mail']['mandrill_api_key'])? $settings['mail']['mandrill_api_key'] : '',
                            ])
                        </div>
                        @if(isset($settings['mail']['sending_domain']) && isset($settings['mail']['verification_mailbox']) && isset($settings['mail']['mandrill_api_key']) && isset($settings['mail']['mandrill_username']))
                            <div class="col-md-12">
                                <button {{$disabled}} class="float-right btn btn-primary" onclick="app.verifySendingDomain(this)">
                                    <i class="fas fa-check"></i>    {{__('Check Domain Verification')}}
                                </button>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-10">
            <div class="card no-padding card-border">
                <div class="card-header">
                    <h4 class="card-title">{{__('Outgoing email information')}}</h4>
                </div>
                <div class="card-body" style="border:1px solid #f7f7f7;">
                    <div class="row">
                        <div data-name="settings_email_name_from" class="col-md-6">
                            @include('back.layouts.core.forms.text-input', [
                            'name' => 'from_name',
                            'label' => 'Send Emails From Name' ,
                            'class' => '',
                            'required' => false,
                            'attr' => $disabled,
                            'value' => isset($settings['mail']['from_name'])? $settings['mail']['from_name'] : '',
                            ])

                        </div>

                        <div data-name="settings_email_address_from" class="col-md-6">
                            @include('back.layouts.core.forms.text-input', [
                            'name' => 'from_email',
                            'label' => 'Send Emails From' ,
                            'class' => '',
                            'required' => false,
                            'attr' => $disabled,
                            'value' => isset($settings['mail']['from_email'])? $settings['mail']['from_email'] : '',
                            ])

                        </div>

                        <div class="col-md-12">
                            @include('back.layouts.core.forms.html', [
                            'name' => 'email_signature',
                            'label' => 'Emails Signature' ,
                            'class' => '' ,
                            'required' => false,
                            'attr' => $disabled,
                            'value' => isset($settings['mail']['email_signature']) ?
                            $settings['mail']['email_signature'] : ''
                            ])
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 'Incoming email --}}
        <div class="col-md-10">
            <div class="card no-padding card-border">
                <div class="card-header">
                    <h4 class="card-title">{{__('Incoming email information')}}</h4>
                </div>
                <div class="card-body" style="border:1px solid #f7f7f7;">
                    <div class="row">
                        <div data-name="settings_email_address_from" class="col-md-12">
                            @include('back.layouts.core.forms.text-input', [
                            'name' => 'to_emails',
                            'label' => 'Send Emails To',
                            'class' => '',
                            'required' => false,
                            'attr' => $disabled,
                            'helper' => 'Multiple emails must be separated by commas',
                            'value' => isset($settings['mail']['to_emails'])? $settings['mail']['to_emails'] : '',
                            ])
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- button--}}
        <div class="col-md-10">
            <button data-name="settings_school_save_button" {{$disabled}} class="float-right btn btn-success">{{__('Save')}}</button>
        </div>
    </div>
    <div id="school_settings_div"></div>
</form>
