@extends('back.layouts.core.helpers.table' ,
        [
            'show_buttons' => false,
            'title'        => $application->title,
        ]
    )
@section('table-content')
    <div class="container-fluid">
        <div class="row">

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
                                            <img src="{{$storageUrl}}" alt="user" class="rounded-circle" />
                                        @else
                                            <img src="{{ asset('media/images/blankavatar.png') }}" id="imagePreview" style="width: 100%; height: 100%; border-radius:100%;">
                                        @endif
                                    @endif
                                </div>

                                <div class="sl-right">
                                    <div>
                                        <p>
                                            {{__('By')}}: <a href="javascript:void(0)">{{$submission_status->properties['name']}}</a>
                                        </p>
                                        <p>
                                            {{__('Submission')}}:
                                            <small class="badge badge-pill {{SubmissionHelpers::getStatusClass($submission_status->status)}}">
                                                {{__($submission_status->status)}}</small>
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
    </div>
@endsection