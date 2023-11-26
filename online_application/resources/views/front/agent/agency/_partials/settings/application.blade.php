<div class="pb-5 tab-pane {{!isset($registrationApplication) ? 'fade' : ''}}" id="v-pills-{{$application->slug}}" role="tabpanel" aria-labelledby="v-pills-{{$application->slug}}-tab">

    @if (!isset($registrationApplication))
        <h4 class="card-title title mb-3">{{__($application->title)}}</h4>
    @else
        @if (session('success'))
            <div class="form-group row ">
                <div class="col-12 ">
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                </div>
            </div>
        @endif
    @endif


    @php
        if(!isset($registrationApplication)){

            $action = route('school.agency.application.submit' , [
                    'school'        => $school,
                    'agency'        => $agency,
                    'application'   =>$application,
                ]);
        }else{

            $action = route('school.agent.register.step2.submit' , [
                    'school'        => $school,
                    'agency'        => $agency
                ]);
        }

    @endphp

    <form method="POST" action="{{$action}}" enctype="multipart/form-data">
        @csrf




        @if ($registrationApplication)

            <input type="hidden" name="application_id[]" value="{{$application->id}}">

            <input type="hidden" name="agent_id" value="{{isset($agentId) ? $agentId : ''}}">
            <input type="hidden" name="agent_email" value="{{isset($agentEmail) ? $agentEmail : ''}}">

        @else

            <input type="hidden" name="application_id" value="{{$application->id}}">

        @endif
        <input type="hidden" name="agency_id" value="{{$agency->id}}">

        @php
            $submission = $agency->agencySubmissions()->where('agency_id' , $agency->id)->first();
        @endphp
        @if ($submission)
            <input type="hidden" name="submission_id"
                value="{{$submission->id}}">
        @endif
        @if ($application->sections_order)
        @foreach ($application->sections_order as $sectionId)

            @php
                $section = $application->sections->filter(function($item) use ($sectionId) {
                        return $item->id == $sectionId;
                })->first();
            @endphp
            <h5 class="mt-4 mb-2">{{__($section->title)}}</h5>

            <section
                    class="form-wizard-section"

                    @if ( isset($section->properties['icon']) && !empty($section->properties['icon']) )

                    data-icon="{{ Storage::disk('s3')->temporaryUrl( $section->properties['icon'], \Carbon\Carbon::now()->addMinutes(5) )}}"

                    @endif

                    data-label="{{ __($section->properties['label'])}}"


            >
                <div class="row">

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
                            @else
                                @php
                                    $params['value'] = '';
                                @endphp
                            @endif

                            @php
                                $params['contactType'] = (isset($field->properties['contactType'])) ? $field->properties['contactType'] : 'Lead';
                            @endphp

                            @if (!$field->repeater && !in_array($field->properties['type'] , ['loop' , 'filesList'])  )

                                @include('front.applications.application-layouts.rounded.'.$field->field_type.'.'.$field->properties['type'] , $params)

                            @endif

                            @if ($field->properties['type'] == 'loop')
                                @include('front.applications.application-layouts.rounded.helper.repeater',[
                                    'section'        => $section,
                                    'props'          => $params,
                                    'submission'     => $submission,
                                ])
                            @endif

                            @if ($field->properties['type'] == 'filesList')
                                @include('front.applications.application-layouts.rounded.file_list.file_list',[
                                'section' => $section,
                                'props' => $params,
                                'submission' => $submission,
                                ])
                            @endif

                        @endforeach
                    @endif
                </div>
            </section>
        @endforeach
    @endif
        <input type="submit" class="btn btn-success float-right" value="{{__('Submit')}}">
    </form>
</div>
