<form method="POST" action="{{ route('plugins.store' , ['plugin' => $pluginName]) }}" enctype="multipart/form-data">

    @csrf
    <div class="row">

    <input type="hidden" class="ajax-form-field" name="plugin" value="{{$pluginName}}">
    
    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input', [
            'name' => 'account_sid',
            'label' => 'Account SID' ,
            'class' => 'ajax-form-field',
            'required' => true,
            'attr' => '',
            'value' =>  isset($plugin->properties['account_sid']) ? 
            $plugin->properties['account_sid'] : ''
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input', [
            'name' => 'auth_token',
            'label' => 'Auth Token' ,
            'class' => 'ajax-form-field',
            'required' => true,
            'attr' => '',
            'value' =>  isset($plugin->properties['auth_token']) ? 
            $plugin->properties['auth_token'] : ''
        ])
    </div>


    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input', [
            'name' => 'call_from',
            'label' => 'Call From' ,
            'class' => 'ajax-form-field',
            'required' => true,
            'attr' => '',
            'value' =>  isset($plugin->properties['call_from']) ? 
            $plugin->properties['call_from'] : ''
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
                'value'         => isset($plugin->published) ? 
            $plugin->published : false,
                'default'       => true
            ])
    </div>
    
</div>
</form>