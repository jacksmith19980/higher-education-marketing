@if ( count($app_student->submissions) > 0 )



	@foreach ($app_student->submissions as $submission)

		@if (isset($submission->application))

			<div class="btn-group">

			    <button type="button" class="btn btn-success btn-small dropdown-toggle m-a-10 d-block ma-20" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			        {{$submission->application->title}}
			    </button>

			    <div class="dropdown-menu">

			        <a class="dropdown-item" target="_blank" href="{{route('pdf.view' , ['submission' => $submission , 'action' =>'view'])}}"><span class="icon-eye">  </span>  {{__('View')}}</a>

			        <a class="dropdown-item" target="_blank" href="{{route('pdf.view' , ['submission' => $submission , 'action' =>'download'])}}"><span class="icon-cloud-download">  </span>  {{__('Download')}}</a>

					<a class="dropdown-item" target="_blank" href="{{route('pdf.view' , ['submission' => $submission , 'action' =>'save'])}}"><span class="icon-cloud-download">  </span>  {{__('Save')}}</a>

			    </div>

			</div>

		@endif






	@endforeach



@else



	<small class="badge badge-default badge-danger text-white">{{__('No Applications')}}</small>



@endif
