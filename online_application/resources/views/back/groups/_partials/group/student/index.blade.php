<div>
    
    <div style="float:right; margin-right: 5%">
        <a href="javascript:get_back_to_all_students({{$group->id}});">
            &larr; {{__('BACK TO ALL STUDENTS')}}
        </a>
    </div>
   
    <div class="app-dashboard-header" style="margin-left: 2%">
        <div class="row align-items-center">
            <div class="col-md-6 col-lg-7">
                <div class="d-flex align-items-center">
                    <div class="col flex-grow-0 p-0">
                        <div class="avatar-upload">
                            <div class="avatar-edit">
                                <form id="imageUploadForm" name="imageUploadForm" method="POST" action="{{ route('school.profileimage.upload' , $school)}}" enctype="multipart/form-data" style="display: none;">
                                    <input type="file" id="imageUpload" accept=".png, .jpg, .jpeg" onchange="app.loadPreview(this);" data-route="" name="file">
                                    <label for="imageUpload"><i class="fas fa-camera"></i></label>
                                    <input type="hidden" name="stId" value="{{$student->id}}">
                                </form>
                            </div>
                            <div class="avatar-preview" id="{{ $student->avatar }}">
                                @if($student->avatar)
                                    @php
                                        $storageUrl = env('AWS_URL').$student->avatar;

                                    @endphp
                                    <img src="{{$storageUrl}}" id="imagePreview" style="width: 100%; height: 100%; border-radius:100%;">
                                @else
                                    <img src="{{ asset('media/images/blankavatar.png') }}" id="imagePreview" style="width: 100%; height: 100%; border-radius:100%;">
                            @endif
                            </div>
                        </div>
                    </div>
                    <div class="col flex-grow-1">
                        <div class="applicant-main-info">
                            <h4 class="mb-1">{{$student->name}}</h4>
                            <p class="mb-1">{{ $student->email }}</p>
                            <p class="mb-1">{{ $student->phone ? $student->phone['phone'] : '' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-5">
                <p class="applicant-adrs mb-3 md-sm-4 py-2 py-md-0">
                    <span class="applicant-adrs-street">{{$student->address}}</span>
                    <span class="applicant-adrs-city">{{$student->city}}</span> <span class="applicant-adrs-country">{{$student->country}}</span>
                    <span class="applicant-adrs-zip">{{$student->postal_code}}</span>
                </p>
            </div>
        </div>
    </div>

    <div style="margin-left: 2%; margin-right: 2%;">
        <ul class="nav nav-pills custom-pills" id="pills-tab" role="tablist"  style="margin-bottom: 2%">
            <li class="nav-item" id="li2"> 
                <a class="nav-link {{ $place ==  'group_student_attendances' ? 'active' : ''}}" id="pills-profile-tab" data-toggle="pill" href="#group_student_attendances" role="tab" aria-controls="pills-profile" aria-selected="false">{{__('Attendances')}}</a>
            </li>
            <li class="nav-item" id="li2"> 
                <a class="nav-link {{ $place ==  'group_student_grades' ? 'active' : ''}}" id="pills-profile-tab" data-toggle="pill" href="#group_student_grades" role="tab" aria-controls="pills-profile" aria-selected="false">{{__('Grades')}}</a>
            </li>
        </ul>
        <div class="tab-content tabcontent-border" >
            <div class="tab-pane {{ $place ==  'group_student_attendances' ? 'active' : ''}}" id="group_student_attendances" role="tabpanel">
                @include('back.groups._partials.group.student.attendances')
            </div>
            <div class="tab-pane {{ $place ==  'group_student_grades' ? 'active' : ''}}" id="group_student_grades" role="tabpanel">
                @include('back.groups._partials.group.student.grades')
            </div>
            
        </div>
    </div>

</div>
