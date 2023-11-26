<!-- Start loop row here -->
<!-- row items -->
@foreach ($userApplication as $application)
    @php
        //$submission = $application->submissions->first();
        $subs = $application->submissions;
        //dump($subs);
    @endphp
    @if ( $application->status )
        @foreach($subs as $submission)
        <div class="mb-4 d-md-flex d-sm-block app-row align-items-center" data-submission-id="submission-{{$application->submissions[0]->id}}">
            <div class="p-3 content-app col-md-6 col-lg-7 pl-md-4">
                <span class="mt-2 app-col-header d-block d-md-none">{{__('Applications')}}</span>
                <h4 class="app-col-title">{{__($application->title)}}</h4>
                <p class="ap-col-desc">{!! __($application->description) !!}</p>
                <div class="flex-wrap mt-2 d-flex">
                    <a class="mb-2 mr-2 btn btn-secondary btn-sm mbtn is-uppercase"
                        href="{{route('school.submissions.show.review' , [$school, $submission])}}">
                        {{__('View')}}
                    </a>
                    @if(
                            (isset($submission->properties['lock']) && $submission->properties['lock'] == 1) &&
                            (isset($application->properties['request_edit']) && $application->properties['request_edit'] == 1))
                        @if(
                                !isset($submission->properties['request_unlock']) || $submission->properties['request_unlock'] == 0
                            )
                            <button class="mb-2 mr-2 btn btn-secondary btn-sm mbtn is-uppercase restart-application" data-route="{{route('submissions.requestUnlock', $school)}}"
                                    onclick="app.requestUnlock(this, {{$submission->id}})">{{__('Request to Edit')}} <i class="icon-lock"></i></button>
                        @else
                            <button class="mb-2 mr-2 btn btn-secondary btn-sm mbtn is-uppercase restart-application">{{__('Request sent')}} <i class="icon-check"></i></button>
                        @endif
                    @endif

                    @if(
                            (isset($submission->properties['lock']) && $submission->properties['lock'] == 0) ||
                            !isset($submission->properties['lock'])
                        )
                        @if (isset($submission->data['assistant_id']) && !empty($submission->data['assistant_id']))
                            <a class="mb-2 mr-2 btn btn-secondary btn-sm mbtn is-uppercase"
                                href="{{route('application.show', [
                                        'school' => $school,
                                        'application' => $application,
                                        'assistant' => $submission->data['assistant_id'],
                                        'edit' => $submission->uuid
                                    ])}}">
                                @if ($submission->steps_progress_status == 100)
                                    {{__('Edit')}}
                                    <i class="icon-note"></i>
                                @else
                                    {{__('Resume Application')}}
                                    <i class="icon-login"></i>
                                @endif
                            </a>
                        @else
                            <a class="mb-2 mr-2 btn btn-secondary btn-sm mbtn is-uppercase"
                                href="{{route('application.show' , ['school' => $school , 'application' => $application, 'edit' => $submission->uuid])}}">
                                @if ($submission->steps_progress_status == 100)
                                    {{__('Edit')}}
                                    <i class="icon-note"></i>
                                @else
                                    {{__('Resume Application')}}
                                    <i class="icon-login"></i>
                                @endif
                            </a>
                        @endif
                            <button class="mb-2 mr-2 btn btn-primary btn-sm mbtn is-uppercase restart-application" data-action="restart"
                                    data-route="{{route('application.submission.delete', ['school' => $school, 'application' => $application, 'submission' => $application->submissions[0]->id])}}"
                                    data-redirect-url="{{route('application.show' , ['school' => $school , 'application' => $application ])}}"
                                    onclick="app.deleteApplication(this)">{{__('Restart Application')}} <i class="icon-reload"></i></button>
                            {{--                                <button class="mb-2 btn btn-danger btn-sm mbtn is-uppercase" data-action="delete"--}}
                            {{--                                        data-route="{{route('application.submission.delete', ['school' => $school, 'application' => $application, 'submission' => $application->submissions[0]->id])}}"--}}
                            {{--                                        onclick="app.deleteApplication(this)">{{__('Delete')}}</button>--}}
                    @endif


                </div>
            </div>
            <div class="p-3 status-app col-md-3 col-lg-2">
                <span class="mt-2 app-col-header d-block d-md-none">{{__('Status')}}</span>
                <span class="status-app-text text-success">
                    <span class="badge badge-secondary">
                        {{--  {{\App\Helpers\Application\SubmissionHelpers::getTextSubmissionStatus($application)}}  --}}
                        {{\App\Helpers\Application\ApplicationStatusHelpers::lastStatusByUpdateDate($submission->statuses())['status']}}



                    </span>
                    <br>
                    {{$application->submissions[0]->updated_at->diffForHumans()}}
                </span>
            </div>

            @php
                //$invoice = $application->invoices()->where('student_id' , $submission->student->id)->first();
                $invoice = $submission->invoice;
            @endphp
            @if($invoice)
                <div class="p-3 reg-app col-md-3 col-lg-3">
                    <div class="flex-wrap d-block d-md-flex flex-column">
                    <span class="mt-2 app-col-header d-block d-md-none">{{__('Registration Fees')}}</span>
                    @if ($invoice && ($invoice->uid) )
                        @if ($invoice->isPaid )
                            <div class="status-app mb-1">
                                <span class="status-app-text text-success">
                                    <span class="badge badge-secondary">
                                        {{__('Invoice Paid')}}
                                    </span>
                                </span>
                            </div>

                        @endif
                        <div class="d-flex">

                        <a class="mb-2 btn btn-outline-primary btn-sm mbtn is-uppercase d-inline-block" target="_blank"
                            href="{{route('invoice.show', ['school'=>$school, 'invoice' => $invoice->uid, 'action' => 'view'] ) }}" style="max-width:180px">{{__('View Invoice')}}</a>

                        </div>
                    @endif

                    @if ($invoice && !$invoice->isPaid )
                        <a class="mb-2 ml-2 btn btn-outline-primary btn-sm mbtn is-uppercase mb-md-0 d-inline-block ml-md-0"
                            href="{{route( 'show.payment' , ['school'=>$school, 'application' => $application , 'invoice' => $invoice ] )}}" style="max-width:180px">{{__('Pay Invoice')}}</a>
                    @endif
                    </div>
                </div>
            @endif
        </div>
        @endforeach
    @endif
@endforeach
<!-- end row items -->


<!-- <div class="card">
    <div class="card-body">
        <div class="d-md-flex align-items-center">
            <div>
                <h4 class="card-title">{{__('Applications')}}</h4>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table no-wrap v-middle">
                <thead class="text-white bg-info">
                <tr class="border-0">
                    <th class="border-0">{{__('Application')}}</th>
                    <th class="border-0">{{__('Status')}}</th>
                    @if (isset($userApplication->first()->properties['application_fees']))
                        <th class="border-0">{{__('Registration Fees')}}</th>
                        <th class="border-0">{{__('Payments')}}</th>
                    @else
                        <th class="border-0"></th>
                        <th class="border-0"></th>
                    @endif
                    <th class="border-0"></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($userApplication as $application)
                        @php
                            $submission = $application->submissions->first();
                        @endphp
                    <tr>
                        <td>
                            @if (
                                    ($application->onTimeSubmission && $application->status == 'Submitted') ||
                                    (isset($submission->properties['lock']) && $submission->properties['lock'] == 1)
                                )

                                <span>{{$application->title}}</span>

                            @else
                                @if (isset($submission->data['assistant_id']) && !empty($submission->data['assistant_id']))
                                    <a href="{{route('application.show' , ['school' => $school , 'application' => $application, 'assistant' => $submission->data['assistant_id']])}}">
                                        {{__($application->title)}}
                                    </a>
                                @else
                                    <a href="{{route('application.show' , ['school' => $school , 'application' => $application])}}">
                                        {{__($application->title)}}
                                    </a>
                                @endif
                            @endif

                            <span class="d-block"><small>{!! __($application->description) !!}</small></span>
                        </td>

                        <td>
                            @if ( $application->status )
                                <span class="d-block">
                                    {{  __($application->status) }}
                                </span>

                                <span data-toggle="tooltip" data-placement="top"
                                    title="{{$application->submissions[0]->updated_at}}">{{$application->submissions[0]->updated_at->diffForHumans()}}
                                </span>
                                @if(isset($submission->properties['lock']))
                                    <div>
                                        @if($submission->properties['lock'] == 1)
                                            <span class="badge badge-secondary">
                                                 {{__('Locked')}}
                                            </span>
                                            @if(!isset($submission->properties['request_unlock']) || $submission->properties['request_unlock'] == 0)
                                                <span style="cursor: pointer" id="request_unlock"
                                                      class="btn-small btn-primary"
                                                      data-route="{{route('submissions.requestUnlock', $school)}}"
                                                      onclick="app.requestUnlock(this, {{$submission->id}})"
                                                >
                                                    {{__('Request Unlock')}}
                                                </span>
                                            @else
                                                <span id="request_unlock"
                                                      class="btn-small btn-primary"
                                                >
                                                    {{__('Unlock Requested')}}
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                @endif
                            @else
                                <a href="{{route('application.show' , ['school' => $school , 'application' => $application ])}}"
                                    class="btn btn-info">{{__('Submit Application')}}</a>
                            @endif
                        </td>

                        @if (isset($application->properties['application_fees']))
                            <td>
                                {{isset($application->properties['application_fees']) ? $application->properties['application_fees'] : 0.00  }}
                                {{isset($settings['school']['default_currency']) ? $settings['school']['default_currency'] : ''}}
                            </td>

                            <td>
                                @if (count($application->invoices) > 0 && count($application->invoices->first()->status) > 0)
                                    <span data-toggle="tooltip" data-placement="top"
                                          title="{{$application->invoices->first()->status->last()->created_at->diffForHumans()}}">
                                    {!! __(QuotationHelpers::getPaymentBadge(
                                    $application->invoices->first()->status->last()->status)) !!}
                                </span>
                                @else
                                    {{__('N/A')}}
                                @endif
                            </td>

                        @else
                            <td></td>
                            <td></td>
                        @endif
                        <td>
                            @include('front.applications._partials.application.actions')
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <div class="d-flex justify-content-center">
                        </div>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div> -->
