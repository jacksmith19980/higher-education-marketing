@php
    // if Multi Courses selection is enabled
    $multi = isset($quotation->properties['enable_multi_program'])? true : false
@endphp
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 px-lg-6 px-md-6" >
    <div class="list-item" 
    data-toggle="modal" 
    data-target=".bs-example-modal-lg"
    onclick="app.showProgramInformation('{{route('program.information' , [
    'school' => $school,
    'program' => $program->id,
    'assistantBuilder'  => $assistantBuilder,
    'step'  => $steps['next']['step'],
    ] )}}')">
        
    <div class="list-header campus-item"
            
             data-program="{{$program->id}}">

            <div class="flex-container">
                <span class="fas fa-map-marker-alt"></span>

                <h3>{{$program->title}}</h3>

                <label class="checkbox-container">
                    <span class="checkmark"></span>
                </label>

            </div>
        </div>

        <div class="list-footer list-footer-vaa" 
        >
            <div class="flex-container">
                <h5>{{__('More Details')}}</h5>
                <span class="fas fa-angle-right"></span>
            </div>
        </div>
        {{--   @endif  --}}
    </div>
</div>