<div class="form-group">

    @label($label)
        <label for="{{$name}}">{{ __($label) }}{!! ($required)? '<span class="text-danger">*</span>' : '' !!}</label>
    @endlabel

    <textarea
        id="{{$name}}"
        class="text_editor form-control{{ $errors->has('description') ? ' is-invalid' : '' }} {{$class}}"
        name="{{$name}}" {{$attr}}
        {{ $required ? ' required' : '' }}>{{ (isset($value))? $value : old('description') }}</textarea>
    @isset ($helper)
        <small id="{{$name}}" class="form-text text-info float-left helper-text">
            {{$helper}}
        </small>
    @endisset
    @if ($errors->has($name))
        <span class="invalid-feedback" role="alert" style="display: block">
            <strong>{{ $errors->first($name) }}</strong>
        </span>
    @endif
</div>
