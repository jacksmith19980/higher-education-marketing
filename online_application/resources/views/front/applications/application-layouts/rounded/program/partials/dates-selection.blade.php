@if($dates && $dates->count())

<div class="col-md-12">
    <label>{{__('Select Schedule')}}<span class="text-danger">*</span></label>
</div>

@foreach($dates as $date)

<div class="col-md-6 col-12">
    @php
        $schedule = $date->schedule->toArray();
    @endphp
    <div class="custom-control custom-radio date_type" style="margin-top: 7px;">

        <input class="custom-control-input radio_date bb"
            data-field="{{$field->id}}"
                data-program="{{$program->slug}}"
                data-registration-fees="{{(isset($program->properties['program_registeration_fee'])) ? $program->properties['program_registeration_fee'] : 0 }}"
                value="{{$date->id}}"
                name="{{$field->name}}[date]"
                id="{{$date->start}}_{{$schedule['label']}}"
                type="radio"
                data-syncroute="{{route('cart.programDate', $school)}}"
                onchange="app.syncProgramDateOnCart(this)"
                required
        @if(isset($selected))
            {{($selected == $date->start . '_' . $schedule['label']) ? ' checked' : ' '}}
                @endif
        >

        @php
            $start_time = $schedule['start_time'];
            $end_time = $schedule['end_time'];
        @endphp

        @include('front.applications.application-layouts.rounded.program.partials.radio-label-specific_dates')
    </div>
</div>
@endforeach
@else
<div class="col-md-12">
    <div class="alert alert-warning">
        <strong>{{__('No Results Found')}}</strong>
        <span class="d-block">{{__('there are no avaiable intake dates to show!')}}</span>
    </div>
</div>

@endif
