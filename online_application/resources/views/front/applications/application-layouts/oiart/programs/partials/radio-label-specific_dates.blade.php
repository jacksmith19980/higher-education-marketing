<label class="custom-control-label" for="{{$date['start_date']}}_{{$date['date_schudel']['label']}}">
    <div class="radio-title">{{__("Start Date")}}: </div>
    <p> &nbsp; {{iconv('latin5', 'utf-8', \App\Helpers\Date\DateHelpers::translateDate($date['start_date']))}}</p>

    <div class="radio-title">{{__("End Date")}}: </div>
    <p> &nbsp; {{iconv('latin5', 'utf-8', \App\Helpers\Date\DateHelpers::translateDate($date['end_date']))}} </p>
    @if(!empty(trim($start_time)))
        @if (isset($date['date_schudel']))
            <div class="radio-title">{{__("Schedule")}}: </div>
            <p>&nbsp; {{ $date['date_schudel']['label'] }} ({{QuotationHelpers::amOrPm($start_time)}}-@if(!empty(trim($end_time))){{QuotationHelpers::amOrPm($end_time)}}@endif)</p>

        @endif
    @endif

</label>
