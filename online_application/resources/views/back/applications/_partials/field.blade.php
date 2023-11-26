<div class="list-group-item list-group-item-action flex-column align-items-start field-row {{($field->field_type == 'payment') ? ' list-payment-field' : ' '}} {{ ($field->field_type == 'helper') ? ' list-helper-field' : ' ' }}"
    data-fieldID="{{$field->id}}" data-section={{$section->id}}>

    <div class="d-flex w-100 justify-content-between">
        <h6 class="mb-1 text-gray">
            <span class="field-drag-handler">
                <i class="ti ti-arrows-vertical"></i>
            </span>
            <span class="field-title">
                {{$field->label}}
                <i class="field_type_{{$field->field_type}} text-success"></i>
                {!! (isset($field->properties['validation']['required']))? '<small class="text-danger">*</small>' : ''
                !!}
            </span>
        </h6>
        <div>
            <span class="action-icons">
                @isset($field->properties['smart'])
                {!! '<small class="icon-puzzle text-info"></small>' !!}
                @endisset

                @if($field->field_type == 'helper')
                {!! '<small class="fas fa-redo text-info"></small>' !!}
                @endif
            </span>
            <span class="action-icons">


                @php
                $lable = str_replace("," , "" , $field->label);
                @endphp
                <a href="javascript:void(0)" class="action-button" data-toggle="tooltip" data-placement="left" id="field-{{$field->id}}"
                    title="{{__('Edit')}}"
                    onclick="app.editField( '{{ route('fields.edit' , ['field' => $field , 'type' => $field->properties['type'] , 'application' => $application->slug , 'field_type' => $field->field_type ] ) }}' , {{ json_encode( [ 'type' => $field->properties['type'] , 'section' => $section->id ] ) }} , '{{__( "Edit" ) }}' , this )">
                    <i class="ti-pencil-alt text-dark app-action-icons"></i>
                </a>

                <a href="javascript:void(0)" class="action-button"
                    data-toggle="tooltip" data-placement="left" title="{{__('Clone')}}"
                   onclick="app.cloneField('{{ route('fields.clone' , [$field, $section, $application] ) }}', 'field-{{$field->id}}')"
                >
                    <i class="ti-loop text-dark app-action-icons"></i>
                </a>

                @if ( isset($field->properties['delete']) && $field->properties['delete'] )
                <a href="javascript:void(0)" class="action-button" onclick="app.deleteElement(
                                    '{{ route('fields.destroy' , $field ) }}',
                                    {{ json_encode( [ 'section' => $section->id ] )  }},
                                    'data-fieldID'
                                    )" data-placement="left" data-toggle="tooltip" title="{{__('Delete')}}">
                    <i class="ti-trash text-danger app-action-icons"></i>
                </a>
                @elseif ( !isset($field->properties['delete']) )

                <a href="javascript:void(0)" class="action-button" onclick="app.deleteElement(
                                    '{{ route('fields.destroy' , $field ) }}',
                                    {{ json_encode( [ 'section' => $section->id ] )  }},
                                    'data-fieldID'
                                    )" data-placement="left" data-toggle="tooltip" title="{{__('Delete')}}">
                    <i class="ti-trash text-danger app-action-icons"></i>
                </a>
                @endif

            </span>
        </div>
    </div>
</div>
