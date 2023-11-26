<table id="applicant_table" class="table table-striped table-bordered display">

    <thead>

    <tr>
{{--        <th>{{__('Type')}}</th>--}}

        <th>{{__('Entity')}}</th>

        <th>{{__('Course')}}</th>

        <th>{{__('Date')}}</th>

        <th>{{__('Start Time')}}</th>

        <th>{{__('End Time')}}</th>

        <th>{{__('Attended')}}</th>

    </tr>

    </thead>

    <tbody>

    @if ($lessons)
        @foreach ($lessons as $lesson)

{{--            @php--}}
{{--                $type = explode('\\', $lesson->lessoneable_type);--}}
{{--            @endphp--}}

            <tr data-student-id="{{$lesson->id}}">
{{--                <td>{{end($type)}}</td>--}}
                <td><a href="{{route('attendances.create' , [
                                        'school' => $school,
                                        'lesson' => $lesson
                                    ] )}}">{{$lesson->lessoneable->title}}</a></td>
                <td>{{isset($lesson->course) ? $lesson->course->title : ' '}}</td>
                <td>{{$lesson->date}}</td>
                <td>{{QuotationHelpers::amOrPm($lesson->classroomSlot->start_time)}}</td>
                <td>{{QuotationHelpers::amOrPm($lesson->classroomSlot->end_time)}}</td>
                <td>{{$lesson->attended()}}</td>
            </tr>

        @endforeach

    @endif

    </tbody>

</table>


<table>
    <tr>
        <td>
            {{ $lessons->links() }}
        </td>
    </tr>
</table>