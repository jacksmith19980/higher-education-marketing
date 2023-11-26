<form  method="POST" id="ajax-form" action="{{ route('sections.store') }}" class="validation-wizard wizard-circle" enctype="multipart/form-data">

    <div class="row">
    <div class="col-md-6">
        
        @include('back.layouts.core.forms.hidden-input',
        [
            'name'      => 'application_id',
            'label'     => '' ,
            'class'     => 'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => $application->id
        ])

        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'title',
            'label'     => 'Title' ,
            'class'     => 'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => ''
        ])
    </div>
    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'properties.label',
            'label'     => 'Label' ,
            'class'     => 'ajax-form-field' ,
            'helper'    => 'Leave blank if you want to use the <strong>Title</strong> value',
            'required'  => false,
            'attr'      => '',
            'value'     => ''
        ])
    </div>
    <div class="col-md-6">
        @include('back.layouts.core.forms.file-input',
        [
            'name'      => 'icon',
            'label'     => 'Icon' ,
            'class'     => 'ajax-form-field' ,
            'helper'    => '',
            'required'  => false,
            'attr'      => '',
            'value'     => ''
        ])
    </div>
</div> <!-- row -->
</form>

