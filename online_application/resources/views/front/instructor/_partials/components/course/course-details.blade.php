@php
    $courseDates = InstructorHelpers::courseDetailsByLessons($instructor, $course);
@endphp

<div style="border-left: 5px solid #004d6e; padding-left: 10px; background-color: #f6f6f6; padding-top: 10px; padding-bottom: 10px; margin-bottom: 20px; border-radius: 5px">
<div class="row show-grid">
    <div class="col-md-4">
        <a href="{{route('instructor.course.show', ['school'=>request()->tenant()->slug, 'course'=>$course->id, 'place'=>Crypt::encrypt('students')])}}" style="font-weight: bold">
            {{$course->title ?? ''}}
        </a>
        <br>
        {{__('Program')}}: {{$course->programs->first()->title}}<br>
        {{__('Campus')}}: {{$course->campuses->first()->title}}<br>
        {{__('Instructor')}}: {{$instructor->name}}<br>
    </div>
    <div class="col-md-3" style="display: flex; align-items: center; justify-content: center;">
        <table style="border-bottom: 3px solid #004d6e;">
            <tr>
                <th>{{__('START')}}</th>
                <th style="padding-left: 20px;">{{__('END')}}</th>
            </tr>
            <tr>
                <td>
                    {{ $courseDates['start_date']}}
                </td>
                <td style="padding-left: 20px;">
                    {{ $courseDates['end_date'] }}
                </td>
            </tr>
        </table>
    </div>
    <div class="col-md-1" style="display: flex; align-items: center; justify-content: center;">
        <table>
            <tr>
                <th>{{__('CLASSROOM')}}</th>
            </tr>
            <tr>
                <td>
                    {{$courseDates['classroomTitle']}}
                </td>
            </tr>
        </table>
    </div>
    <div class="col-md-1" style="display: flex; align-items: center; justify-content: center;">
        <table>
            <tr>
                <th>{{__('SCHEDULE')}}</th>
            </tr>
            <tr>
                <td>
                    {{$courseDates['schedule']}}
                </td>
            </tr>
        </table>
    </div>
    <div class="col-md-2" style="display: flex; align-items: center; justify-content: center;">
        <table>
            <tr>
                <th>{{__('STATUS')}}</th>
            </tr>
            <tr>
                <td>
                    {{$courseDates['status']}}
                </td>
            </tr>
        </table>
    </div>
    @if($showActions)
        <div class="col-md-1"  style="display: flex; align-items: center; justify-content: center;">
        @php
            $buttons = [[
                    "text"=>"Students",
                    "icon"=>"fas fa-graduation-cap",
                    "place"=>"students",
                    "url"=>"instructor.course.show"
                    ],
                        ["text"=>"Calendar", "icon"=>"far fa-calendar-alt", "place"=>"calendar", "url"=>"instructor.course.show"],
                        ["text"=>"Attendance", "icon"=>"fas fa-users", "place"=>"attendance", "url"=>"instructor.course.show"],
                        ["text"=>"Grades", "icon"=>"far fa-newspaper", "place"=>"grades", "url"=>"instructor.course.show"]]
        @endphp

        <div class="btn-group more-optn-group">
            <button type="button"
            class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split ti-more-alt flat-btn"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>

            <div class="dropdown-menu">
                @foreach ($buttons as $button)
                        <a class="dropdown-item " href="{{route($button['url'], ['school'=>request()->tenant()->slug, 'course'=>$course->id, 'place'=>Crypt::encrypt($button['place'])])}}">
                            <i class="{{$button['icon']}}"></i> <span  class="icon-text">{!!__($button['text'])!!}</span>
                        </a>
                @endforeach
            </div>
        </div>



        </div>
    @endif
</div>
</div>
