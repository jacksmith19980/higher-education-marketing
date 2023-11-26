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

        <div class="col-md-12">
            @include('back.layouts.core.forms.text-input', [
            'name' => 'base_url',
            'label' => 'Mautic\'s Base URL',
            'class' => 'ajax-form-field',
            'required' => true,
            'attr' => '',
            'value' => isset($plugin->properties['base_url']) ? $plugin->properties['base_url'] : ''
            ])
        </div>

        <div class="col-md-6">
            @include('back.layouts.core.forms.text-input', [
            'name' => 'username',
            'label' => 'Username' ,
            'class' => 'ajax-form-field',
            'required' => true,
            'attr' => '',
            'value' => isset($plugin->properties['username']) ? $plugin->properties['username'] : ''
            ])
        </div>

        @if(!isset($plugin->properties['password']))
        <div class="col-md-6">
            @include('back.layouts.core.forms.password', [
            'name' => 'password',
            'label' => 'Password' ,
            'class' => 'ajax-form-field',
            'required' => true,
            'attr' => '',
            'value' => isset($plugin->properties['password']) ? $plugin->properties['password'] : ''
            ])
        </div>

        @else
        <div class="col-md-6">
            @include('back.layouts.core.forms.hidden-input', [
            'name' => 'password',
            'label' => 'Password' ,
            'class' => 'ajax-form-field',
            'required' => true,
            'attr' => '',
            'value' => isset($plugin->properties['password']) ? $plugin->properties['password'] : ''
            ])

            @include('back.layouts.core.forms.password', [
            'name' => 'password',
            'label' => 'Password' ,
            'class' => 'ajax-form-field',
            'required' => true,
            'attr' => '',
            'value' => '',
            'helper'=> 'Leave empty to keep the saved Password'
            ])

        </div>
        @endif

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
