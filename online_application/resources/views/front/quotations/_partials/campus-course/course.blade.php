@php
    // if Multi Courses selection is enabled
    $multi = isset($quotation->properties['enable_multi_program'])? true : false
@endphp

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 px-lg-3 px-md-3">
    <div class="list-item has-no-content">
        <div class="list-header"
             data-course="{{$course->id}}"
             data-title="{{$course->title}}"
             data-fee="{{$course->properties['course_registeration_fee']}}"
             data-slug="{{$course->slug}}"
             onclick="app.selectCourse({{$course->id}} , {{$campus->id}} , {{$multi}} )"
        >
            <div class="flex-container">
                <span class="fas fa-graduation-cap"></span>
                <h4>{{$course->title}}</h4>
                <label class="checkbox-container">
                    <span class="checkmark"></span>
                </label>
            </div>
        </div>
    </div>
</div>