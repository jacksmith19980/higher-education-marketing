<div class="container">

    <div class="row justify-content-center">

        <div class="col-md-12">

            <div class="card">

                <div class="card-body wizard-content">

                   

                    <form method="POST" action="{{route('application.submit' , ['school'=> $school , 'application' => $application ] )}}" class="validation-wizard m-t-40" aria-label="" data-add-button="{{__('Submit Application')}}" data-next-button="Save & Continue" enctype="multipart/form-data">

                        

                        @csrf

                        <!-- Step 1 -->

                        @if ($application->sections_order)



    						@foreach ($application->sections_order as $sectionId)



                                @php



                                    $section = $sections->filter(function($item) use ($sectionId) {

                                           

                                      return $item->id == $sectionId;



                                      })->first();

                                  

                                @endphp

                            





                             <h6>{{$section->title}}</h6>

                            <section data-icon="{{ asset('media/images/'.$section->properties['icon'])}}" class="form-wizard-section">

                                  
                                <input type="hidden" name="section" value="{{$section->id}}" >



                                <div class="row">

                                  

                                  {{session('errors')}} 

                                    

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

                                            $params['value'] = "";

                                        @endphp

                                    @endif

                                    

                                   @include('front.applications.application-layouts.gbsg.'.$field->field_type.'.'.$field->properties['type'] , $params)

                                        @endforeach

                                    

                                    @endif

                                    

                                 



                                </div> <!-- row -->

                                  

                            </section>

                            <!-- Step 2 -->



    						@endforeach                        

                        @endif

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>