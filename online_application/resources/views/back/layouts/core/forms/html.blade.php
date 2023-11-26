<div class="form-group">
    @label($label)
    <label for="{{$name}}">{!! __($label) !!}{!! ($required)? '<span class="text-danger">*</span>' : '' !!}</label>
    @endlabel

    <textarea id="text_editor" {{$attr}} class="{{$class}} text_editor {{$name}}" name="{{$name}}">
        {{$value}}
    </textarea>

    @isset ($helper)

    <small id="{{$name}}" class="form-text text-info float-left helper-text">

        {{$helper}}

    </small>

    @endisset

    @if ($errors->has($name))
    <span class="invalid-feedback" role="alert">
        <strong>{{ $errors->first($name) }}</strong>
    </span>
    @endif

</div>
