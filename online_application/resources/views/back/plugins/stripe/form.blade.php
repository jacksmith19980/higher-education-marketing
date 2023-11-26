<form method="POST" action="{{ route('plugins.store' , ['plugin' => $pluginName]) }}" enctype="multipart/form-data">

    @csrf
    <div class="row">

        <input type="hidden" class="ajax-form-field" name="plugin" value="{{$pluginName}}">

        <div class="col-md-6">
            @include('back.layouts.core.forms.text-input', [
                'name' => 'public_api_key',
                'label' => 'Public API Key' ,
                'class' => 'ajax-form-field',
                'required' => true,
                'attr' => '',
                'value' =>  isset($plugin->properties['public_api_key']) ? $plugin->properties['public_api_key'] : ''
            ])
        </div>

        <div class="col-md-6">
            @include('back.layouts.core.forms.text-input', [
                'name' => 'secret_api_key',
                'label' => 'Secret API Key' ,
                'class' => 'ajax-form-field',
                'required' => true,
                'attr' => '',
                'value' =>  isset($plugin->properties['secret_api_key']) ? $plugin->properties['secret_api_key'] : ''
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
                'data'          => ['payment' => 'Payment'],
                'value'         => isset($plugin->type) ? $plugin->type : false
            ])
        </div>

    </div>
</form>