<div class="form-group">
    @label($label)

        <label for="{{$name}}">{{ __($label) }}{!! ($required)? '<span class="text-danger">*</span>' : '' !!}</label>

    @endlabel

    <select class="form-control custom-select select2 {{$class}} form-control-lg"  {!! ( isset($addNewRoute) and $addNewRoute ) ? 'data-route="' . $addNewRoute . '"' : '' !!} name="{{$name}}" {{$attr}}
    {!! ($required)? 'required="required"' : '' !!}
    >
        @if (isset($placeholder))
            <option value="" selected ="selected"> {{ __($placeholder) }} </option>
        @endif

        @foreach ($data as $k=>$v)

            @if (is_array($v))

                <optgroup label="{{$k}}">

                    @foreach ($v as $val=>$label)

                        <option @if(isset($sharedIds) and $sharedIds) value="{{$val . ',' . $k}}" @else value="{{$val}}" @endif {{($value == $val)? ' selected="selected"' : ' '}} >{{__($label)}}</option>

                    @endforeach

                </optgroup>

            @else

                <option value="{{$k}}" {{($value == $k)? ' selected="selected"' : ' '}} >{{__($v)}}</option>

            @endif

        @endforeach
    </select>


    @if (isset($helper_text))
            <small class="text-info">{{$helper_text}}</small>
    @endif

    @if ($errors->has($name))
        <span class="invalid-feedback" role="alert" style="display: block">
            <strong>{{ $errors->first($name) }}</strong>
        </span>
    @endif
</div>
