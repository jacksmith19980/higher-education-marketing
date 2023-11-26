<div class="form-group">
    
    @label($label)
        <label for="{{$name}}"><strong>{{ __($label) }}{!! ($required)? '<span class="text-danger">*</span>' : '' !!}</strong></label>

    @endlabel

    @foreach ($data as $default=>$label)
        
    <div class="custom-control custom-checkbox" style="margin-top: 7px;">
            
            <input class="custom-control-input {{$class}}" id="{{$name.'_'.$default}}" name="{{$name}}" type="checkbox"
            value="{{$default}}" {{$attr}}  @checked($default, $value ) checked="checked" @endchecked >
            <label class="custom-control-label" for="{{$name.'_'.$default}}">{!! __($label) !!}</label>
        </div>
        
        @endforeach

    @if ($errors->has($name))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first($name) }}</strong>
        </span>
    @endif
</div>

