<div class="form-group">
    
    @label($label)
    
    <label for="{{$name}}">{{ __($label) }}{!! ($required)? '<span class="text-danger">*</span>' : '' !!}</label>
    
    @endlabel

    <input id="{{$name}}" 
    	   type="text" 
    	   class="datepicker-autoclose form-control{{ $errors->has($name) ? ' is-invalid' : '' }} {{$class}} form-control-lg" 
            
            name="{{$name}}" 
    	  
            @if($value)
                    value="{{ $value }}" 
               @else
                    value="{{ old($name) }}" 
            @endif

    	  
           {{ $required ? ' required' : '' }}

           {{ $attr }} 

           >

    @if ($errors->has($name))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first($name) }}</strong>
        </span>
    @endif
</div>

