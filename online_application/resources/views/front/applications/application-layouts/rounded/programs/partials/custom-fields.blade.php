<div class=" row">
    @php
        $fieldCustomFields = collect($field->properties['customFields'])->keyBy('name')->toArray();

        $customFields = isset($customFields) ? $customFields :
        App\Tenant\Models\CustomField::whereIN('slug', array_keys(isset($program->properties['customfields'])? $program->properties['customfields'] : []))->get()->keyBy('slug')->toArray();
    @endphp

    @foreach($field->properties['customFields'] as $key => $customField)

        @if(isset($customFields[$customField['name']]))
                @php
                    $name  = $customField['name'];
                    $value = $program->properties['customfields'][$name];

                    $properties = ApplicationHelpers::getCustomFieldParams($fieldCustomFields[$customField['name']] , $customFields[$customField['name']] , $value , $field );

                @endphp

                @include('front.applications.application-layouts.rounded.custom-fields.' .$customFields[$customField['name']]['field_type'], [
                        'properties'        => $properties,
                        'customFieldName'   => $customField['name'],
                    ])
        @endif
    @endforeach
</div>
