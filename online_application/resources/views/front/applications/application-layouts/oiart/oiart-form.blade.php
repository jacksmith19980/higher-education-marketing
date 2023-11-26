<div class="container">

    <div class="row justify-content-center">

        <div class="col-md-12">

            <div class="card">

                <div class="card-body wizard-content">

                    @if (isset($application->properties['no_login']))
                        @php
                            $route = route('application.submit.no.login' , ['school'=> $school , 'application' => $application ])
                        @endphp
                    @else
                        @php
                            $route = route('application.submit' , ['school'=> $school , 'application' => $application ])
                        @endphp

                    @endif

                    <form method="POST" action="{{ $route }}" class="validation-wizard m-t-40" aria-label=""
                          data-add-button="{{__('Submit Application')}}" data-next-button="{{__('Save and Continue')}}"
                          data-prev-button="{{__('Previous')}}"
                          enctype="multipart/form-data"
                          @if(isset($application->properties['finish_button'])) data-finish-button="true" @endif
                    >

                        <input type="hidden" name="document_signed_at" value="{{isset($submission['data']['document_signed_at']) ? $submission['data']['document_signed_at'] : '' }}">
                        @csrf
                        @if ($application->sections_order)

                            @foreach ($application->sections_order as $sectionId)

                                @php
                                    $section = $sections->filter(function($item) use ($sectionId) {
                                          return $item->id == $sectionId;
                                          })->first();
                                @endphp

                                <h6>{{__($section->title)}}</h6>

                                <section
                                        class="form-wizard-section"

                                        @if ( isset($section->properties['icon']) && !empty($section->properties['icon']) )

                                        data-icon="{{ Storage::disk('s3')->temporaryUrl( $section->properties['icon'], \Carbon\Carbon::now()->addMinutes(5) )}}"

                                        @endif

                                        data-label="{{ __($section->properties['label'])}}"
                                >

                                    <input type="hidden" name="section" value="{{$section->id}}">
                                    <input type="hidden" name="booking_id" value="{{request('booking')}}">
                                    <input type="hidden" name="assistant_id" value="{{request('assistant')}}">
                                    <input type="hidden" name="submissionUid" value="{{$submissionUid}}">

                                    <div class="row">


                                        @if (session('success'))

                                            <div class="alert alert-success col-md-12">
                                                {{session('success')}}
                                            </div>

                                        @endif

                                        @if ($section->fields_order)
                                            @foreach ($section->fields_order as $fieldId)
                                                @php
                                                    $field = $section->fields->filter(function($item) use ($fieldId) {
                                                            return $item->id == $fieldId;

                                                    })->first();

                                            $params = [
                                                'properties' => $field->properties,
                                                'data'       => $field->data,
                                                'label'      => $field->label,
                                                'name'       => $field->name,
                                                'repeater'   => $field->repeater
                                            ];
                                            if(isset($preview)) {
                                                if(isset($params['properties']['validation']['required'])) {
                                                    $params['properties']['validation']['required'] = null;
                                                }
                                            }
                                                @endphp

                                                @if ( isset($submission->data[$field->name]) )
                                                    @php
                                                        $params['value'] = $submission->data[$field->name] ;
                                                    @endphp

                                                    @if (
                                                        $field->properties['type'] == 'phone'
                                                        && isset(
                                                            $submission->data['countrycode_'.$field->name])
                                                        )
                                                        @php
                                                        $params['code'] = $submission->data['countrycode_'.$field->name]
                                                        @endphp

                                                        @endif

                                                        @if (
                                                        $field->properties['type'] == 'phone'
                                                        && isset(
                                                            $submission->data['country_code_'.$field->name])
                                                        )
                                                        @php
                                                        $params['country_code'] = $submission->data['country_code_'.$field->name]
                                                        @endphp

                                                        @endif

                                                @else
                                                    @php
                                                        $params['value'] = '';
                                                    @endphp
                                                @endif

                                                @php
                                                    $params['contactType'] = (isset($field->properties['contactType'])) ? $field->properties['contactType'] : 'Lead';
                                                @endphp

                                                @if (!$field->repeater && !in_array($field->properties['type'] , ['loop' , 'filesList'])  )

                                                    @include('front.applications.application-layouts.oiart.'.$field->field_type.'.'.$field->properties['type'] , $params)

                                                @endif

                                                @if ($field->properties['type'] == 'loop')
                                                    @include('front.applications.application-layouts.oiart.helper.repeater',[
                                                       'section'      => $section,
                                                       'props'   => $params,
                                                       'submission'   => isset($submission) ? $submission : '',
                                                    ])
                                                @endif

                                                @if ($field->properties['type'] == 'filesList')
                                                    @include('front.applications.application-layouts.oiart.file_list.file_list',[
                                                    'section' => $section,
                                                    'props' => $params,
                                                    'submission'   => isset($submission) ? $submission : '',
                                                    ])
                                                @endif

                                            @endforeach
                                        @endif
                                    </div>
                                </section>
                            @endforeach
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var submittedStep = "{{ isset($submission->properties['step']) ? $submission->properties['step'] : null }}"
</script>
