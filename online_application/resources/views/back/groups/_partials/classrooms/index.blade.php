<div class="row">
    <div class="col-md-12">
        @include('back.layouts.core.forms.select',
        [
            'name'      => 'classroom',
            'label'     => 'Classroom' ,
            'class'     =>'select2' ,
            'required'  => false,
            'attr'      => '',
            'value'     => '',
            'data'      => $classrooms
        ])
    </div>
</div>