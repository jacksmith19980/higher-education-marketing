<div class="col-md-12"><strong>{{__('Date and Schedule')}}</strong></div>
@foreach($datesData as $date)
@if (strtotime(substr($date['date'], 0, 10)) >= strtotime(date('Y-m-d')))
    <div class="col-md-6">
        <div class="custom-control custom-radio date_type" style="margin-top: 7px;">

            <input class="custom-control-input" data-field="{{$field->id}}"
                    data-route="{{route('date.addons', $school)}}" data-course="{{$course->slug}}"
                    data-syncroute="{{route('cart.courseDate', $school)}}"
                    onchange="app.dateSelected(this{{isset($hash) ? ', "' . $hash . '"' : ''}})" value="{{$date['id']}}"
                    name="{{$field->name}}[date]" id="{{$date['id']}}" type="radio"
                    @if(isset($selected))
                        {{($selected == $date['id'])? ' checked' : ' '}}
                    @endif
            >

            <label class="custom-control-label" for="{{$date['id']}}">
                {!! $date['date'] !!}
            </label>
        </div>
    </div>
@else

    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="custom-control custom-radio date_type" style="margin-top: 7px; width:100%">
            <input class="custom-control-input radio_date bb"  data-field="{{$field->id}}"
                    data-route="{{route('date.addons', $school)}}" data-course="{{$course->slug}}"
                    data-syncroute="{{route('cart.courseDate', $school)}}"
                    onchange="app.dateSelected(this{{isset($hash) ? ', "' . $hash . '"' : ''}})" value="{{$date['id']}}"
                    name="{{$field->name}}[date][{{$course->slug}}]" id="{{$date['id']}}" type="radio"
                    @if(isset($selected))
                        {{($selected == $date['id'])? ' checked' : ' '}}
                    @endif
            >

            <label class="custom-control-label" for="{{$date['id']}}">
                    {!! $date['date'] !!}
            </label>
        </div>
    </div>
@endif
@endforeach
