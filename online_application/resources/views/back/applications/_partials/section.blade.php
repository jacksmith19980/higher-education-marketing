<div class="col-md-12 col-sm-12 application_section" data-sectionId="{{$section->id}}"
    data-application-slug="{{$application->slug}}">
    <div class="card card-hover">
        <div class="card-header bg-info handle">
            <h4 class="float-left text-white m-b-0 handle">{{$section->title}}</h4>
            <div class="float-right section-control">

                <a href="javascript:void(0)" data-placement="top" data-toggle="tooltip" title="{{__('Edit')}}" onclick="app.editSection(
                       '{{ route('sections.edit' , [ 'section'=> $section , 'application' =>$application ]) }}',
                       {{ json_encode( [ 'section' => $section->id , 'application' => $application->id ] ) }},
                       '{{__( " Edit ".$section->title ) }}', this )">
                    <i class="text-white ti-pencil-alt app-action-icons"></i>
                </a>

                <a href="javascript:void(0)" onclick="" data-placement="top" data-toggle="tooltip"
                    title="{{__('Clone')}}"><i class="text-white ti-loop app-action-icons" onclick="app.cloneSection(
                        '{{ route('sections.clone' , [$section, $application] ) }}'
                    )"></i></a>

                <a href="javascript:void(0)" data-placement="top" data-toggle="tooltip" title="{{__('Delete')}}"
                    onclick="app.deleteElement(
                       '{{ route('sections.destroy' , $section ) }}',
                       {{ json_encode( [ 'application' => $application->id ] )}},
                       'data-sectionId'
                   )">
                    <i class="text-white ti-trash app-action-icons"></i>
                </a>

            </div>
        </div>
        <div class="card-body">
            <div class="fields-wrapper list-group" data-parent-section="{{$section->id}}">
                <input type="hidden" name="fields_order" value="{{json_encode($section->fields_order)}}">
                @if ($section->fields_order)
                @foreach ($section->fields_order as $fieldId)
                @php
                $field = $section->fields->filter(function($item) use ($fieldId) {
                return $item->id == $fieldId;
                })->first();
                @endphp
                @if(!$field)
                    @continue
                @endif
                @if($field->field_type =='payment' )
                @include('back.applications._partials.payment-field', ['section' => $section , 'application' =>
                $application , 'field' => $field , 'payment'=> $field->payment])
                @else
                @include('back.applications._partials.field', ['section' => $section , 'application' => $application ,
                'field' => $field])
                @endif
                @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
