
    @if ( $lessons_count = $lessons->count() )
    <div class="">
        <table id="course_attendances" class="table responsiveDataTable display new-table nowrap w-100 responsive dataTable">
            <thead>
                <tr>
                    <th>{{__('Date')}}</th>
                    <th>{{__('Classroom')}}</th>
                    <th>{{__('Start time')}}</th>
                    <th>{{__('End Time')}}</th>
                    <th>{{__('Held')}}</th>
                    <th></th>
                </tr>
            </thead>
            @foreach($lessons as $lesson)
                <tr>
                    <td>{{$lesson->date}}</td>
                    <td>{{$lesson->classroom->title}}</td>
                    <td>{{date('h:i A', strtotime($lesson->classroomSlot->start_time))}}</td>
                    <td>{{date('h:i A', strtotime($lesson->classroomSlot->end_time))}}</td>
                    <td>{{$lesson->date > now() ? 'Yes' : 'No' }}</td>
                    <td>
                        @php
                        $buttons = [["text"=>"View", "icon"=>"icon-eye", "class"=>"", "url"=>"javascript:get_lesson_attendances($lesson->id, $course->id, 'view')"],
                                    ["text"=>"Edit", "icon"=>"icon-pencil", "class"=>"", "url"=>"javascript:get_lesson_attendances($lesson->id, $course->id, 'edit')"],
                                    ["text"=>"Export", "icon"=>"icon-share-alt", "class"=>"", "url"=>"javascript:export_lesson_attendances($lesson->id, $course->id, 'export')"]]
                        @endphp
                        <div class="btn-group more-optn-group float-right">
                            <button type="button"
                            class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split ti-more-alt flat-btn"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            </button>
                            <div class="dropdown-menu">
                                @foreach ($buttons as $button)
                                        <a class="dropdown-item {{$button['class']}}" href="{{$button['url']}}">
                                            <i class="{{$button['icon']}}"></i> <span  class="icon-text">{!!__($button['text'])!!}</span>
                                        </a>
                                @endforeach
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@else
<div class="alert alert-warning">
    <strong>{{__('No Results Found')}}</strong>
    <span class="d-block">{{__('there are none data to show!')}}</span>
</div>
@endif
