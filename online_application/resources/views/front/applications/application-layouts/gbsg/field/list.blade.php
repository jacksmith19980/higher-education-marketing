
<div class="field_wrapper {{($properties['smart'])? 'smart-field ' : ''}} col-md-{{$properties['wrapper']['columns']}} col-sm-12 col-xs-12 {{$properties['wrapper']['class']}}" {{ (!$properties['show'])? 'data-hidden=true' : ' ' }}

	@smart($properties['smart'])
		data-field="{{$name}}"
		data-action="{{$properties['logic']['action']}}"
		data-reference="{{$properties['logic']['field']}}"
		data-operator="{{$properties['logic']['operator']}}"
		data-value="{{$properties['logic']['value']}}"
	@endsmart
>
		<div class="form-group">

		 @if($properties['label']['show'])<label for="{{$name}}">{{ __($properties['label']['text']) }}:</label>@endif

		 <select class="select2 form-control custom-select" style="width: 100%; height:36px;border:1px soild #CCC" name="{{$name}}"

		 @include('front.applications.application-layouts.gbsg.partials._validation-messages');

		>

			@if(!$properties['label']['show'])
	    		<option value="">{{$label}} @if(isset($properties['validation']['required']))  *@endif</option>
	    	@endif

	    	@if ($data)

				@foreach ($data as $k=>$v)
		      	    <option value="{{$k}}" @selected($value,$k) {{ ' selected ="selected"' }} @endselected >{{$v}}</option>
	      	    @endforeach
	    	@endif

	    </select>

	    @isset ($properties['helper'])
			<small id="{{$name}}" class="form-text text-info float-left helper-text">    	{{$properties['helper']}}
			</small>
		@endisset


			@if ($errors->has($name))
		        <span class="invalid-feedback" role="alert">
		            <strong>{{ $errors->first($name) }}</strong>
		        </span>
			@endif
		</div>
</div>
