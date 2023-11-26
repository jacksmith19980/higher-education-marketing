@if(count($datesData) > 0)

    <div class="col-md-12">
        <label>{{__('Select Date')}}<span class="text-danger">*</span></label>
    </div>

    @foreach($datesData as $date)

    <div class="col-md-6 col-12">
        <div class="custom-control custom-radio date_type" style="margin-top: 7px;">

            <input class="custom-control-input radio_date" data-field="{{$field->id}}"
                   data-program="{{$program->slug}}"
                   data-registration-fees="{{(isset($program->properties['program_registeration_fee'])) ? $program->properties['program_registeration_fee'] : 0 }}"
                   value="{{$date['start_date']}}_{{$date['date_schudel']}}" name="{{$field->name}}[date]"
                   id="{{$date['start_date']}}_{{$date['date_schudel']}}" type="radio"
                   data-syncroute="{{route('cart.programDate', $school)}}"
                   onchange="app.syncProgramDateOnCart(this)"
                   required
            @if(isset($selected))
                {{($selected == $date['start_date'] . '_' . $date['date_schudel']) ? ' checked' : ' '}}
                    @endif
            >

            @php
                $key = array_search($date['date_schudel'], $settings['calendar']['schedule_label']);
                $start_time = $settings['calendar']['schedule_start_time'][$key];
                $end_time = $settings['calendar']['schedule_end_time'][$key];
            @endphp

            @include('front.applications.application-layouts.iframe.programs.partials.radio-label-specific_dates')
        </div>
    </div>
    @endforeach
@else
    {{__('No available programs')}}
@endif