<div>
    <div style="float:right; margin-right: 5%">
        <a href="javascript:get_back_to_all_lessons({{$group->id}});">
            &larr; {{__('BACK TO ATTENDANCE')}}
        </a>
    </div>
    <div style="margin-left: 2%">
        <table>
            <tr>
                <td>{{__('DATE')}}:</td>
                <td style="padding-left: 30px">{{$lesson->date}}</td>
            </tr>
            <tr>
                <td>{{__('CLASSROOM')}}:</td>
                <td style="padding-left: 30px">{{$lesson->classroom->title}}</td>
            </tr>
            <tr>
                <td>{{__('START TIME')}}:</td>
                <td style="padding-left: 30px">{{date('h:i A', strtotime($lesson->classroomSlot->start_time))}}</td>
            </tr>
            <tr>
                <td>{{__('END TIME')}}:</td>
                <td style="padding-left: 30px">{{date('h:i A', strtotime($lesson->classroomSlot->end_time))}}</td>
            </tr>
        </table>
    </div>
</div>