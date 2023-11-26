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


    @if(!isset($plugin->properties['api_key']))
    <div class="col-md-12">
        @include('back.layouts.core.forms.password', [
        'name' => 'api_key',
        'label' => 'API Token' ,
        'class' => 'ajax-form-field',
        'required' => true,
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
        'required' => true,
        'attr' => '',
        'value' => isset($plugin->properties['api_key']) ? $plugin->properties['api_key'] : ''
        ])

        @include('back.layouts.core.forms.password', [
        'name' => 'api_key',
        'label' => 'API Key' ,
        'class' => 'ajax-form-field',
        'required' => true,
        'attr' => '',
        'value' => '',
        'helper'=> 'Leave empty to keep the saved API Key'
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
