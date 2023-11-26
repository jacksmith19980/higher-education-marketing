@php
    $courses = [];
    $campuses = ApplicationHelpers::getCampusesList();
    $hash = md5(time());
@endphp
<div id="{{$hash}}" style="display: flex; flex-wrap: wrap;" class="col-md-12">

    <div class="col-md-12">
        <button type="button" class="close float-right" aria-label="Close" data-hash="{{$hash}}" id="close-{{$hash}}" data-cleanroute="{{route('cart.deleteCourse', $school)}}" onclick="app.deleteCourse(this)">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="w-100"></div>

    @if($field->properties['showCampus'] && count($campuses) > 1)

        <div class="col-md-6">
            @include('back.layouts.core.forms.select',
            [
                'name'      => $field->name . '[campus][' . $hash . ']',
                'label'     => 'Campuses',
                'class'     => 'ajax-form-field' ,
                'required'  => true,
                'attr'      => "onchange=app.searchCoursesSeconds(this,'".route('courses.campus', $school) ."') data-field=".$field->id . " data-fieldName=".$field->name . " data-hash=".$hash . " data-cleanroute=".route('cart.deleteCourse', $school),
                'value'     => '',
                'placeholder' => "Select a Campus",
                'data' => $campuses
            ])
        </div>
    @else
        @php
            if(empty($field->data)) {
                $courses = ApplicationHelpers::getCoursesList();
            }
            else {
                $courses = $field->data;
            }

        @endphp
    @endif

    <div class="col-md-6">
        @include('back.layouts.core.forms.select',
        [
            'name'      => $field->name . '[courses][' . $hash . ']',
            'label'     => 'Courses',
            'class'     => 'ajax-form-field',
            'required'  => true,
            'attr'      =>  "onchange=app.searchDatesAddonsSeconds(this,'".route('courses.startDatesAndAddons', $school) ."') data-route=".route('date.addons', $school) . " data-field=".$field->id . " data-fieldName=".$field->name . " data-hash=".$hash,
            'value'     => '',
            'placeholder' => "Select a Course",
            'data'      => $courses
        ])
    </div>

    <div class="col-md-12">
        <div class="start-date-{{$hash}} row form-group">
        </div>
    </div>

    <div class="col-md-12">
        <div class="course-addons-{{$hash}} row form-group">
        </div>
    </div>

    <div class="col-md-12">
        <div class="date-addons-{{$hash}} row form-group">
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {
        $(".select2").select2();
    });
</script>
