<h4 class="m-b-15">{{__('Settings')}}</h4>
@php
    $disabled = (!$permissions['edit|settings']) ? 'disabled="disabled"' : '';
@endphp

<form method="POST" action="{{route('settings.store')}}" class="needs-validation" novalidate=""
    enctype="multipart/form-data">
    <div class="row">
        @csrf
        @include('back.layouts.core.forms.hidden-input', [
        'name' => 'group',
        'value' => 'school',
        'class' => '',
        'required' => '',
        'attr' => '',
        ])
        {{--Main--}}
        <div class="col-md-10">
            <div class="card no-padding card-border" >
                <div class="card-header">
                    <h4 class="card-title">{{__('Main')}}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div data-name="settings_language" class="col-md-6">
                            @include('back.layouts.core.forms.select', [
                            'name' => 'locale',
                            'label' => 'Language',
                            'class' => 'settings_language',
                            'required' => true,
                            'attr' => $disabled,
                            'value' => isset($settings['school']['locale'])? $settings['school']['locale'] : "en",
                            'data' => SchoolHelper::getDefaultLanguages()
                            ])
                        </div>
                        <div data-name="settings_website" class="col-md-6">
                            @include('back.layouts.core.forms.text-input', [
                            'name' => 'website',
                            'label' => 'Website' ,
                            'class' => '',
                            'required' => false,
                            'attr' => $disabled,
                            'value' => isset($settings['school']['website'])? $settings['school']['website'] : '',
                            'placeholder' => 'http://',
                            ])
                        </div>

                        <div class="col-md-6">
                            @include('back.layouts.core.forms.text-input', [
                            'name' => 'domain',
                            'label' => 'Application Domain' ,
                            'class' => '',
                            'required' => false,
                            'attr' => $disabled,
                            'value' => isset($settings['school']['domain'])? $settings['school']['domain'] : '',
                            'placeholder' => 'http://',
                            ])

                        </div>

                        <div class="col-md-6">
                            @include('back.layouts.core.forms.select', [
                            'name' => 'school_type',
                            'label' => 'School Type' ,
                            'class' => '',
                            'required' => false,
                            'attr' => $disabled,
                            'value' => isset($settings['school']['school_type']) ? $settings['school']['school_type'] :
                            '',
                            'data' => [
                                    'Business School' => 'Business School',
                                    'Career College' => 'Career College',
                                    'College' => 'College',
                                    'Community College' => 'Community College',
                                    'K-12' => 'K-12',
                                    'Language School' => 'Language School',
                                    'Sixth form' => 'Sixth form',
                                    'Summer Camps' => 'Summer Camps',
                                    'University' => 'University',
                                    'University/ESL' => 'University/ESL',
                                    'Association' => 'Association',
                                    'Digital Marketing Agency' => 'Digital Marketing Agency',
                                    'Gov' => 'Gov',
                                    'International Recruitment Agency' => 'International Recruitment Agency',
                                    'Collégial' => 'Collégial',
                                    'Other' => 'Other',
                            ]
                            ])

                        </div>

                        <div class="col-md-12">
                            @include('back.layouts.core.forms.text-input', [
                            'name' => 'address',
                            'label' => 'School Address' ,
                            'class' => '',
                            'required' => false,
                            'attr' => $disabled,
                            'value' => isset($settings['school']['address'])? $settings['school']['address'] : '',
                            ])

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-10">
            <div class="card no-padding card-border">
                <div class="card-header">
                    <h4 class="card-title">{{__('Currency')}}</h4>
                </div>
                <div class="card-body" style="border:1px solid #f7f7f7;">
                    <div class="row">
                        <div data-name="settings_default_currency" class="col-md-6">
                            @include('back.layouts.core.forms.select', [
                            'name' => 'default_currency',
                            'label' => 'Default Currency' ,
                            'class' => 'settings_default_currency',
                            'required' => false,
                            'attr' => 'onchange=app.changeDefaultCurrencyHint(this) ' . $disabled,
                            'value' => isset($settings['school']['default_currency'])?
                            $settings['school']['default_currency'] : 'CAD',
                            'placeholder' => '',
                            'data' => QuotationHelpers::getDefaultCurrency()
                            ])

                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Global Custom css--}}
        {{-- <div class="col-md-10">
            <div class="card no-padding card-border">
                <div class="card-header">
                    <h4 class="card-title">{{__('Global Custom css')}}</h4>
                </div>
                <div class="card-body" style="border:1px solid #f7f7f7;">
                    <div class="row">
                        <div class="col-md-12">
                            @include('back.layouts.core.forms.text-area', [
                            'name' => "global_custom_css",
                            'label' => 'Custom CSS' ,
                            'class' => 'css-editor' ,
                            'value' => isset($settings['school']['global_custom_css']) ?
                            $settings['school']['global_custom_css'] : '',
                            'required' => false,
                            'attr' => ''
                            ])
                            <script>
                                CodeMirror.fromTextArea( document.getElementById("global_custom_css") , {
                                    lineNumbers: true,
                                    theme: 'material',
                                    smartIndent: true
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        {{-- 'Outgoing email --}}
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
                            'value' => isset($settings['school']['from_name'])? $settings['school']['from_name'] : '',
                            ])

                        </div>

                        <div data-name="settings_email_address_from" class="col-md-6">
                            @include('back.layouts.core.forms.text-input', [
                            'name' => 'from_email',
                            'label' => 'Send Emails From' ,
                            'class' => '',
                            'required' => false,
                            'attr' => $disabled,
                            'value' => isset($settings['school']['from_email'])? $settings['school']['from_email'] : '',
                            ])

                        </div>

                        <div class="col-md-12">
                            @include('back.layouts.core.forms.html', [
                            'name' => 'email_signature',
                            'label' => 'Emails Signature' ,
                            'class' => '' ,
                            'required' => false,
                            'attr' => $disabled,
                            'value' => isset($settings['school']['email_signature']) ?
                            $settings['school']['email_signature'] : ''
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
                            'value' => isset($settings['school']['to_emails'])? $settings['school']['to_emails'] : '',
                            ])
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Invoice--}}
        <div class="col-md-10">
            <div class="card no-padding card-border">
                <div class="card-header">
                    <h4 class="card-title">{{__('Invoice')}}</h4>
                </div>
                <div class="card-body" style="border:1px solid #f7f7f7;">
                    <div class="row">
                        <div class="col-md-12">
                            @include('back.layouts.core.forms.html', [
                            'name' => 'school_details',
                            'label' => 'School Information' ,
                            'class' => '' ,
                            'required' => false,
                            'attr' => $disabled,
                            'value' => isset($settings['school']['school_details']) ?
                            $settings['school']['school_details'] : ''
                            ])
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- E-Signature --}}
        <div class="col-md-10">
            <div class="card no-padding card-border">
                <div class="card-header">
                    <h4 class="card-title">{{__('E-signature')}}</h4>
                </div>
                <div class="card-body" style="border:1px solid #f7f7f7;">
                    <div class="row">
                        <div class="col-md-12">
                            @include('back.layouts.core.forms.select', [
                            'name' => 'esignature_service_provider',
                            'label' => 'E-signature Service Provider' ,
                            'class' => 'settings_esignature_service_provider',
                            'required' => false,
                            'attr' => $disabled,
                            'value' => isset($settings['school']['esignature_service_provider'])?
                            $settings['school']['esignature_service_provider'] : null,
                            'placeholder' => 'Select Service',
                            'data' => PluginsHelper::getPluginsList('e-signature')
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
