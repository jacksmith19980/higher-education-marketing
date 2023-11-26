<div class="row repeated_fields">
    <input type="hidden" name="customfield" value="1">
    <div class="col-md-4">
        @include('back.layouts.core.forms.select',
        [
            'name'      => 'properties[sync_target]',
            'label'     => 'Object' ,
            'class'     => 'ajax-form-field',
            'required'  => true,
            'attr'      => 'onchange=app.getCustomfields(this)',
            'data'      => [
                null            => __('Select Object'),
                'Courses'       => __('Courses'),
                'Program'       => __('Programs'),
            ],
            'value'     => isset($field) && optional($field)->properties['sync']['target'] ? optional($field)->properties['sync']['target'] : null
        ])
    </div>

    @php
        if (isset($field) && optional($field)->properties['sync']['target']) {
            if ($field->properties['sync']['target'] == 'Program') {
                $customfields = \App\Helpers\customfield\CustomFieldHelper::getProgramsCustomFields('name', 'slug');
            }

            if ($field->properties['sync']['target'] == 'Courses') {
                $customfields = \App\Helpers\customfield\CustomFieldHelper::getCoursesCustomFields('name', 'slug');
            }
        }
    @endphp

    <div class="col-md-4">
        @include('back.layouts.core.forms.select',
        [
            'name'      => 'properties[sync_source]',
            'label'     => 'Custom field' ,
            'class'     => 'ajax-form-field',
            'required'  => true,
            'attr'      => '',
            'data'      => isset($customfields) ? $customfields : [null => __('Select Custom Field')],
            'value'     => isset($field) && optional($field)->properties['sync']['source'] ? optional($field)->properties['sync']['source'] : null
        ])
    </div>


    <div class="col-md-4">
        @php
            $applicationId = $application->id;
        @endphp

        @include('back.layouts.core.forms.select',
        [
            'name'      => 'properties[sync_field]',
            'label'     => 'Sync With' ,
            'class'     => 'ajax-form-field select2',
            'required'  => true,
            'attr'      => "",
            'data'      => $fields,
            'value'     => isset($field) && optional($field)->properties['sync']['field'] ? optional($field)->properties['sync']['field'] : null
        ])
    </div>
</div>
