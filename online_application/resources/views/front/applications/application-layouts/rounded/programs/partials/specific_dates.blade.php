@if(count($datesData) > 0)

    <div class="col-md-12">
        <label>{{__('Select Date')}}<span class="text-danger">*</span></label>
    </div>

    @foreach($datesData as $date)

    <div class="col-md-6 col-12">
        @php
            $schedule = $date['date_schudel'];
        @endphp


        <div class="custom-control custom-radio date_type" style="margin-top: 7px;">

            <input class="custom-control-input radio_date bb" data-field="{{$field->id}}"
                   data-program="{{$program->slug}}"
                   data-registration-fees="{{(isset($program->properties['program_registeration_fee'])) ? $program->properties['program_registeration_fee'] : 0 }}"
                   value="{{$date['start_date']}}_{{$date['date_schudel']['label']}}" name="{{$field->name}}[date]"
                   id="{{$date['start_date']}}_{{$date['date_schudel']['label']}}" type="radio"
                   data-syncroute="{{route('cart.programDate', $school)}}"
                   onchange="app.syncProgramDateOnCart(this)"
                   required
            @if(isset($selected))
                {{($selected == $date['start_date'] . '_' . $date['date_schudel']['label']) ? ' checked' : ' '}}
                    @endif
            >

            @php
                $start_time = $schedule['start_time'];
                $end_time = $schedule['end_time'];
            @endphp

            @include('front.applications.application-layouts.oiart.programs.partials.radio-label-specific_dates')
        </div>
    </div>
    @endforeach
@else
    {{__('No available programs')}}
@endif
