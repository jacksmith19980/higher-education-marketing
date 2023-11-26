@if($addonsData->count())
    <div class="col-md-12">{{__('Course Addons')}}</div>
    @foreach($addonsData as $addons)
        <div class="col-md-6">
            <div class="custom-control  custom-checkbox" style="margin-top: 7px;">
                <input class="custom-control-input" name="{{$field->name}}[course_addons][{{$course->slug}}][{{$addons['key']}}][]"
                    id="{{$addons['key']}}" value="{{$addons['price']}}" type="checkbox"
                    data-course="{{$course->slug}}"
                    data-syncroute="{{route('cart.addonsCourse', $school)}}"
                    onchange="app.addonsCourse(this{{isset($hash) ? ', "' . $hash . '"' : ''}})"
                @if(isset($selected))
                    {{array_key_exists($addons['key'], $selected)? ' checked' : ' '}}
                @endif
                >
                <label class="custom-control-label" for="{{$addons['key']}}">{{$addons['title']}}</label></div>
        </div>
    @endforeach
@endif
