<div class="col-md-6">
    @include('back.layouts.core.forms.text-input',
    [
        'name'          => 'properties[pricing][number_of_weeks]',
        'label'         => 'Number of weeks' ,
        'class'         => '' ,
        'required'      => true,
        'attr'          => '',
        'value'         => isset($course->properties['pricing']['number_of_weeks']) ? $course->properties['pricing']['number_of_weeks'] : '',
        'data'          => ''
        ])
</div>

<div class="col-md-6">
    @include('back.layouts.core.forms.text-input',
    [
        'name'          => 'properties[pricing][regular_price_per_week]',
        'label'         => 'Regular Price/Week' ,
        'class'         => '' ,
        'required'      => true,
        'attr'          => '',
        'data'         => '',
        'value'          => (isset($course->properties['pricing']['regular_price_per_week'])) ? $course->properties['pricing']['regular_price_per_week'] : '',

        'hint_after'    => isset($settings['school']['default_currency'])? $settings['school']['default_currency'] : 'CAD'
        
        ])
</div>

<div class="col-md-6">
    @include('back.layouts.core.forms.text-input',
    [
        'name'          => 'properties[pricing][hiseason_price_per_week]',
        'label'         => 'High Season Price/Week' ,
        'class'         => '' ,
        'required'      => false,
        'attr'          => '',
        'value'         => (isset($course->properties['pricing']['hiseason_price_per_week'])) ? $course->properties['pricing']['hiseason_price_per_week'] : '',
        'data'          => '',
        'hint_after'    => isset($settings['school']['default_currency'])? $settings['school']['default_currency'] : 'CAD'
        ])
</div>