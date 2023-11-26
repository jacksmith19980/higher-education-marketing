@php
    $allowed = (isset($properties['allowed']) ) ? implode( "," ,$properties['allowed'] ):
'jpg,gif,png,pdf,doc,docx,zip';
@endphp

@if (!empty($value))

    @foreach ( json_decode($value, true) as $val)
        @if ( $file = FilesHelper::getFile($val['file']) )
                @php
                    $files[] = [
                        'file' => $file,
                        'type' => $val['type']
                    ];
                @endphp
        @endif
    @endforeach
@endif


<div class="fileuploader fileUploadList hide"
    data-allowed="{{ $allowed }}"
    @isset ($properties['multiple']) data-multiple={{ $properties['multiple'] }} @endisset
    data-upload="{{route('school.file.upload' , ['school' => $school])}}"
    data-destroy="{{route('school.file.destroy', $school)}}"
    data-name="{{$name}}"
    data-list="true"
    data-type=""
>
</div>

@isset ($properties['helper'])
<small id="{{$name}}" class="form-text text-info float-left helper-text">
    {{$properties['helper']}}
</small>
@endisset

<input type="text" style="opacity: 0;height: 0;margin-top: -10px;"
    class="{{$name}} form-control {{ $errors->has($name) ? ' is-invalid' : '' }} fileHolder multiFileHolder" name="{{$name}}"
    style="width:100%" value="{{ isset($files) ? $value : '' }}"
    @include('front.applications.application-layouts.gbsg.partials._validation-messages'); />

<div class="error_{{$name}} fileError" style="margin-top:-20px;"></div>

@if (isset($files))
    <div style="margin-top: 10px;">
        @foreach ( $files as $file)
            @include('front.applications.application-layouts.oiart.file.uploaded-file' , [
            'file' => $file['file'],
            'name' => $name,
            'type' => $file['type']
            ])
        @endforeach
    </div>
@endif
