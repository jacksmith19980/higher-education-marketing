@php
    $submission_link = SubmissionHelpers::getLink($submission);
@endphp
<div id="application-{{$submission->application->id}}" class="py-3 px-4 nav-application l-psuedu-border bg-grey-1" data-submission-id="{{$submission->id}}">

        @if($canView)
        <a class="btn btn-light bg-primary btn-toggler collapsed float-right-btn" data-toggle="collapse" href="#appbodyCollapse-{{$submission_link}}" role="button" aria-expanded="false" aria-controls="appbodyCollapse" >
            <i class="text-white mdi mdi-plus" data-toggle="tooltip" data-placement="top" title="{{__('View More')}}">
            </i>
        </a>
        @endif

        <div class="py-2 pl-2 pr-0 row application-header justify-content-between pl-md-4" data-parent="#accordion_application_action" href="#application-{{$submission->application->id}}">

            <div class="col-12 col-md-7">
                <div class="d-flex">

                    @php
                        $statuses = $submission->statuses->pluck('status')->toArray();
                    @endphp
                    <div class="af-column-title">
                        <h5 class="mb-3">{{__('Application Form')}}</h5>

                        <h4><span id="{{$submission_link}}">{{$submission->application->title}}</span> {{$submission->id}}</h4>
                        <div class="application-statuses d-flex flex-wrap mb-3 mb-md-0">
                            <span class="pr-3"><i class="icon-clock text-secondary"></i> {{__('Created')}}: {{ $submission->application->created_at->translatedFormat('F j, Y') }}</span> <span class="pr-3"><i class="icon-refresh text-secondary"></i> {{__('Updated')}}: {{ $submission->application->updated_at->translatedFormat('F j, Y') }}</span> <span><i class="icon-info"></i> {{__('Step')}}: {{ $submission->application->step ? $submission->application->step : "N/A" }}</span>

                        </div>
                    </div>

                </div>
            </div>

            <div class="col-12 col-md-5 pr-0">
                @php
                    $disabled =  !$canEdit ? 'data-disabled="disabled"' : ''
                @endphp
                <div class="row w-100 h-100 align-items-center justify-content-between">
                    <div class="col-9 col-md-9 af-column-title">
                        <h5 class="mb-3">{{__('Submission Status')}}</h5>
                        <div class="btn editable-btn">
                            <span
                            {{$disabled}}
                            class="editable editable-click editable-open singleClick"
                            data-placement="top"
                            data-name="status"
							data-type="select"
							data-source="{{ route('application.status.list', $submission) }}"
                            data-value="{{$submission->statusLast()}}"
                            data-url="{{route('application.status.updatepost', $submission)}}"
                            data-validation="{&quot;required&quot;:&quot;This field is required&quot;}"
                            data-original-title=""
                            title="" aria-describedby="popover757227">
                            {{$submission->statusLast()}}
                            </span>
                            <i class="fas fa-caret-right"></i>
                        </div>
                    </div>
                    @if($canView || $canEdit || $canDelete)
                        <div class="col-3 col-md-3 mt-2 px-0">
                            <div class="btn-group more-optn-group float-right is-circle">
                                <button type="button"
                                class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split ti-more-alt is-circle flat-btn"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                </button>
                                <div class="dropdown-menu">

                    @endif
                    @if($submission->statusLast()== 'Archived')
                        <a class="dropdown-item" data-toggle="tooltip" href="javascript:void(0)" role="button" data-placement="top" data-original-title="{{__('Restore')}}"
                        onclick="app.restoreSubscription('{{$submission->id}}'); location.reload()">
                        <img src="/assets/images/restore.svg" class="custom-icon bg-white" width="12px"><span class="pl-2 icon-text">{{__('Restore Submission')}}</span>
                        </a>
                    @else
                        <a class="dropdown-item" data-toggle="tooltip" href="javascript:void(0)" role="button" data-placement="top" data-original-title="{{__('Archive')}}"
                        onclick="app.archiveSubscription('{{$submission->id}}'); location.reload()">
                        <img src="/assets/images/archive.svg" class="custom-icon bg-white" width="12px"><span class="pl-2 icon-text">{{__('Archive Submission')}}</span>
                        </a>
                    @endif
                    @if($canView)
                    <a
                    href="{{route('pdf.view' , ['submission' => $submission , 'action' =>'view'])}}" target="_blank"
                    class="dropdown-item" data-toggle="tooltip" data-placement="top"
                    data-original-title="{{__('View Application PDF')}}">
                    <i class="mr-1 far fa-file-pdf"></i><span class="pl-2 icon-text">{{__('View PDF')}}</span>
                    </a>
                    @endif

                    @if (isset($integratable) && $integratable)
                        <a href="javascript:void(0)"
                        onclick="app.resyncSubmission({{$submission->id}}, {{$submission->student->id}}, this)"
                        class="dropdown-item" data-toggle="tooltip" data-placement="top"
                        data-original-title="{{__('Resync Submission')}}">
                        <i class="mr-1 ti-reload"></i><span class="pl-2 icon-text">{{__('Resync')}}</span></a>
                    @endif
                    @if ($canDelete)
                        <a class="dropdown-item" data-toggle="tooltip" href="javascript:void(0)" role="button" data-placement="top" data-original-title="{{__('Delete Submission')}}"
                        onclick="app.deleteElement('{{route('submissions.destroy' , $submission)}}','','data-submission-id')">
                            <i class="mr-1 text-white icon-trash"></i><span class="pl-2 icon-text">{{__('Delete Submission')}}</span>

                        </a>
                    @endif

                                </div>
                            </div>
                        </div>

                </div>
            </div>
        </div>
        @if($canView)
        <div class="collapse @if(isset($request_link) && $request_link === $submission_link){{'show'}}@endif" id="appbodyCollapse-{{$submission_link}}">
            <div class="pl-2 pl-md-3 accordion pl-md-4 tab-accordion">
                <div class="review-page" id="{{$submission->id}}" data-route="{{route('submissions.review', [$submission])}}">
                </div>
            </div>
        </div>
        @endif
    </div>
