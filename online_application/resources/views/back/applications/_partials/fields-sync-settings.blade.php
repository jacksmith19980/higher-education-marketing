<div class="col-md-4">
    @include('back.layouts.core.forms.select',
    [
        'name'      	=> 'properties[sync_target]',
        'label'     	=> 'Sync' ,
        'class'     	=> 'ajax-form-field' ,
        'required'  	=> true,
        'attr'      	=> '',
        'data'			=> [
            null            => __('Select Object'),
            'Campus'        => __('Campuses'),
            'Courses'       => __('Courses'),
            'Program'	    => __('Programes'),
            'Dates'	        => __('Intake Dates'),
            'Start_Dates'	=> __('Start Dates'),
        ],
        'value'     	=> isset($field) && optional($field)->properties['sync']['target'] ? optional($field)->properties['sync']['target'] : null
    ])
</div>

<div class="col-md-4">
    @include('back.layouts.core.forms.select',
    [
        'name'      	=> 'properties[sync_source]',
        'label'     	=> 'With' ,
        'class'     	=> 'ajax-form-field' ,
        'required'  	=> true,
        'attr'      	=> '',
        'data'			=> [
            null            => __('Select Object'),
            'Campus'        => __('Campuses'),
            'Courses'       => __('Courses'),
            'Program'	    => __('Programes'),
            'Dates'	        => __('Intake Dates'),
            'Start_Dates'	=> __('Start Dates'),
        ],
        'value'     	=> isset($field) && optional($field)->properties['sync']['source'] ? optional($field)->properties['sync']['source'] : null
    ])
</div>



<div class="col-md-4">
    @php
        $applicationId = $application->id;
    @endphp

    @include('back.layouts.core.forms.select',
    [
        'name'      	=> 'properties[sync_field]',
        'label'     	=> 'Field' ,
        'class'     	=> 'ajax-form-field select2' ,
        'required'  	=> true,
        'attr'      	=> "",
        'data'			=> $fields,
        'value'     	=> isset($field) && optional($field)->properties['sync']['field'] ? optional($field)->properties['sync']['field'] : null
    ])
</div>
