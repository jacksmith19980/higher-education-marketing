<div class="{{($properties['smart'])? 'smart-field ' : ''}} col-md-{{$properties['wrapper']['columns']}} col-sm-12 col-xs-12 {{$properties['wrapper']['class']}}" {{ (!$properties['show'])? 'data-hidden=true' : ' ' }}
	
	@if ($properties['smart']))
		@foreach ($properties['logic'] as $key=>$rule)
			{{ "data-smart-$key = ".$rule['field'].":".$rule['operator'].":".$rule['value']." " }}
		@endforeach
	@endif
>
	<div class="form-group">
	
		@if($properties['label']['show'])    
	    <label for="{{$name}}">{{ __($properties['label']['text']) }}:</label>
	    @endif

	    <input id="{{$name}}" 
	    	   type="phone" 
	    	   class="form-control{{ $errors->has($name) ? ' is-invalid' : '' }} {{ ($properties['class']) }}" 
	    	   
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
