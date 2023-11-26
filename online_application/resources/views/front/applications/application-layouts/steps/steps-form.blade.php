<div class="jumbotron jumbotron-fluid">

    <div class="container">

        <div class="col-md-8 offset-md-2">

            <h3 class="display-7" style="text-align: center;">{{$application->title}}</h3>

            <p class="lead" style="text-align: center;">{{$application->description}}</p>

        </div>

    </div>

</div>


<div class="container">

    <div class="row justify-content-center">

        <div class="col-md-12">

            <div class="card">

                <div class="card-body wizard-content">


                    <form method="POST"
                          action="{{route('application.submit' , ['school'=> $school , 'application' => $application ] )}}"
                          class="validation-wizard wizard-circle m-t-40" aria-label=""
                          data-add-button="{{__('Submit Application')}}">


                    @csrf

                    <!-- Step 1 -->



                        @foreach ($sections as $section)

                            <h6>{{$section->title}}</h6>

                            <section>

                                <div class="row">

                                    {{session('errors')}}



                                    @foreach ($section->fields as $field)



                                        @php

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



                                        @if ( optional($submission)->data )

                                            @php

                                                $params['value'] = $submission->data[$field->name];

                                            @endphp

                                        @else

                                            @php

                                                $params['value'] = null;

                                            @endphp

                                        @endif





                                        @include('front.layouts.form.steps-form.'.$field->properties['type'] , $params)



                                    @endforeach


                                </div> <!-- row -->


                            </section>

                            <!-- Step 2 -->

                        @endforeach


                    </form>

                </div>

            </div>

        </div>

    </div>

</div>