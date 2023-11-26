<div class="col-lg-6 col-md-6 col-sm-12 draggable">
    <div class="card new-card card-hover recent-accounts">
        <div class="card-body">
            <div class="d-flex card-header two-col-header justify-content-between pr-2">
                <h4 class="card-title">
                    <i class="flaticon-leader pr-2 regular-icon"></i>
                    {{__('Recent Submissions')}}
                </h4>
                <a href="{{ route('submissions.index') }}" class="text-center text-primary">
                    <small>{{__('VIEW ALL')}}</small>
                </a>
            </div>
            <div class="striped-row card-content">
                <div class="d-flex flex-row card-subheader justify-content-between pl-4 pr-3 py-0">
                    <div class="d-block">{{__('Name')}}</div>
                    <div>{{__('Status')}}</div>
                </div>
                @foreach($latestSubmissions as $submission)
                    @if (!$submission->student)
                        @continue
                    @endif
                    <div class="d-flex justify-content-between align-items-center pl-4 pr-3">
                        <div class="d-block">
                            <a href="{{route('students.show', $submission->student)}}">
                                <h6 class="font-medium" x-text="applicant.name">{{$submission->student->name}}</h6>
                            </a>
                        </div>
                        <div>
                            @if (count($submission->statuses) > 0)
                                <span class="badge badge-default badge-info text-white d-block">{{$submission->statuses->last()->status}}</span>
                            @else
                                <span class="badge badge-default badge-info text-white d-block">{{$submission->status}}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
