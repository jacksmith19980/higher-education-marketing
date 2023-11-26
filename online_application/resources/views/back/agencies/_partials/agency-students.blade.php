<div class="tab-pane fade show active" id="students" role="tabpanel" aria-labelledby="pills-timeline-tab">
    <div class="card-body">
        
            
            @php
                $totalAgencyApplications = 0;
                $totalSubmissions        = [];
                $students = $agency->students()->orderBy('created_at' , 'desc')->get();
            @endphp

            @if ($students->count())
                
            <div class="profiletimeline m-t-0">
                
                @foreach ( $students  as $student)
                
                <div class="sl-item">
                    <div class="sl-left"> 
                        
                        <img src="{{$student->avatar ? $storageUrl : asset('media/images/blankavatar.png')}}" alt="user" class="rounded-circle" /> </div>

                    <div class="sl-right">
                        <div>
                            <a href="{{route('students.show' , $student)}}" class="link">{{$student->name}}</a> 
                            <p class="text-muted">
                                {{$student->email}}
                            </p>
                            <p>
                                {{__('Created By')}}: <a href="javascript:void(0)">{{$student->agent->name}}</a>
                            </p>
                        
                            @if ($totalApplications = $student->submissions()->count())
                            
                            @php
                                    $totalAgencyApplications += $totalApplications;
                                    $totalSubmissions[] = $student->submissions()->pluck('status')->toArray();

                                @endphp
                                <div class="like-comm">
                                    <a href="javascript:void(0)" class="link m-r-10">
                                        {{Str::plural('Application', $totalApplications)}}:

                                        @foreach ($student->submissions as $studentSubmission)

                                            <small class="badge badge-pill {{SubmissionHelpers::getStatusClass($studentSubmission->status)}}">
                                                {{ $studentSubmission->application->title }}</small>

                                        @endforeach
                                    </a>
                                </div>
                            @endif

                            <span class="sl-date">
                                    {{__('Created at:')}} {{$student->created_at->diffForHumans()}}
                            </span>
                        
                        </div>
                    </div>
                </div>
                <hr>

                @endforeach

                @php
                session()->put('totalAgencyApplications-'.$agency->id , $totalAgencyApplications);
                session()->put('totalSubmissions-'.$agency->id , $totalSubmissions);
                @endphp
            </div> 
            @else

                <div class="alert alert-warning">
                    <strong>No Results Found</strong>
                    <span class="d-block">there are none! {{$agency->name}} didn't add any student yet!</span>
                </div>
            
            @endif
      
    </div>
</div>