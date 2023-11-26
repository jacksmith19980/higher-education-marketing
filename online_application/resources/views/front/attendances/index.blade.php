@extends('front.instructor._partials.table', [
    'show_buttons' => false,
    'title'        => 'Attendance',
])
@section('table-content')
{{--    @include('front.attendances._partials.index')--}}
<div class="row" id="datatableNewFilter">
    <div class="col-md-6 col-sm-4 col-xs-12" id="lenContainer">
    </div>
    <div class="col-md-3 col-sm-4 col-xs-12" id="calContainer">
        <div class="input-group mr-3">
            <input id="calendarRanges" type="text" class="form-control calendarRanges">
            <div class="input-group-append">
                <span class="input-group-text">
                    <span class="ti-calendar"></span>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-4 col-xs-12" id="filterContainer">
    </div>
</div>
<input type="hidden" name="instructor_id" id="instructor_id" value="{{$instructor->id}}">
<table id="lessons_instructor_table" data-route="{{route('attendances.getLessons', $school)}}" data-i18n="{{$datatablei18n}}"
       class="table table-bordered new-table nowrap display">

    <thead>

    <tr>
        <th>{{__('Title')}}</th>
        <th>{{__('Instructor')}}</th>
        <th>{{__('Classroom')}}</th>
        <th>{{__('Course')}}</th>
        <th>{{__('Date')}}</th>
        <th>{{__('Start Time')}}</th>
        <th>{{__('End Time')}}</th>
        <th>{{__('Held')}}</th>
    </tr>
    </thead>
</table>
@endsection