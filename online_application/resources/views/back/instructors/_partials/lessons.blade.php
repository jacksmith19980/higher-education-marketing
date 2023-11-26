<div class="tab-pane fade show active" id="lessons" role="tabpanel" aria-labelledby="pills-lessons-tab">
    <div class="card-body">
        <table id="applicant_table" class="table table-striped table-bordered display">
            <thead>
            <tr>
                <th>{{__('Cohort')}}</th>
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
                    <tr data-student-id="{{$lesson->id}}">
                        <td></td>
                        <td>{{$lesson->course->title}}</td>
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
    </div>
</div>
