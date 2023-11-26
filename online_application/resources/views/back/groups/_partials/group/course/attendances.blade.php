<table id="group_course_attendances_table" class="table responsiveDataTable display new-table nowrap w-100 responsive dataTable">
    <thead>
        <tr>
            <th>{{__('Student')}}</th>
            <th>{{__('Date')}}</th>
            <th>{{__('Classroom')}}</th>
            <th>{{__('Status')}}</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($courseAttendances as $attendance)
            <tr>
                <td>{{ $attendance->student->first_name .' '. $attendance->student->last_name }}</td>
                <td>{{ $attendance->lesson->date }}</td>
                <td>{{ $attendance->lesson->classroom ? $attendance->lesson->classroom->title : '' }}</td>
                <td>{{ ucfirst($attendance->status) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    $('#group_course_attendances_table').DataTable({
        "searching": false,
        "lengthChange": false
    });
</script>