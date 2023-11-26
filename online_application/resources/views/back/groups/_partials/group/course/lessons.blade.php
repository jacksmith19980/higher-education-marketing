<table id="group_course_lessons_table" class="table responsiveDataTable display new-table nowrap w-100 responsive dataTable">
    <thead>
        <tr>
            <th>{{__('Instructor')}}</th>
            <th>{{__('Classroom')}}</th>
            <th>{{__('Date')}}</th>
            <th>{{__('Start Time')}}</th>
            <th>{{__('End Time')}}</th>
            <th>{{__('Held')}}</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($course->lessons as $lesson)
            <tr>
                <td>{{ $lesson->instructor->first_name .' '. $lesson->instructor->last_name }}</td>
                <td>{{ $lesson->classroom->title }}</td>
                <td>{{ $lesson->date }}</td>
                <td>{{ date('h:i A', strtotime($lesson->classroomSlot->start_time)) }}</td>
                <td>{{ date('h:i A', strtotime($lesson->classroomSlot->end_time)) }}</td>
                <td>{{ $lesson->date > now() ? 'Yes' : 'No' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    $('#group_course_lessons_table').DataTable({
        "searching": false,
        "lengthChange": false
    });
</script>