<label class="custom-control-label" for="{{$date->start}}_{{$schedule['label']}}">
    <div class="radio-title">{{__("Start Date")}}: </div>
    <p> &nbsp; {{iconv('latin5', 'utf-8', \App\Helpers\Date\DateHelpers::translateDate($date->start))}}</p>

    <div class="radio-title">{{__("End Date")}}: </div>
    <p> &nbsp; {{iconv('latin5', 'utf-8', \App\Helpers\Date\DateHelpers::translateDate($date->end))}} </p>

    @if(!empty(trim($start_time)))
        @if ($schedule = $date->schedule)
            <div class="radio-title">{{__("Schedule")}}: </div>
            <p>&nbsp; {{ $schedule->label }} ({{QuotationHelpers::amOrPm($schedule->start_time)}}-@if(!empty(trim($schedule->end_time))){{QuotationHelpers::amOrPm($schedule->end_time)}}@endif)</p>

        @endif
    @endif
</label>
