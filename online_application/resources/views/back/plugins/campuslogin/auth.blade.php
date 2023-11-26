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
        'name' => 'url',
        'label' => 'Basic URL' ,
        'class' => 'ajax-form-field',
        'required' => true,
        'attr' => '',
        'value' => isset($plugin->properties['url']) ? $plugin->properties['url'] : ''
        ])
    </div>
    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input', [
        'name' => 'ORGID',
        'label' => 'ORGID' ,
        'class' => 'ajax-form-field',
        'required' => true,
        'attr' => '',
        'value' => isset($plugin->properties['ORGID']) ? $plugin->properties['ORGID'] : ''
        ])
    </div>
    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input', [
        'name' => 'MailListID',
        'label' => 'MailListID' ,
        'class' => 'ajax-form-field',
        'required' => true,
        'attr' => '',
        'value' => isset($plugin->properties['MailListID']) ? $plugin->properties['MailListID'] : ''
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
