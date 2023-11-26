@if (isset($fieldsSelect))
<div class="row">
    <div class="col-md-6">
        @include('back.layouts.core.forms.select',
        [
        'name' => 'field_name',
        'label' => 'Field' ,
        'class' => '' ,
        'required' => true,
        'attr' => '',
        'data' => $fieldsSelect,
        'value' => '',
        'placeholder' => __('--Select Field--')
        ])
    </div>

    @if (isset($templateFields) && !empty($templateFields))
    <div class="col-md-5">
        @include('back.layouts.core.forms.select',
        [
        'name' => 'template_field_alias',
        'label' => 'Document Field' ,
        'class' => '' ,
        'required' => true,
        'attr' => '',
        'data' => $templateFields,
        'value' => '',
        'placeholder' => __('--Select Field--')
        ])
    </div>
    @else

    <div class="col-md-5">
        @include('back.layouts.core.forms.text-input',
        [
        'name' => 'template_field_alias',
        'label' => 'Document Field' ,
        'class' => '' ,
        'required' => true,
        'attr' => '',
        'value' => ''
        ])
    </div>
    @endif

    <div class="mt-2 col-md-1">
        <div class="form-group">
            <label>&nbsp;</label>
            <button class="btn btn-success" type="button" onclick="app.addMappedField(this)">
                <i class="fa fa-plus"></i>
            </button>
        </div>
    </div>
</div>

@else
<div class="row" data-field-name="{{$field_name_value}}">
    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
        'name' => 'template_field_name[]',
        'label' => null ,
        'placeholder' => 'Field',
        'class' => '' ,
        'required' => false,
        'attr' => 'disabled data-value=',
        'value' => $field_name
        ])
    </div>

    <div class="col-md-5">
        @include('back.layouts.core.forms.text-input',
        [
        'name' => 'custom_mautic_field_alias[]',
        'label' => null ,
        'placeholder' => 'Document Field',
        'class' => '' ,
        'required' => false,
        'attr' => 'disabled data-value=',
        'value' => $template_field_name
        ])
    </div>

    <div class="mt-2 col-md-1">
        <div class="form-group">
            <button class="btn btn-danger" type="button" onclick="app.removeMappedField('{{$field_name_value}}')">
                <i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
</div>
@endif
