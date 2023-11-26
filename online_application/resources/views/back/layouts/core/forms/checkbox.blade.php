<div class="form-group">

    @label($label)
        <label for="{{$name}}">{{ __($label) }}{!! ($required)? '<span class="text-danger">*</span>' : '' !!}</label>

    @endlabel


    <div class="custom-control custom-checkbox" style="margin-top: 7px;">

        <input class="custom-control-input {{$class}}" id="{{$name}}" name="{{$name}}" type="checkbox"
        value="{{$default}}" {{$attr}}  @checked($default, $value ) checked="checked" @endchecked
        >

        <label class="custom-control-label" for="{{$name}}"

        @isset ($helper)
            data-toggle="tooltip" data-container="body" data-placement="top" title="" data-original-title="{{$helper}}"
        @endisset
        >
        {{ __($helper_text) }}

        @isset ($helper)
            <p><small id="{{$name}}" class="form-text text-info float-left helper-text">{{__($helper)}}</small></p>
        @endisset

    </label>
    </div>


    @if ($errors->has($name))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first($name) }}</strong>
        </span>
    @endif
</div>
