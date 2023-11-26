<form method="POST" name="plugin-auth-form" action="{{ route('plugins.store' , ['plugin' => $pluginName]) }}"
    enctype="multipart/form-data">

    @csrf
    <div class="row">

        <input type="hidden" class="ajax-form-field" name="plugin" value="{{$pluginName}}">

        @include('back.layouts.core.forms.hidden-input',
        [
        'name' => 'type',
        'label' => 'Type' ,
        'class' => 'ajax-form-field',
        'required' => true,
        'attr' => '',
        'value' => isset($plugin->type) ? $plugin->type : 'crm'
        ])

        <div class="col-md-9">
            @include('back.layouts.core.forms.text-input', [
            'name' => 'webhook_url',
            'label' => 'Mautic\'s Base URL',
            'class' => 'ajax-form-field',
            'required' => true,
            'attr' => '',
            'value' => isset($plugin->properties['webhook_url']) ? $plugin->properties['webhook_url'] : ''
            ])
        </div>
        <div class="col-md-3">
            @include('back.layouts.core.forms.select', [
            'name' => 'webhook_method',
            'label' => 'Method',
            'class' => 'ajax-form-field',
            'required' => true,
            'attr' => '',
            'data' => [
            'POST' => 'POST',
            'GET' => 'GET',
            ],
            'value' => isset($plugin->properties['webhook_method']) ? $plugin->properties['webhook_method'] : ''
            ])
        </div>
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
            @include('back.layouts.core.forms.switch',
            [
            'name' => 'is_default',
            'label' => 'Default CRM',
            'class' => 'switch ajax-form-field',
            'required' => true,
            'attr' => 'data-on-text=Yes data-off-text=No',
            'helper_text' => '',
            'value' => isset($plugin->properties['is_default']) ? $plugin->properties['is_default'] : true,
            'default' => true
            ])
        </div>
    </div>
</form>
