<div class="col-md-10">
    <div class="card no-padding card-border">
        <div class="card-header">
            <h4 class="card-title">{{__('Mautic')}}</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    @include('back.layouts.core.forms.text-input', [
                    'name' => 'mautic_default_campus',
                    'label' => 'Default Campus' ,
                    'class' => '',
                    'required' => false,
                    'attr' => '',
                    'value' => isset($settings['auth']['mautic_default_campus'])?
                    $settings['auth']['mautic_default_campus'] : '',
                    ])
                </div>
                <div class="col-md-6">
                    @include('back.layouts.core.forms.text-input', [
                    'name' => 'mautic_default_source',
                    'label' => 'Default Source' ,
                    'class' => '',
                    'required' => false,
                    'attr' => '',
                    'value' => isset($settings['auth']['mautic_default_source'])?
                    $settings['auth']['mautic_default_source'] : '',
                    ])
                </div>
                <div class="col-md-6">
                    @include('back.layouts.core.forms.select', [
                    'name' => 'mautic_push',
                    'label' => 'Push new accounts to Mautic' ,
                    'class' => '',
                    'required' => false,
                    'attr' => '',
                    'placeholder' => 'Select',
                    'data' => [
                    'Yes' => 'Yes',
                    'No' => 'No',
                    ],
                    'value' => isset($settings['auth']['mautic_push'])? $settings['auth']['mautic_push'] :
                    'Yes',
                    ])
                </div>

                <div class="col-md-6">
                    @include('back.layouts.core.forms.select', [
                    'name' => 'mautic_channel',
                    'label' => 'Channel' ,
                    'class' => '',
                    'required' => false,
                    'attr' => '',
                    'placeholder' => 'Select Channel',
                    'data' => MauticHelper::getMauticListField('channel'),
                    'value' => isset($settings['auth']['mautic_channel'])? $settings['auth']['mautic_channel'] :
                    null,
                    ])
                </div>
                <div class="col-md-6">
                    @include('back.layouts.core.forms.select', [
                    'name' => 'mautic_contact_type',
                    'label' => 'Contact Type' ,
                    'class' => '',
                    'required' => false,
                    'attr' => '',
                    'placeholder' => 'Select Contact Type',
                    'data' => MauticHelper::getContactTypes(),
                    'value' => isset($settings['auth']['mautic_contact_type'])?
                    $settings['auth']['mautic_contact_type'] : null,
                    ])
                </div>
                <div class="col-md-6">
                    @include('back.layouts.core.forms.select', [
                    'name' => 'mautic_request_type',
                    'label' => 'Request Type' ,
                    'class' => '',
                    'required' => false,
                    'attr' => '',
                    'placeholder' => 'Select Request Type',
                    'data' => MauticHelper::getMauticListField('request_type'),
                    'value' => isset($settings['auth']['mautic_request_type'])?
                    $settings['auth']['mautic_request_type'] : null,
                    ])
                </div>
            </div>
        </div>
    </div>
</div>
