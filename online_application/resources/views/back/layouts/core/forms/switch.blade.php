<div class="form-group">
    @label($label)
        <label for="{{$name}}">{{ __($label) }}{!! ($required)? '<span class="text-danger">*</span>' : '' !!}</label>

        <br />
    @endlabel

    <input class="switch {{$class}}" id="{{$name}}" name="{{$name}}" type="checkbox"
    
    value="{{$default}}" {{$attr}}  @checked($default, $value ) checked="checked" @endchecked >
   
   <label for="{{$name}}">{{ __($helper_text) }}</label>

</div>
   
