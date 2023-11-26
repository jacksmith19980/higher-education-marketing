<div class="row">
    <div class="col-md-12">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'rubric_title',
            'label'     => 'Title' ,
            'class'     =>'' ,
            'required'  => false,
            'attr'      => '',
            'value'     => ''
        ])
    </div>
</div> 

<div class="row">
    <div class="col-md-12">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'rubric_description',
            'label'     => 'Description' ,
            'class'     =>'' ,
            'required'  => false,
            'attr'      => '',
            'value'     => ''
        ])
    </div>
</div> 
