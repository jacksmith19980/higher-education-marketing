<form  method="POST" action="{{ route('update.student.education' , ['type' => $type,'student'=>$student , 'education' => $education]) }}" class="validation-wizard text_input_field">

    <div class="row">
        @include('back.layouts.core.forms.hidden-input',
            [
            'name' => 'type',
            'label' => '' ,
            'class' => 'ajax-form-field' ,
            'required' => true,
            'attr' => '',
            'value' => $type
            ])
    @if(strtolower($type) == 'program')
        <div class="col-md-12">
            @include('back.layouts.core.forms.select',
            [
                'name'      => 'education',
                'label'     => 'Program' ,
                'class'     =>'select2 ajax-form-field' ,
                'required'  => true,
                'attr'      => 'disabled onchange=app.getEducationDetails(this) data-type=' . $type,
                'value'     => $education->educationable->id,
                'placeholder' => 'Select Program',
                'data'      => ProgramHelpers::getProgramInArrayOnlyTitleId($campuses->pluck('id')->toArray()),
            ])
        </div>
    @endif

    @if($type == 'course')
        <div class="col-md-12">
            @include('back.layouts.core.forms.select',
            [
                'name'      => 'education',
                'label'     => 'Course' ,
                'class'     =>'select2 ajax-form-field' ,
                'required'  => true,
                'attr'      => 'onchange=app.getEducationDetails(this) data-type=' . $type,
                'value'     => '',
                'placeholder' => 'Select Course',
                'data'      => CourseHelpers::getCoursesInArrayOnlyTitleId($campuses),
            ])
        </div>
    @endif
    </div>
    <div class="row" id="educationDetails">

        <div class="col-md-6">
            @include('back.layouts.core.forms.select',
            [
                'name'          => 'date',
                'label'         => 'Intake Date' ,
                'class'         =>'select2 ajax-form-field' ,
                'required'      => true,
                'attr'          => 'onchange=app.getCohortsByDate(this)',
                'value'         => $education->date->id,
                'placeholder'   => 'Select Date',
                'data'          => $dateList,
            ])
        </div>
            @include('back.layouts.core.forms.campuses',
                [
                    'name'          => 'campus',
                    'label'         => 'Campus' ,
                    'class'         =>'ajax-form-field' ,
                    'required'      => true,
                    'attr'          => 'onchange=app.getCohortsByDate(this)',
                    'value'         => $education->campus->id,
                    'placeholder'   => 'Select Campus',
                    'data'          => $campuses->pluck('title', 'id')->toArray(),
                    'single'        => true,
            ])
        <div class="col-md-6" id="CohortsListContainer">
            @include('back.students.education.education-cohorts',
            [
                'groups'        => $groups,
            ])
        </div>

        <div class="col-md-6">
            @include('back.layouts.core.forms.select',
            [
                'name'          => 'status',
                'label'         => 'Status' ,
                'class'         =>'select2 ajax-form-field' ,
                'required'      => true,
                'attr'          => '',
                'value'         => $education->status,
                'placeholder'   => 'Select Status',
                'data'          => SchoolHelper::getEducationStatus(),
            ])
        </div>

        @if($courses)
            <div class="col-12">
                <table class="table-striped table">
                <thead>
                    <tr>
                        <th>{{__('Selected')}}</th>
                        <th>{{__('Title')}}</th>
                        <th>{{__('Status')}}</th>
                        <th>{{__('Cohorts')}}</th>
                    </tr>
                </thead>
                @foreach($courses as $course)
                    @php
                        $subEducationsIds = $subEducations->pluck('educationable_id')->toArray();
                        $checked = (in_array($course->id , $subEducationsIds)) ? "checked" : "";
                        $groups = 'N/A';
                        $status = 'N/A';
                        if($subEducation = $subEducations->where('educationable_id' , $course->id)->first()){
                            $status = ucwords($subEducation->status);
                            if($groups = $subEducation->allGroups->pluck('title')->toArray()){

                                $groups = implode(", " , $groups);
                            }

                        }
                    @endphp
                    <tr>
                        <td>
                            <input type="checkbox" {{$checked}} value="{{$course->id}}" name="courses[{{$course->id}}]" class="ajax-form-field" />
                        </td>
                        <td>{{$course->title}}</td>
                        <td>{{$status}}</td>
                        <td>{{$groups}}</td>
                    </tr>
                @endforeach
                </table>
            </div>
        @endif
    </div>
</form>
<script type="text/javascript">
    $(".select2").select2();
</script>
