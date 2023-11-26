@php

if(!isset($courses)){

    

    $courses = QuotationHelpers::getCoursesSelection($quotation->properties['courses'],$campuses);



}   

@endphp



@if (isset($courses) &&  count($courses) > 1)

    

<div class="form-group">

    @include('back.layouts.core.forms.checkbox-group',

    [

            'name'      => "courses[]",

            'label'     => __('Please select which programme you would like to book.') ,

            'class'     => '' ,

            'required'  => true,

            'attr'      => 'onchange=app.courseSelected($(this)) data-quotation='.$quotation->id,

            'value'     => '',

            'placeholder' => __('Select Program'),

            'data'      => $courses,

            ])

</div>



@else



@endif