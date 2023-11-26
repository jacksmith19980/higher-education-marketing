<div class="col-md-{{$properties['wrapper']['columns']}} col-sm-12 col-xs-12 {{$properties['wrapper']['class']}} ">
	<div class="form-group">
	
		@if($properties['label']['show'])    
	    <label for="{{$name}}">{{ __($properties['label']['text']) }}:</label>
	    @endif

	    <input id="{{$name}}" 
	    	   type="text" 
	    	   class="pickadate-select-year form-control{{ $errors->has($name) ? ' is-invalid' : '' }} {{$properties['class']}}" 
	    	   
	    	   name="{{$name}}" 
			   	
			   	@if(!$properties['label']['show'])  
	    	   		placeholder="{{$label}}" 
	    	    @endif

	    	   @if($value)
	    	   		value="{{ $value }}" 
			   @else
	    	   		value="{{ old($name) }}" 
			   @endif


	    	   {{ $properties['validation']['required'] ? ' required' : '' }}>

	    @if ($errors->has($name))
	        <span class="invalid-feedback" role="alert">
	            <strong>{{ $errors->first($name) }}</strong>
	        </span>
	    @endif
	</div>
</div>
