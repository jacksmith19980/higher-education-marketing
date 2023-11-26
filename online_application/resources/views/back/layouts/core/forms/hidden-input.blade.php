<input id="{{$name}}" 
    	   type="hidden" 
    	   class="form-control {{$class}}" 
    	   name="{{$name}}" 

            @if($value)
             value = "{{$value}}"      
               @else
                    value="{{ old($name) }}" 
            @endif

           {{ $required ? ' required' : '' }}
           {{ $attr }} 
>
