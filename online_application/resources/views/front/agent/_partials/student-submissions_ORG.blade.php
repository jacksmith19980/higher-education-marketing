@if ($agentStudent->submissions->count())
        
	@foreach ($agentStudent->submissions as $submission)
			
			<div class="btn-group">

			    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

			        {{$submission->application->title}}

			    </button>

			    <div class="dropdown-menu">

			        <a class="dropdown-item" href="{{route('school.agent.student.submit' , ['school' => $school , 'application' => $submission->application , 'student' => $agentStudent ])}}"><span class="icon-note"></span>  {{__('Edit')}}</a>

			        <a class="dropdown-item" target="_blank" href="{{route('agent.pdf.download' , ['submission' => $submission , 'school' => $school ,  'action' =>'download'])}}"><span class="icon-cloud-download">  </span>  {{__('Download')}}</a>
				
			    </div>

			</div>


	@endforeach

@else

	<div class="btn-group">

			    <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

			        {{__('No Applications')}}

			    </button>

				@if ($applications->count())
			    <div class="dropdown-menu">
						
				@foreach ($applications as $application)
			        <a class="dropdown-item" href="{{route('school.agent.student.submit' , ['school' => $school , 'application' => $application , 'student' => $agentStudent ])}}">{{$application->title}}</a>
				@endforeach		
			        
			    </div>
				@endif

			</div>


@endif
