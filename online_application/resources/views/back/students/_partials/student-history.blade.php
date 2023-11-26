<div id="nav-history" class="tab-pane fade" role="tabpanel" aria-labelledby="nav-history-tab">
    @foreach($applicant->submissions as $submission)
        @php
            $submission_s= $submission->statuses;
            $submission_statuses = $submission_s->sortBy('id');
            $application = $submission->application;
        @endphp
    @if($submission->application)
        <div id="application-{{$submission->application->id}}" class="p-4 nav-application l-psuedu-border bg-grey-1" data-submission-id="{{$submission->id}}">
            <div class="card-body">


                @if ($submission_statuses->count())

                    <div class="profiletimeline m-t-0">

                        @foreach ( $submission_statuses  as $submission_status)
                            @php
                                $student = $submission->student;
                            @endphp
                            <div class="sl-item">
                                <div class="sl-left">
                                    @if ($submission_status->student_id != null)
                                        @php
                                            $student = \App\Tenant\Models\Student::findOrFail($submission_status->student_id);
                                        @endphp

                                        @if($student->avatar)
                                            @php
                                                $storageUrl = env('AWS_URL').$student->avatar;
                                            @endphp
                                            <img src="{{$storageUrl}}" alt="user" class="rounded-circle"/>
                                        @else
                                            <img src="{{ asset('media/images/blankavatar.png') }}" id="imagePreview"
                                                 style="width: 100%; height: 100%; border-radius:100%;">
                                        @endif
                                    @endif
                                </div>

                                <div class="sl-right">
                                    <div>
                                        @if (isset($submission_status->properties['name']))
                                            <p>
                                                {{__('By')}}: <a
                                                        href="javascript:void(0)">{{$submission_status->properties['name']}}</a>
                                            </p>
                                        @endif
                                        <p>
                                            {{__('Status')}}:
                                            <small class="badge badge-pill {{SubmissionHelpers::getStatusClass($submission_status->status)}}">
                                                {{__(\App\Helpers\Application\ApplicationStatusHelpers::statusLabel($submission_status->status))}}</small>
                                        </p>

                                        <div class="like-comm">
                                            {{__('Application')}}:
                                            <a href="javascript:void(0)" class="link m-r-10">
                                                <small>
                                                    {{ $application->title }}</small>
                                            </a>
                                        </div>
                                        <span class="sl-date">
                                                {{$submission_status->created_at->diffForHumans()}}
                                            </span>
                                    </div>
                                </div>
                            </div>
                            <hr>

                        @endforeach
                    </div>
                @else

                    <div class="alert alert-warning">
                        <strong>No Results Found</strong>
                        <span class="d-block">there are none!</span>
                    </div>

                @endif


            </div>
        </div>

        @else
            <div class="alert alert-warning">
                <strong>No application Found</strong>
                <span class="d-block">there are none data to show!</span>
            </div>
        @endif
    @endforeach
</div>
