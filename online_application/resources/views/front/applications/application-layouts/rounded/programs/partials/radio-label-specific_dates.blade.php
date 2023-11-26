<label class="custom-control-label" for="{{$date['start_date']}}_{{$date['date_schudel']['label']}}">
    <div class="radio-title">{{__("Start Date")}}: </div>
    <p> &nbsp; {{iconv('latin5', 'utf-8', \App\Helpers\Date\DateHelpers::translateDate($date['start_date']))}}</p>

    <div class="radio-title">{{__("End Date")}}: </div>
    <p> &nbsp; {{iconv('latin5', 'utf-8', \App\Helpers\Date\DateHelpers::translateDate($date['end_date']))}} </p>
    @if(!empty(trim($start_time)))
    <div class="radio-title">{{__("Start Time")}}: </div>
    <p> &nbsp; {{\App\Helpers\Quotation\QuotationHelpers::amOrPm($start_time)}}</p>
    @endif
    @if(!empty(trim($end_time)))
    <div class="radio-title">{{__("End Time")}}: </div>
    <p> &nbsp; {{\App\Helpers\Quotation\QuotationHelpers::amOrPm($end_time)}}</p>
    @endif
</label>
