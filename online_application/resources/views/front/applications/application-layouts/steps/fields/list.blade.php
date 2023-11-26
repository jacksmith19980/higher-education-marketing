<div class="col-md-{{$properties['wrapper']['columns']}} col-sm-12 col-xs-12 {{$properties['wrapper']['class']}} ">
	<div class="form-group">
	
		@if($properties['label']['show'])    
	    <label for="{{$name}}">{{ __($properties['label']['text']) }}:</label>
	    @endif

	    <select class="select2 form-control custom-select" style="width: 100%; height:36px;border:1px soild #CCC" name="{{$name}}" {{ $properties['validation']['required'] ? ' required' : '' }}>
			
			@if(!$properties['label']['show'])    
	    		<option value="">Select {{$label}}</option>
	    	@endif

	    	@if ($data)
	    		@foreach ($data as $k=>$v)
		      	    <option value="{{$k}}" @selected($value,$v) {{ ' selected ="selected"' }} @endselected >{{$v}}</option>
	      	    @endforeach
	    	@endif

	    </select>

	    @if ($errors->has($name))
	        <span class="invalid-feedback" role="alert">
	            <strong>{{ $errors->first($name) }}</strong>
	        </span>
	    @endif
	</div>
</div>
