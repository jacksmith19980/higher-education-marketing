@if ($child->submissions->count())
        
	@foreach ($child->submissions as $submission)
			
			<div class="btn-group">

				<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

			        {{$submission->application->title}}

			    </button>

			    <div class="dropdown-menu">

			        <a class="dropdown-item" href="{{route('school.parent.child.submit' , ['school' => $school , 'application' => $submission->application , 'student' => $child ])}}"><span class="icon-note"></span>  {{__('Edit')}}</a>
				
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
			        <a class="dropdown-item" href="{{route('school.parent.child.submit' , ['school' => $school , 'application' => $application , 'student' => $child ])}}">{{$application->title}}</a>
				@endforeach		
			        
			    </div>
				@endif

			</div>


@endif
