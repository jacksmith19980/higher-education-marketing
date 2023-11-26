<div class="row">
    <div class="col-md-2 offset-10 m-b-30">
        @include('back.layouts.core.helpers.add-elements-button' , [
            'text'          => 'Add Dates',
            'action'        => 'course.addSpecificDates',
            'container'     => '#specific_dates_wrapper'
        ])
    </div>
</div>

<div class="row" id="specific_dates_wrapper"></div>


<div class="row">
    @if (isset($course->properties['start_date']) && isset($course->properties['end_date']) && !empty($course->properties['start_date']))
    
    @foreach ($course->properties['start_date'] as $key=>$startDate )
        <div class="col-md-4">
            @include('back.layouts.core.forms.date-input',
        [
            'name'          => 'properties[start_date][]',
            'label'         => 'Start date' ,
            'class'         => '' ,
            'required'      => false,
            'attr'          => '',
            'value'         => isset($startDate) ? $startDate : '',
            'data'          => ''
            ])
        </div>
        
        <div class="col-md-4">
            @include('back.layouts.core.forms.date-input',
        [
            'name'          => 'properties[end_date][]',
            'label'         => 'End date' ,
            'class'         => '' ,
            'required'      => false,
            'attr'          => '',
            'value'         => isset($course->properties['end_date'][$key]) ? $course->properties['end_date'][$key] : '',
            'data'          => ''
            ])
        </div>

        <div class="col-md-3">
         @include('back.layouts.core.forms.text-input',
        [
            'name'          => 'properties[date_price][]',
            'label'         => 'Price' ,
            'class'         => '' ,
            'required'      => false,
            'attr'          => '',
            'value'         => isset($course->properties['date_price'][$key]) ? $course->properties['date_price'][$key] : '',
            'data'          => '',
            'hint_after'    => 'CAD',
            ])
        </div>

        <div class="col-md-1 action_wrapper">
            <div class="form-group m-t-27">
                            <button class="btn btn-danger" type="button" onclick="">
                                <i class="fa fa-minus"></i>
                            </button>
            </div>
        </div>

    @endforeach    
    @endif
</div>

