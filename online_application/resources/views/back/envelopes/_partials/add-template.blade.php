<div class="row">
    <div class="col-md-12">
        @include('back.layouts.core.forms.select', [
        'name' => 'template',
        'label' => 'Template' ,
        'class' => 'ajax-form-field' ,
        'required' => false,
        'placeholder' => __('Select Template'),
        'attr' => 'onchange=app.loadEnvelopesTemplates(this)' ,
        'data' => Sign::getTemplatesList(),
        'value' => ''
        ])
    </div>
</div>

@include('back.layouts.core.forms.hidden-input',
[
'name' => 'template_name',
'label' => 'Template Name' ,
'class' => 'ajax-form-field' ,
'required' => true,
'attr' => '',
'value' => ''

]) @include('back.layouts.core.forms.hidden-input',
[
'name' => 'custom_fields',
'label' => 'Custom Fields' ,
'class' => 'ajax-form-field' ,
'required' => true,
'attr' => '',
'value' => ''
])

<div id="applicationFieldsWrapper">
    <div class="row">
        <div class="col-12">
            <div class="alert alert-warning">
                {{__('Please select the template first')}}
            </div>
        </div>
    </div>
</div>
