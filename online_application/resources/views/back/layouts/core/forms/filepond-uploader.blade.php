<div class="form-group">

    @label($label)
        <label for="{{$name}}">{{ __($label) }}{!! ($required)? '<span class="text-danger">*</span>' : '' !!}</label>
    @endlabel

    <input id="{{$name}}"
        class="filepond filePondUploader {{$class}}"
        name="{{$name}}"
        {{($multiple) ? 'multiple' : ''}}
        data-allow-reorder="{{$reorder}}"
        data-max-file-size="{{$maxSize}}MB"
        data-max-files="{{$maxFiles}}"
        data-label-idle="{{isset($labelIdle) ? __($labelIdle ): false}}"

        {{(isset($acceptedFileTypes)) ? 'data-allowed='.$acceptedFileTypes : ''}}

        {{ $required ? ' required' : '' }}
        {{$attr}}
        {{ $uploadUrl ? ' data-upload-url=' . $uploadUrl : '' }}
        {{ $deleteUrl ? ' data-delete-url=' . $deleteUrl : '' }}

    >

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
