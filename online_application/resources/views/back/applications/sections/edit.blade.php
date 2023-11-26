<form  method="POST" id="ajax-form" action="{{ route( $route , $section) }}" class="validation-wizard wizard-circle" enctype="multipart/form-data">

    <div class="row">

        @include('back.layouts.core.forms.hidden-input',
        [
            'name'      => '_method',
            'label'     => 'Type' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => 'PUT'
        ])

    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'title',
            'label'     => 'Title' ,
            'class'     => 'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => $section->title
        ])
    </div>
    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'properties_label',
            'label'     => 'Label' ,
            'class'     => 'ajax-form-field' ,
            'helper'    => 'Leave blank if you want to use the <strong>Title</strong> value',
            'required'  => false,
            'attr'      => '',
            'value'     => $section->properties['label']
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
            'value'     => isset($section->properties['icon']) ? $section->properties['icon'] : ''
        ])
    </div>
</div> <!-- row -->
</form>
