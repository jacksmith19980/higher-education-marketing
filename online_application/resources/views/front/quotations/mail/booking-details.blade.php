@foreach ($courses as $course)
    <h4>{{$course['title']}} </h4>
        @foreach ($course['dates'] as $date)
            <strong>
                {{ QuotationHelpers::formateStartEndDates($date['start'].':'.$date['end']) }}
            </strong>
        @endforeach
@endforeach