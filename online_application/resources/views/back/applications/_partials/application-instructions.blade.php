<div class="row">
    <div class="col-md-6">
        @include('back.layouts.core.forms.checkbox',
        [
        'name' => 'properties[show_instructions]',
        'label' => false ,
        'class' => '' ,
        'required' => false,
        'attr' => '',
        'helper_text' => 'Show application instructions',
        'value' => (isset($application->properties['show_instructions']))? $application->properties['show_instructions'] : 0,
        'default' => 1,
        'helper' => 'Show application instructions'
        ])
    </div>
    <div class="col-md-12">
        @include('back.layouts.core.forms.text-area',
        [
        'name' => 'properties[instructions]',
        'label' => 'Instructions' ,
        'class' =>'' ,
        'value' => (isset($application->properties['instructions']))? $application->properties['instructions'] :
        '',
        'required' => false,
        'attr' => ''
        ])
    </div>
</div>
