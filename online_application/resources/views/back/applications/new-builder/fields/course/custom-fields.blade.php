<div class="row">
    <div class="col-12 text-right">
        <a href="javascript:void(0)" data-payload="{{json_encode(['object' => 'course'])}}" onclick="app.repeatElement('field.addCustomField' , 'custom-fields-wrapper' , false)" class="btn btn-success">{{__('Add Custom Field')}}</a>
    </div>
</div>

<div class="custom-fields-wrapper container">
    @if(isset($field->properties['customFields']))
        @foreach ($field->properties['customFields'] as $key => $customField )
            @include('back.applications.new-builder.fields.course.custom-field' , [
                'order'         => $key,
                'customField'   => $customField
                ])

        @endforeach
    @endif
</div>
