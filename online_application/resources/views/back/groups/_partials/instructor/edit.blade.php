<div class="row">
    <div class="col-md-12">
        @include('back.layouts.core.forms.multi-select', [
            'name'      => 'instructors[]',
            'label'     => 'Instructors' ,
            'class'     =>'select2' ,
            'required'  => false,
            'attr'      => '',
            'value'     => \App\Helpers\School\ModelHelpers::convertFirstNameLastnameInNameAssocWithId($group->instructors),
            'data'      => $instructors
        ])
    </div>
</div>