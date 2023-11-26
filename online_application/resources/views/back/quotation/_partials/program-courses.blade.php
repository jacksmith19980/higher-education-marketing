<div class="row">
    
    <div class="col-md-6">
        @include('back.layouts.core.forms.multi-select',
            [
                'name'      => 'campuses[]',
                'label'     => 'Campuses' ,
                'class'     =>'select2' ,
                'required'  => false,
                'attr'      => '',
                'value'     => isset($settings['quotation']['campuses'])? $settings['quotation']['campuses'] : [],
                'data'      => $campuses
                ])
    </div>
        
    <div class="col-md-6">
        @include('back.layouts.core.forms.multi-select',
        [
            'name'      => 'programs[]',
            'label'     => 'Programs' ,
            'class'     =>'select2' ,
            'required'  => false,
            'attr'      => '',
            'value'     => isset($settings['quotation']['programs'])? $settings['quotation']['programs'] : [],
            'data'      => $programs
        ])
    </div>


    <div class="col-md-6">
        @include('back.layouts.core.forms.multi-select',
        [
            'name'      => 'courses[]',
            'label'     => 'Courses' ,
            'class'     =>'select2' ,
            'required'  => false,
            'attr'      => '',
            'value'     => isset($settings['quotation']['courses'])? $settings['quotation']['courses'] : [],
            'data'      => $courses
        ])
    </div>

</div> <!-- row -->