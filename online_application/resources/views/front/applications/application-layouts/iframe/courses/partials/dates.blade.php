<div class="col-md-12">{{__('Dates')}}</div>
@foreach($datesData as $date)
    <div class="col-md-6">
        <div class="custom-control custom-radio" style="margin-top: 7px;">

            <input class="custom-control-input" data-field="{{$field->id}}"
                   data-route="{{route('date.addons', $school)}}" data-course="{{$course->slug}}"
                   data-syncroute="{{route('cart.courseDate', $school)}}"
                   onchange="app.dateSelected(this{{isset($hash) ? ', "' . $hash . '"' : ''}})" value="{{$date['id']}}"
                   name="{{$field->name}}[date][{{$course->slug}}]" id="{{$date['id']}}" type="radio"
                   @if(isset($selected))
                       {{($selected == $date['id'])? ' checked' : ' '}}
                   @endif
            >

            <label class="custom-control-label" for="{{$date['id']}}">{{$date['date']}}</label>
        </div>
    </div>
@endforeach
