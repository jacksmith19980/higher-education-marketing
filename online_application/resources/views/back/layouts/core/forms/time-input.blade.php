<div class="form-group">
    @label($label)

    <label for="{{$name}}">{{ __($label) }}{!! ($required)? '<span class="text-danger">*</span>' : '' !!}</label>

    @endlabel

    <input id="{{$name}}"
            type="time"
            class="form-control{{ $errors->has($name) ? ' is-invalid' : '' }} {{$class}} form-control-lg"
            name="{{$name}}"

        @if($value)
            value="{{ $value }}"
        @else
            value="{{ old($name) }}"
        @endif

        @if (isset($placeholder))
            placeholder="{{$placeholder}}"
        @endif

        {{ $required ? ' required' : '' }}

        {{ $attr }}
    >
    @isset ($helper)
        <small id="{{$name}}" class="form-text text-info float-left helper-text">{{$helper}}</small>
    @endisset

    @if ($errors->has($name))
        <span class="invalid-feedback" role="alert" style="display:block">
            <strong>{{ $errors->first($name) }}</strong>
        </span>
    @endif
</div>