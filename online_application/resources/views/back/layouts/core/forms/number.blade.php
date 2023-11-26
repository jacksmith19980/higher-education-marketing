<div class="form-group">
    
    @label($label)
    
    <label for="{{$name}}">{{ __($label) }}{!! ($required)? '<span class="text-danger">*</span>' : '' !!}</label>
    
    @endlabel

    @if (isset($hint_before) || isset($hint_after))
        
            <div class="input-group-prepend">
    
    @endif

        @if (isset($hint_before))
           
            <span class="input-group-text" id="basic-addon1">{{$hint_before}}</span>
               
        @endif
        
 
    <input id="{{$name}}" 
    	   type="number" 
    	   class="form-control{{ $errors->has($name) ? ' is-invalid' : '' }} {{$class}} form-control-lg" 
            
            name="{{$name}}" 
    	  
            @if($value)
                    value="{{ $value }}" 
               @else
                    value="{{ old($name) }}" 
            @endif

    	   @if (isset($placeholder))
            placeholder="{{ __($placeholder) }}"
           @endif

    	   @if (isset($step))
            step="{{$step}}"
           @endif

           {{ $required ? ' required' : '' }}

           {{ $attr }} 

           >
           
           @if (isset($hint_after))
           
            <span class="input-group-text" id="basic-addon1">{{$hint_after}}</span>
               
           @endif


    @if (isset($hint_before) || isset($hint_after))
        </div>
    @endif
    
    @isset ($helper)
        <small id="{{$name}}" class="form-text text-info float-left helper-text">{{$helper}}</small>
    @endisset

    @if ($errors->has($name))
        <span class="invalid-feedback" role="alert" style="display:block">
            <strong>{{ $errors->first($name) }}</strong>
        </span>
    @endif


</div>

   
