<div>
    
    <div style="float:right; margin-right: 5%">
        <a href="javascript:get_back_to_all_courses({{$group->id}});">
            &larr; {{__('BACK TO ALL COURSES')}}
        </a>
    </div>
   
    <div class="app-dashboard-header" style="margin-left: 2%">
        <div class="row align-items-center">
            <div class="col-md-6 col-lg-7">
                <div class="d-flex align-items-center">
                    <div class="col flex-grow-1">
                        <div class="applicant-main-info">
                            <h4 class="mb-1">{{$course->title}}</h4>
                            <p class="mb-1">{{__('Programe')}} : {{ implode(" , " , Arr::pluck($course->programs->toArray(), 'title')) }}</p>
                            <p class="mb-1">{{__('Campus')}} : 
                                @foreach($course->campuses as $campus)
                                    @if ($campus)
                                        @include('back.campuses._partials.show', ['campus' => $campus])
                                    @endif
                                @endforeach
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div style="margin-top: 4%">
        <ul class="nav nav-pills custom-pills" id="pills-tab" role="tablist"  style="margin-bottom: 2%">
            <li class="nav-item" id="li2"> 
                <a class="nav-link {{ $place ==  'group_course_attendances' ? 'active' : ''}}" id="pills-profile-tab" data-toggle="pill" href="#course_attendances" role="tab" aria-controls="pills-profile" aria-selected="false">{{__('Attendances')}}</a>
            </li>
            <li class="nav-item" id="li2"> 
                <a class="nav-link {{ $place ==  'group_course_grades' ? 'active' : ''}}" id="pills-profile-tab" data-toggle="pill" href="#course_grades" role="tab" aria-controls="pills-profile" aria-selected="false">{{__('Grades')}}</a>
            </li>
            <li class="nav-item" id="li2"> 
                <a class="nav-link {{ $place ==  'group_course_lessons' ? 'active' : ''}}" id="pills-profile-tab" data-toggle="pill" href="#course_lessons" role="tab" aria-controls="pills-profile" aria-selected="false">{{__('Lessons')}}</a>
            </li>
        </ul>
        <div class="tab-content tabcontent-border" >
            <div class="tab-pane {{ $place ==  'group_course_attendances' ? 'active' : ''}}" id="course_attendances" role="tabpanel">
                @include('back.groups._partials.group.course.attendances')
            </div>
            <div class="tab-pane {{ $place ==  'group_course_grades' ? 'active' : ''}}" id="course_grades" role="tabpanel">
                @include('back.groups._partials.group.course.grades')
            </div>
            <div class="tab-pane {{ $place ==  'group_course_lessons' ? 'active' : ''}}" id="course_lessons" role="tabpanel">
                @include('back.groups._partials.group.course.lessons')
            </div>
        </div>
    </div>

</div>
