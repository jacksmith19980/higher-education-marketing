<div class="form-group">
    
    @label($label)
        <label for="{{$name}}"><strong>{{ __($label) }}{!! ($required)? '<span class="text-danger">*</span>' : '' !!}</strong></label>

    @endlabel
    
    @if (isset($small_helper))
        <p><small>{{$small_helper}}</small></p>
    @endif
    
    @foreach ($data as $default=>$label)
        
    <div class="custom-control custom-radio" style="margin-top: 7px;">
            
            <input class="custom-control-input {{$class}}" id="{{$name.'_'.$default}}" name="{{$name}}" type="radio"
            value="{{$default}}" {{$attr}}  @checked($default, $value ) checked="checked" @endchecked >
            <label class="custom-control-label" for="{{$name.'_'.$default}}">{{ __($label) }}</label>
        </div>
        
        @endforeach
    @if ($errors->has($name))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first($name) }}</strong>
        </span>
    @endif
    
    @if(isset($deselect) && $deselect )
        <div>
            <a href="javascript:void(0)" onclick="app.deselectAll('{{$name.'_'.$default}}')" class="float-right btn waves-effect btn-sm waves-light btn-outline-danger">{{__('Deselect')}}</a>
        </div>
    @endif
    
</div>

