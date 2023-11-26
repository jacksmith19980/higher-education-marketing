
<table id="course_dates_prices" class="table responsiveDataTable display new-table nowrap w-100 responsive dataTable">
    <thead>
        <tr>
            <th>{{__('Start Date')}}</th>
            <th>{{__('End Date')}}</th>
            <th>{{__('Schedule')}}</th>
            <th>{{__('Price')}}</th>
            <th>{{__('Status')}}</th>
            <th></th>
        </tr>
    </thead>

    <tbody>
        @if ($course->dates()->count())
            @foreach ($course->dates()->get() as $date)
                <tr data-date-id="{{$date->id}}">
                    @include('back.courses._partials.course.course-date-row' , [
                        'course'    => $course,
                        'date'      => $date
                    ])
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
<script>
    $('#course_dates_prices').DataTable({
        "searching": false,
        "lengthChange": false,
        "columnDefs": [
            {
                "targets": 4,
                "orderable": false
            }
        ]
    });
</script>
