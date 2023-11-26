@if ($file)
    <div
        class="ajax-file-upload-statusbar"
        style="width: 420px;padding:15px;"
        data-uploaded-file="{{$name}}"
        data-uploaded-file-name={{$file->name}}
        >

        <div class="ajax-file-upload-filename">
            @if (isset($type))
            <strong style="display:block">{{ ucwords($type) }}</strong>
            @endif
            <span>{{__('File')}}: {{ $file->original_name }} ({{$file->size}})</span>
        </div>
    <a href="javascript:void(0)" style="float: right;"

    data-title="{{__('Are you sure?')}}"
    data-text="{{__('You are going to delete this file permanently!')}}"
    data-button="{{__('Deleting..')}}"
    data-success-title="{{__('File Deleted!')}}"
    data-success-message="{{__("File Deleted Successfully")}}"

    class="ajax-file-upload-red" onclick="app.deleteUploadedFile('{{$file->name}}' , '{{route('school.file.destroy' , $school)}}' , '{{$name}}' , this , {{isset($type) ? true : false}})" >{{__('Delete')}}</a>

        <div class="clear"></div>
    </div>
@endif
