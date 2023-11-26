<div class="col-md-6">
    @include('back.layouts.core.forms.select',
    [
        'name'          => 'date',
        'label'         => 'Intake Date' ,
        'class'         =>'select2 ajax-form-field' ,
        'required'      => true,
        'attr'          => 'onchange=app.getCohortsByDate(this)',
        'value'         => '',
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
            'value'         => '',
            'placeholder'   => 'Select Campus',
            'data'          => $campuses->pluck('title', 'id')->toArray(),
            'single'        => true,
    ])
<div class="col-md-6" id="CohortsListContainer">
    @include('back.students.education.education-cohorts',
    [
        'groups'        => null,
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
        'value'         => '',
        'placeholder'   => 'Select Status',
        'data'          => SchoolHelper::getEducationStatus(),
    ])
</div>

@if($subEducations)
    <div class="col-12">
        <table class="table-striped table">
        <thead>
            <tr>
                <th>{{__('Selected')}}</th>
                <th>{{__('Title')}}</th>
                <th>{{__('Code')}}</th>
            </tr>
        </thead>
        @foreach($subEducations as $subEducation)
            <tr>
                <td>
                    <input type="checkbox" checked value="{{$subEducation->id}}" name="courses[{{$subEducation->id}}]" class="ajax-form-field" />
                </td>
                <td>{{$subEducation->title}}</td>
                <td>{{$subEducation->slug}}</td>

            </tr>
        @endforeach
        </table>
    </div>
@endif
