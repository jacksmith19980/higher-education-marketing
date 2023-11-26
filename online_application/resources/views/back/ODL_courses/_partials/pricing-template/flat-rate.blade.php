<div class="col-md-6">
    @include('back.layouts.core.forms.text-input',
    [
        'name'          => 'properties[number_of_weeks]',
        'label'         => 'Number of weeks' ,
        'class'         => '' ,
        'required'      => true,
        'attr'          => '',
        'value'         => isset($course->properties['number_of_weeks']) ? $course->properties['number_of_weeks'] : '',
        'data'          => ''
        ])
</div>

<div class="col-md-6">
    @include('back.layouts.core.forms.text-input',
    [
        'name'          => 'properties[regular_price_per_week]',
        'label'         => 'Regular Price/Week' ,
        'class'         => '' ,
        'required'      => true,
        'attr'          => '',
        'data'         => '',
        'value'          => (isset($course->properties['regular_price_per_week'])) ? $course->properties['regular_price_per_week'] : '',

        'hint_after'    => 'CAD'
        
        ])
</div>

<div class="col-md-6">
    @include('back.layouts.core.forms.text-input',
    [
        'name'          => 'properties[hiseason_price_per_week]',
        'label'         => 'High Season Price/Week' ,
        'class'         => '' ,
        'required'      => false,
        'attr'          => '',
        'value'         => (isset($course->properties['hiseason_price_per_week'])) ? $course->properties['hiseason_price_per_week'] : '',
        'data'          => '',
        'hint_after'    => 'CAD'
        ])
</div>