<div class=" row">
    @foreach($customFields as $key => $customField)
        @php
            $slug = $customField['slug'];

            $value = isset($values[$slug]) ? $values[$slug] : $entity->{$slug};

            $name  = $customField['name'];
            $fieldCustomFields = collect($field->properties['customFields'])->keyBy('name')->toArray();

            $properties = ApplicationHelpers::getCustomFieldParams($fieldCustomFields[$customField['slug']],$customField, $value , $field );

        @endphp

        @include('front.applications.application-layouts.rounded.custom-fields.' .$customField['field_type'], [
                'customFieldName' => $name,
                'properties' => $properties
                ])
    @endforeach
</div>
