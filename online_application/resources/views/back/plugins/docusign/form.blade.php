<form method="POST" name="plugin-auth-form" action="{{ route('plugins.store' , ['plugin' => $pluginName]) }}" enctype="multipart/form-data">

    @csrf
    <div class="row">

        <input type="hidden" class="ajax-form-field" name="plugin" value="{{$pluginName}}">

        <div class="col-md-6">
            @include('back.layouts.core.forms.text-input', [
                'name' => 'client_id',
                'label' => 'Integration Key' ,
                'class' => 'ajax-form-field',
                'required' => true,
                'attr' => '',
                'value' =>  isset($plugin->properties['client_id']) ? $plugin->properties['client_id'] : ''
            ])
        </div>

        <div class="col-md-6">
            @include('back.layouts.core.forms.text-input', [
                'name' => 'secret_key',
                'label' => 'Secret Key' ,
                'class' => 'ajax-form-field',
                'required' => true,
                'attr' => '',
                'value' =>  isset($plugin->properties['secret_key']) ? $plugin->properties['secret_key'] : ''
            ])
        </div>
        
        <div class="col-md-6">
            @include('back.layouts.core.forms.text-input', [
                'name' => 'account_id',
                'label' => 'Account GUID' ,
                'class' => 'ajax-form-field',
                'required' => true,
                'attr' => '',
                'value' =>  isset($plugin->properties['account_id']) ? $plugin->properties['account_id'] : ''
            ])
        </div>

        <div class="col-md-6">
            @include('back.layouts.core.forms.text-input', [
                'name' => 'folder_id',
                'label' => 'Folder ID' ,
                'class' => 'ajax-form-field',
                'required' => true,
                'attr' => '',
                'value' =>  isset($plugin->properties['folder_id']) ? $plugin->properties['folder_id'] : ''
            ])
        </div>
        <div class="col-md-6">
            @include('back.layouts.core.forms.text-input', [
                'name' => 'brand_id',
                'label' => 'Brand ID' ,
                'class' => 'ajax-form-field',
                'required' => true,
                'attr' => '',
                'value' =>  isset($plugin->properties['brand_id']) ? $plugin->properties['brand_id'] : ''
            ])
        </div>
        
        <div class="mb-3 col-md-12">
            @include('back.layouts.core.forms.text-input', [
                'name'      => 'redirect_uri',
                'label'     => 'Redirect URL' ,
                'class'     => 'ajax-form-field',
                'required'  => true,
                'attr'      => 'readonly="readonly"',
                'value'     =>  route('plugins.auth' , 'docusign'),
                'helper'    => __('Add this value to Redirect URIs field in your Docusign application')
            ])
        </div>

        <div class="col-md-6">
            @include('back.layouts.core.forms.switch',
                [
                    'name'          => 'published',
                    'label'         => 'Published',
                    'class'         => 'switch ajax-form-field',
                    'required'      => true,
                    'attr'          => 'data-on-text=Yes data-off-text=No',
                    'helper_text'   => '',
                    'value'         => isset($plugin->published) ? $plugin->published : false,
                    'default'       => true
                ])
        </div>

        <div class="col-md-6">
            @include('back.layouts.core.forms.select',
            [
                'name'          => 'type',
                'label'         => 'Type' ,
                'class'         => 'ajax-form-field',
                'required'      => false,
                'placeholder'   => 'Select type',
                'attr'          => '',
                'data'          => [
                        'payment'       => 'Payment',
                        'e-signature'   => 'e-Signature',
                    ],
                'value'         => isset($plugin->type) ? $plugin->type : 'e-signature'
            ])
        </div>

        <div class="mt-3 mb-3 text-center col-md-12">
            <center>
                <button type="button" 
                id="integration_details_authButton" 
                class="btn btn-success btn-lg" 
                onclick="app.initiateIntegrationAuthorization(this)"
                data-auth-link="{{env('DOCUSIGN_AUTH_URL')}}"
                data-append="client_id,redirect_uri"
                >
                <i class="fa fa-key "></i>
                Authorize App</button>
            </center>
        </div>
        

    </div>
</form>



