

<div class=" row">

{{--  @dump($course->properties['customfields'])
@dump($customFields)
@dump($field->properties['customFields'])  --}}

@foreach($field->properties['customFields'] as $key => $customField)
    @if(isset($customFields[$customField['name']]))


            @php
                $value = $course->properties['customfields'][$customField['name']];
                $name  = $customField['name'];

                $fieldCustomFields = collect($field->properties['customFields'])->keyBy('name')->toArray();


               $properties = ApplicationHelpers::getCustomFieldParams($fieldCustomFields[$customField['name']] , $customFields[$customField['name']] , $value , $field );




            @endphp

            @include('front.applications.application-layouts.rounded.custom-fields.' .$customFields[$customField['name']]['field_type'], ['properties' => $properties])

    @endif

@endforeach
</div>
