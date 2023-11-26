<form method="POST" name="plugin-auth-form" action="{{ route('plugins.store' , ['plugin' => $pluginName]) }}"
    enctype="multipart/form-data">

    @csrf

    <div class="row">

        <input type="hidden" class="ajax-form-field" name="plugin" value="{{$pluginName}}">

       {{--   <input type="hidden" class="ajax-form-field" name="scope"
            value="user_login:self+agreement_read:account+agreement_write:account+agreement_send:account+library_read:group+library_write:group+workflow_read:account+workflow_write:account+webhook_read:account+webhook_write:account+webhook_read:account+webhook_retention:account+application_read:account+application_write:account">  --}}
        <div class="col-md-12">
            @include('back.layouts.core.forms.text-input', [
            'name' => 'base_url',
            'label' => 'Base URL' ,
            'class' => 'ajax-form-field',
            'required' => isset($config['fields']['base_url']) ? true : false,
            'attr' => '',
            'value' => isset($plugin->properties['base_url']) ? $plugin->properties['base_url'] : ''
            ])
        </div>

        @if(!isset($plugin->properties['api_key']))
        <div class="col-md-12">
            @include('back.layouts.core.forms.password', [
            'name' => 'api_key',
            'label' => 'API Key' ,
            'class' => 'ajax-form-field',
            'required' => isset($config['fields']['api_key']) ? true : false,
            'attr' => '',
            'value' => isset($plugin->properties['api_key']) ? $plugin->properties['api_key'] : ''
            ])
        </div>

        @else
        <div class="col-md-12">
            @include('back.layouts.core.forms.hidden-input', [
            'name' => 'api_key',
            'label' => 'API Key' ,
            'class' => 'ajax-form-field',
            'required' => isset($config['fields']['api_key']) ? true : false,
            'attr' => '',
            'value' => isset($plugin->properties['api_key']) ? $plugin->properties['api_key'] : ''
            ])

            @include('back.layouts.core.forms.password', [
            'name' => 'api_key',
            'label' => 'API Key' ,
            'class' => 'ajax-form-field',
            'required' => isset($config['fields']['api_key']) ? true : false,
            'attr' => '',
            'value' => '',
            'helper'=> 'Leave empty to keep the saved API Key'
            ])

        </div>
        @endif

       {{--   <div class="mb-3 col-md-12">
            @include('back.layouts.core.forms.text-input', [
            'name' => 'redirect_uri',
            'label' => 'Redirect URL' ,
            'class' => 'ajax-form-field',
            'required' => isset($config['fields']['redirect_uri']) ? true : false,
            'attr' => 'readonly="readonly"',
            'value' => route('plugins.auth' , 'pandadoc'),
            'helper' => __('Add this value to Redirect URIs field in your AdobeSign application')
            ])
        </div>  --}}

        <div class="col-md-6">
            @include('back.layouts.core.forms.switch',
            [
            'name' => 'published',
            'label' => 'Published',
            'class' => 'switch ajax-form-field',
            'required' => true,
            'attr' => 'data-on-text=Yes data-off-text=No',
            'helper_text' => '',
            'value' => isset($plugin->published) ? $plugin->published : false,
            'default' => true
            ])
        </div>

        <div class="col-md-6">
            @include('back.layouts.core.forms.hidden-input',
            [
            'name' => 'type',
            'label' => 'Type' ,
            'class' => 'ajax-form-field',
            'required' => true,
            'attr' => '',
            'value' => isset($plugin->type) ? $plugin->type : 'e-signature'
            ])
        </div>

        {{--  <div class="mt-3 mb-3 text-center col-md-12">
            <center>
                <button type="button" id="integration_details_authButton" class="btn btn-success btn-lg"
                    onclick="app.initiateIntegrationAuthorization(this)" data-auth-link="{{env('ADOBESIGN_AUTH_URL')}}"
                    data-append="redirect_uri,scope,client_id">
                    <i class="fa fa-key "></i>
                    {{__('Save & Authorize App')}}</button>
            </center>
        </div>  --}}


    </div>

</form>
