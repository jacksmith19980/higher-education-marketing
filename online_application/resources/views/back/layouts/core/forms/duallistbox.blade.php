<div class="form-group">
    @label($label)
    <label for="{{$name}}">{{ __($label) }}{!! ($required)? '<span class="text-danger">*</span>' : '' !!}</label>
    @endlabel

    <select
            class="form-control duallistbox {{$class}}"
            name="{{$name}}"
            {{$attr}}
            multiple="multiple"
            {!! ($required)? 'required="required"' : '' !!}
    >

        @foreach ($data as $k=>$val)
            @if (is_array($value) && (in_array($k , $value) || (array_key_exists($k , $value))))
                @php
                    $selected = 'selected="selected"';
                @endphp
            @else
                @php
                    $selected = ' ';
                @endphp
            @endif

            <option value="{{$k}}" {{$selected}} >{{$val}}</option>

        @endforeach

    </select>

    @isset ($helper)
        <small id="{{$name}}" class="form-text text-info float-left helper-text">{{$helper}}</small>
    @endisset

    @if ($errors->has($name))

        <span class="invalid-feedback" role="alert">

            <strong>{{ $errors->first($name) }}</strong>

        </span>

    @endif
</div>
