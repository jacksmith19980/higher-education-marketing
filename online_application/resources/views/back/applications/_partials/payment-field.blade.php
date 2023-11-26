<div class="list-group-item list-group-item-action flex-column align-items-start field-row {{($field->field_type == 'payment') ? ' list-payment-field' : ' ' }}" data-fieldID="{{$field->id}}" data-section = {{$section->id}}>

     <div class="d-flex w-100 justify-content-between">
        <h6 class="mb-1 text-gray">
            <span class="field-drag-handler">
                <i class="ti ti-arrows-vertical"></i>
            </span>
            <span class="field-title">
                {{$field->label}}
                <i class="field_type_{{$field->field_type}} text-success"></i>

                {!! (isset($field->properties['validation']['required']))? '<small class="text-danger">*</small>' : '' !!}

                @smart($field->properties['smart'])

                    {!! '<small class="icon-puzzle text-info"></small>' !!}

                @endsmart
            </span>
        </h6>

        <span class="action-icons">

            <a href="javascript:void(0)" class="action-button" data-toggle="tooltip" data-placement="left" title="{{__('Edit')}}" onclick="app.editPaymentField( '{{ route('payments.edit' , ['payment' => $payment ,'field' => $field , 'type' => $field->properties['type'] , 'application' => $application->slug , 'field_type' => $field->field_type ] ) }}' , {{ json_encode( [ 'type' => $field->properties['type'] , 'section' => $section->id ] ) }} , '{{__( "Edit ".$field->label ) }}' , this )"
                >
            	<i class="ti-pencil-alt text-dark app-action-icons"></i>
            </a>

            <a href="javascript:void(0)" class="action-button" onclick="app.cloneField({{$field->id}})" data-toggle="tooltip" data-placement="left" title="{{__('Clone')}}">
            		<i class="ti-loop text-dark app-action-icons"></i>
            </a>

            <a href="javascript:void(0)" class="action-button"

                    onclick="app.deleteElement(
                            '{{ route('fields.destroy' , $field ) }}',
                             {{ json_encode( [ 'section' => $section->id ] )  }},
                            'data-fieldID'
                            )"


            	data-placement="left" data-toggle="tooltip"  title="{{__('Delete')}}" >

            	<i class="ti-trash text-danger app-action-icons"></i>

            </a>

        </span>

    </div>

</div>
