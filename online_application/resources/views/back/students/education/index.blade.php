<div id="nav-education" class="tab-pane fade" role="tabpanel" aria-labelledby="nav-education">

    <div class="col--12 text-right clearfix mb-3">
        <div class="float-right btn-group" role="group">
            <div id="addEducation" data-name="add_new_application_dropdown" class="btn-group " role="group">
                <button data-name="dropdown-toggle" id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    {{__('Add New')}}
                </button>

                <div data-name="dropdown-menu" class="dropdown-menu" aria-labelledby="btnGroupDrop1" x-placement="bottom-end">
                    <a class="dropdown-item student-application" href="javascript:void(0)"
                    onclick="app.addEducation('{{route('add.education' , ['student' => $student,'type'=>'program'])}}' , '' , '{{__('Add Program')}}' , this)">
                        {{__('Add Program')}}
                    </a>
                    <a class="dropdown-item student-application" href="javascript:void(0)"
                    onclick="app.addEducation('{{route('add.education' , ['student' => $student,'type'=>'course'])}}' , '' , '{{__('Add Course')}}' , this)">
                        {{__('Add Course')}}
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if($applicant->parentEducations->count())
        <div class="col-12" id="EducationList">
            @foreach ($applicant->parentEducations as $education)
                @include('back.students.education.educationable' , [
                    'student'       => $student,
                    'education'     => $education,
                    'educationable' => $education->educationable,
                ])
            @endforeach
        </div>
    @else
        <div class="col-12" id="EducationList"></div>
        <div class="col-12" id="EmptyEducationList">
            @include('back.students._partials.student-no-results')
        </div>
    @endif

</div>
