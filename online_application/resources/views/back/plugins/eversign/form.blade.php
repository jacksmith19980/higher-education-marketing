<form method="POST" action="{{ route('plugins.store' , ['plugin' => $pluginName]) }}" enctype="multipart/form-data">

    @csrf
    <div class="row">

    <input type="hidden" class="ajax-form-field" name="plugin" value="{{$pluginName}}">
    
    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input', [
            'name' => 'access_key',
            'label' => 'Access Key' ,
            'class' => 'ajax-form-field',
            'required' => true,
            'attr' => '',
            'value' =>  isset($plugin->properties['access_key']) ? 
            $plugin->properties['access_key'] : ''
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input', [
            'name' => 'business_id',
            'label' => 'Business ID' ,
            'class' => 'ajax-form-field',
            'required' => true,
            'attr' => '',
            'value' =>  isset($plugin->properties['business_id']) ? 
            $plugin->properties['business_id'] : ''
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