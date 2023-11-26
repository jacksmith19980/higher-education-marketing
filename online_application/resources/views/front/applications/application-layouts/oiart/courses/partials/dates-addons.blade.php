<div class="col-md-12">{{__('Date Addons')}}</div>
@if($addons)
@foreach($addons as $key => $addon)
    <div class="col-md-6">
        <div class="custom-control  custom-radio date_type" style="margin-top: 7px;">
            <input class="custom-control-input radio_date bb" name="{{$field->name}}[date_addons][{{$course->slug}}][{{$key}}][]"
                   id="{{$key}}" value="{{$addon['price']}}" type="checkbox"
                   data-course="{{$course->slug}}"
                   data-date="{{$date_id}}"
                   data-syncroute="{{route('cart.addonsDateCourse', $school)}}"
                   onchange="app.addonsDateCourse(this{{isset($hash) ? ', "' . $hash . '"' : ''}})"
            @if(isset($selected))
                {{array_key_exists($key, $selected)? ' checked' : ' '}}
            @endif
            >
            <label class="custom-control-label" for="{{$key}}">{{$addon['title']}}</label></div>
    </div>
@endforeach
@endif
