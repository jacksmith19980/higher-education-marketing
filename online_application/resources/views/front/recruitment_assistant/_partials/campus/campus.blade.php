@php
    // if Multi Courses selection is enabled
    $multi = isset($quotation->properties['enable_multi_program'])? true : false
@endphp
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 px-lg-4 px-md-3">
    <div class="list-item">
        <div class="list-header campus-item"
             onclick="app.selectCampus({{$campus->id}}, {{$multi}} )"
             data-campus="{{$campus->id}}">

            <div class="flex-container">
                <span class="fas fa-map-marker-alt"></span>

                <h3>{{$campus->title}}</h3>

                <label class="checkbox-container">
                    <span class="checkmark"></span>
                </label>

            </div>
        </div>

        <div class="list-footer list-footer-vaa" data-toggle="modal" data-target=".bs-example-modal-lg"
             onclick="app.showCampusInformation('{{route('campus.information' , [
    'school' => $school,
    'campus' => $campus->id,
    'assistantBuilder'  => $assistantBuilder,
    'step'  => $steps['next']['step'],
    ])}}')"
        >
            <div class="flex-container">
                <h5>{{__('More Details')}}</h5>
                <span class="fas fa-angle-right"></span>
            </div>
        </div>
        {{--   @endif  --}}
    </div>
</div>