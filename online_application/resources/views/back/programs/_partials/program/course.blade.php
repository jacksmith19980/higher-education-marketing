<tr data-program-id="{{$course->id}}">

    <td>
        <span style="font-size: 14px">
            <a href="{{route('courses.show' , ['course' => $course])}}">{{$course->title}}</a>
        </span>
    </td>
    <td>
        <span style="font-size: 12px">
            {{$course->slug}}
        </span>
    </td>
    <td>
        @foreach ($course->campuses as $campus)
            <span style="font-size: 12px" class="badge badge-primary">
                {{$campus->title}}
            </span>
        @endforeach
    </td>

    <td>
        @php
        $buttons = [
            ["text"=>"Edit", "icon"=>"icon-pencil", "class"=>"", "url"=>"javascript:editCourseProgram($course->id)"]
        ]
        @endphp

    </td>
</tr>
