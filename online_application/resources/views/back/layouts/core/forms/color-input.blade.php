<!-- <div class="form-group row">
    <label for="example-color-input" class="col-2 col-form-label">Color</label>
    <div class="col-10">
        <input class="form-control" value="#563d7c" id="example-color-input" type="color">
    </div>
</div>
 -->
<div class="form-group">
    
    @label($label)
         <label for="{{$name}}">{{ __($label) }}{!! ($required)? '<span class="text-danger">*</span>' : '' !!}</label>
    @endlabel
    
    <input id="{{$name}}" 
    	   type="input" 
    	   class="form-control{{ $errors->has($name) ? ' is-invalid' : '' }} {{$class}} colorpicker form-control-md" 
    	   name="{{$name}}" 
    	  
            @if($value)
                    value="{{ $value }}" 
               @else
                    value="{{ old($name) }}" 
            @endif

    	  
           {{ $required ? ' required' : '' }}

           {{ $attr }} 

           >
    @if (isset($helper_text))
            <small class="text-info">{{$helper_text}}</small>
    @endif
    
    @if ($errors->has($name))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first($name) }}</strong>
        </span>
    @endif
</div>

