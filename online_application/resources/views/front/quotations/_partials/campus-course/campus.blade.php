@php
// if Multi Courses selection is enabled
$multi = isset($quotation->properties['enable_multi_program'])? true : false
@endphp
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 px-lg-4 px-md-3">
    <div class="list-item">
        <div class="list-header campus-item" onclick="app.selectCampus({{$campus->id}} , {{($campus->courses->count() == 1) ? $campus->courses->first()->id : null }} , {{$multi}} )" data-campus="{{$campus->id}}">
            <div class="flex-container">
                <span class="fas fa-map-marker-alt"></span>
                
                <h3>{{$campus->title}}</h3>
                
                <label class="checkbox-container">
                    <span class="checkmark"></span>
                </label>

            </div>
        </div>
            <div class="list-content {{($campus->courses->count() == 1) ? 'hidden' : ''}} ">
                <ul class="courses-List">
                    @foreach ($campus->courses as $course)
                        <li class="course-item" onclick="app.selectCourse({{$course->id}} , {{$campus->id}} )"
                            data-course="{{$course->id}}"
                            data-title="{{$course->title}}"
                            data-slug="{{$course->slug}}"
                            >
                            <span class="label">{{$course->title}}</span>
                            <label class="checkbox-container">
                                <span class="checkmark"></span>
                            </label>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="list-footer {{($campus->courses->count() == 1) ? 'hidden' : ''}}">
                <div class="flex-container">
                    <h5>Select course</h5>
                    <span class="fas fa-angle-down"></span>
                </div>
            </div>
       {{--   @endif  --}}
    </div>
</div>