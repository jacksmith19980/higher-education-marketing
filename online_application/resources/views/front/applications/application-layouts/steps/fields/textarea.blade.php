<div class="col-md-{{$properties['wrapper']['columns']}} col-sm-12 col-xs-12 {{$properties['wrapper']['class']}} ">
    <div class="form-group">
    
        @if($properties['label']['show'])    
        <label for="{{$name}}">{{ __($properties['label']['text']) }}:</label>
        @endif
    
        <textarea 
        id="{{$name}}" 

        class="form-control{{ $errors->has($name) ? ' is-invalid' : '' }} {{$properties['class']}}" 
        
        name="{{$name}}"
        
        @if(!$properties['label']['show'])  
                    placeholder="{{$label}}" 
        @endif
        {{ $properties['validation']['required'] ? ' required' : '' }}>@if($value){{ $value }}@else{{ old($name) }}@endif</textarea>

        @if ($errors->has($name))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first($name) }}</strong>
            </span>
        @endif
    </div>
</div>
