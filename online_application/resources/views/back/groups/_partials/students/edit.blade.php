<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Select Students</h4>
                <div class="col-md-12">
                    @include('back.layouts.core.forms.duallistbox',
                    [
                        'name'      => 'students[]',
                        'label'     => '' ,
                        'class'     => 'duallistbox' ,
                        'required'  => false,
                        'attr'      => '',
                        'value'     => \App\Helpers\School\ModelHelpers::convertFirstNameLastnameInNameAssocWithId($group->students),
                        'data'      => $students
                    ])
                </div>
            </div>
        </div>
    </div>
</div>
