<div class="col-md-10">
    <div class="card no-padding card-border">
        <div class="card-header">
            <h4 class="card-title">{{__('CampusLogin')}}</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    @include('back.layouts.core.forms.text-input', [
                    'name' => 'campuslogin[campusID]',
                    'label' => 'Default CampusID' ,
                    'class' => '',
                    'required' => false,
                    'attr' => '',
                    'value' => isset($settings['auth']['campuslogin']['campusID'])?
                    $settings['auth']['campuslogin']['campusID'] : '',
                    ])
                </div>
                <div class="col-md-6">
                    @include('back.layouts.core.forms.text-input', [
                    'name' => 'campuslogin[mediaID]',
                    'placeholder' => '' ,
                    'class' => '',
                    'required' => false,
                    'attr' => '',
                    'label' => 'Default MediaID',
                    'data' => MauticHelper::getMauticListField('channel'),
                    'value' => isset($settings['auth']['campuslogin']['mediaID'])? $settings['auth']['campuslogin']['mediaID'] :
                    null,
                    ])
                </div>
                <div class="col-md-6">
                    @include('back.layouts.core.forms.select', [
                    'name' => 'campuslogin[push]',
                    'label' => 'Push new accounts to CampusLogin' ,
                    'class' => '',
                    'required' => false,
                    'attr' => '',
                    'placeholder' => 'Select',
                    'data' => [
                    'Yes' => 'Yes',
                    'No' => 'No',
                    ],
                    'value' => isset($settings['auth']['campuslogin']['push'])? $settings['auth']['campuslogin']['push'] :
                    'Yes',
                    ])
                </div>



            </div>
        </div>
    </div>
</div>
