<div class="col-sm-12 col-lg-4" data-eversign-id="{{$paymentGateWay->id}}">
    <div class="card bg-info">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="m-r-10">
                    <h1 class="m-b-0"><i class="fas fa-bolt text-white"></i></h1></div>
                <div>
                    <h2 class="font-20 text-white m-b-5 op-7 integration-title">{{$field->label}}</h2>
                    <h6 class="text-white font-medium m-b-0">{{ strtoupper($field->name) }}</h6>
                </div>
                <div class="ml-auto">
                    <div class="crypto">
                        <canvas style="display: inline-block; width: 58px; height: 30px; vertical-align: top;"
                                width="58" height="30"></canvas>
                    </div>
                </div>
            </div>
            <div class="row text-center text-white m-t-30">

                <div class="col-6">
                    <a href="javascript:void(0)" class="action-button" data-toggle="tooltip" data-placement="top"
                       title="{{__('Edit')}}"
                       onclick="app.editField( '{{ route('fields.edit' , ['field' => $field , 'type' => $field->properties['type'] , 'application' => $application->slug , 'field_type' => $field->field_type ] ) }}' , {{ json_encode( [ 'type' => $field->properties['type']] ) }} , '{{__( "Edit" ) }}' , this )">

                        <i class="ti-pencil-alt text-white app-action-icons"></i>
                    </a>
                </div>
                <div class="col-6">
                    <a href="javascript:void(0)" class="action-button"
                       onclick="app.deleteElement('{{route('fields.destroy' , $field)}}' , {{ json_encode( [ 'id' => $field->id ] )  }} , 'data-eversign-id')"
                       data-toggle="tooltip" data-placement="top" title="{{__('Delete')}}">
                        <i class="ti-trash text-white app-action-icons"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
