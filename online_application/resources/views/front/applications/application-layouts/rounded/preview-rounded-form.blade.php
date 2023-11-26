<div class="container-fluid">
    @php
        $header_headline = (isset($settings['applications']['header_line_color']) && $settings['applications']['header_line_color'] != '') ? $settings['applications']['header_line_color'] : ((isset($settings['branding']['secondary_color']) && $settings['branding']['secondary_color'] !== '') ? $settings['branding']['secondary_color'] : '#1a8ec6');
    @endphp
    <div
            class="header-headline"
            style="
                    background: {{$header_headline}};
            padding-top: 5px;
            padding-bottom: 5px;
            color: #ffffff;"
    >
        <h1 style="margin: 0; font-size: 28px; line-height: 40px; text-align: center; font-weight: 400; padding: 0 1rem;">
            {{__($application->title)}}
        </h1>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
                <div class="wizard-content">

                    @if (isset($application->properties['no_login']))
                        @php
                            $route = route('application.submit.no.login' , ['school'=> $school , 'application' => $application ])
                        @endphp
                    @else
                        @php
                            $route = route('application.submit' , ['school'=> $school , 'application' => $application ])
                        @endphp

                    @endif

                    <form method="POST" action="{{ $route }}" class="validation-wizard wizard-circle" aria-label=""
                            data-add-button="{{__('Submit Application')}}" data-next-button="{{__('Save and Continue')}}"
                            data-prev-button="{{__('Previous')}}"

                            @if (isset($application->properties['review_page']))
                            data-review="{{ (count($application->sections_order) + 1 )}}"

                            data-review-url="{{route('submission.review' , ['application' => $application,'school' => $school])}}"
                            @endif

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

                                <h6>{{__($section->properties['label'])}}</h6>

                                <section>

                                    <input type="hidden" name="section" value="{{$section->id}}">
                                    <input type="hidden" name="booking_id" value="{{request('booking')}}">
                                    <input type="hidden" name="assistant_id" value="{{request('assistant')}}">

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
                                                    if(!$field)
                                                    {
                                                        continue;
                                                    }
                                                    $params = [
                                                        'properties' => $field->properties,
                                                        'data'       => $field->data,
                                                        'label'      => $field->label,
                                                        'name'       => $field->name,
                                                        'repeater'   => $field->repeater
                                                    ];

                                                    if(isset($params['properties']['validation']['required'])) {
                                                        $params['properties']['validation']['required'] = null;
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
                                                    if(isset($params['properties']['validation']['required'])) {
                                                        $params['properties']['validation']['required'] = null;
                                                    }
                                                    if($field->properties['type'] == "program") {
                                                        $params['properties']['validation']['required'] = false;
                                                    }
                                                @endphp

                                                @if (!$field->repeater && !in_array($field->properties['type'] , ['loop' , 'filesList']))

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
                            {{--   @if (isset($application->properties['review_page']))
                                @include('front.applications.application-layouts.rounded.review.index' , [
                                    'application'=>$application,
                                    'submission'=>$submission,
                                ])
                            @endif  --}}
                        @endif

                    </form>

                </div>

        </div>
    </div>
</div>

<script>
    var submittedStep = "{{ isset($submission->properties['step']) ? $submission->properties['step'] : null }}"
</script>
