<div class="row">

        @include('back.layouts.core.forms.hidden-input',
        [
            'name' => 'date_type',
            'label' => '' ,
            'class' => 'ajax-form-field' ,
            'required' => true,
            'attr' => '',
            'value' => $course->properties['dates_type'],
        ])

        @include('back.layouts.core.forms.hidden-input',
        [
            'name' => 'key',
            'label' => '' ,
            'class' => 'ajax-form-field' ,
            'required' => true,
            'attr' => '',
            'value' => $key,
        ])

        <div class="col-md-6">
            @include('back.layouts.core.forms.date-input',
            [
            'name'  => 'properties[date]',
            'label' => 'Date' ,
            'class' => 'ajax-form-field' ,
            'required' => true,
            'attr' => '',
            'value' => isset($date['properties']['date']) ? $date['properties']['date']  : '',
            'data' => ''
            ])
        </div>
    </div>
    <div class="row">

        <div class="col-md-6">
            @include('back.layouts.core.forms.time-input',
            [
            'name' => 'properties[start_time]',
            'label' => 'Start time' ,
            'class' => 'ajax-form-field' ,
            'required' => true,
            'attr' => '',
            'value' => isset($date['properties']['start_time']) ? $date['properties']['start_time'] : '',
            'data' => ''
            ])

        </div>

        <div class="col-md-6">
            @include('back.layouts.core.forms.time-input',
            [
            'name' => 'properties[end_time]',
            'label' => 'End time' ,
            'class' => 'ajax-form-field' ,
            'required' => true,
            'attr' => '',
            'value' => isset($date['properties']['end_time']) ? $date['properties']['end_time'] : '',
            'data' => ''
            ])
        </div>

        <div class="col-md-6">
            @include('back.layouts.core.forms.text-input',
            [
            'name' => 'properties[date_price]',
            'label' => 'Price' ,
            'class' => 'ajax-form-field' ,
            'required' => true,
            'attr' => '',
            'value' => isset($date['properties']['date_price']) ? $date['properties']['date_price'] : '',
            'data' => '',
            'hint_after' => isset($settings['school']['default_currency']) ?
            $settings['school']['default_currency'] : 'CAD',
            ])
        </div>
    </div>
<div class="row">
<div class="col-md-12">
<div class="course_dates_repeater"></div>
</div>
</div>
