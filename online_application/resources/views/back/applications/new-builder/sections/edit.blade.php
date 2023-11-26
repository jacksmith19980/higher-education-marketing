<form  method="POST"
    id="ajax-form"
    class="validation-wizard wizard-circle"
    enctype="multipart/form-data"
    @submit.prevent="updateSection({{$section->id}})"
    >
    <div class="row">
    <div class="col-md-6 new-field">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'title',
            'label'     => 'Title' ,
            'class'     => 'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => isset($section->title) ? $section->title : ''
        ])
    </div>
    <div class="col-md-6 new-field">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'properties_label',
            'label'     => 'Label' ,
            'class'     => 'ajax-form-field' ,
            'helper'    => 'Leave blank if you want to use the <strong>Title</strong> value',
            'required'  => false,
            'attr'      => '',
            'value'     => isset($section->properties['label']) ? $section->properties['label'] : ''
        ])
    </div>
    <div id="sideMenuFooter">
        <button class="btn btn-light" @click="editedSection = 0">{{__('Cancle')}}</button>
        <button type="submit" class="btn btn-success">{{__('Save')}}</button>
    </div>

</div> <!-- row -->



</form>
