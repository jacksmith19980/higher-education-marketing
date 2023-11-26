@php

	$file = FilesHelper::getFile($fileName);

@endphp



<div class="ajax-file-upload-statusbar" style="width: 420px;">

    

    <div class="ajax-file-upload-filename">{{ $file->original_name }} ({{$file->size}})</div>



    	<a href="javascript:void(0)" style="float: right;" class="ajax-file-upload-red" onclick="app.deleteUploadedFile('{{route('school.file.destroy' , $school)}}' , {{ $file->id }});">Delete</a>

    

    <div class="clear"></div>

</div>