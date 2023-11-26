<div class="p-4 tab-pane fade l-psuedu-border bg-grey-1" id="nav-files" role="tabpanel" aria-labelledby="pills-files-tab">

        @if ($applicant->files->count())

        <div class="table-responsive m-t-10">
            <table class="table stylish-table">
                <thead>
                    <tr>
                        <th>{{__('File')}}</th>
                        <th>{{__('Size')}}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($applicant->files()->orderBy('created_at' , 'desc')->get() as $file)
                        <tr data-file="{{$file->id}}">
                            <td>
                                <h6><a href="{{ route('file.show' , ['fileName' => $file->name]) }}" target="_blank">{{$file->original_name}}</a></h6>
                                <small class="text-muted">{{__('Uploaded')}}: {{$file->created_at->diffForHumans()}}</small>
                            </td>
                            <td>{{$file->size}}</td>
                            <td>
                                <a href="{{ route('file.show' , ['fileName' => $file->name]) }}" target="_blank" class="btn btn-circle btn-light"><i class="icon-arrow-down-circle"></i></a>

                                <a href="javascript:void(0)" onclick="app.deleteElement( '{{route('student.file.delete' , [ 'school' => $school , 'student'=> $applicant , 'file' => $file])}}' , '' ,  'data-file' )"  class="btn btn-circle btn-light text-danger"><i class="icon-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        {{-- <div class="feed-widget ps-container ps-theme-default ps-active-y">
            <ul class="m-0 list-style-none feed-body p-b-20">
                @foreach ($applicant->files as $file)
                    <li class="feed-item" style="border-bottom:1px solid #f2f1f1">
                        <div>
                            <span><i class="icon-doc p-t-5"></i> {{$file->original_name}}</span>
                            <a href="{{ Storage::disk('s3')->temporaryUrl($file->name, \Carbon\Carbon::now()->addMinutes(5)) }}" class="text-muted d-block">Download</a>
                        </div>
                        <span class="ml-auto font-12 text-muted">{{$file->size}}</span>
                    </li>
                @endforeach

            </ul>
        </div>
 --}}
        @else

            @include('back.students._partials.student-no-results')

        @endif

    <div class="form-group">
            <label for="files">{{__('Add Files')}}</label>

            @php
                $allowed = (isset($properties['allowed']) ) ?  implode( "," ,$properties['allowed'] ):
                'jpg,gif,png,pdf,doc,docx,zip';
            @endphp

            <div class="fileuploader" data-allowed="{{ $allowed  }}" data-uploadstr="{{__('Drag & Drop Files')}}"
                 data-multiple = "true"
                 data-upload = "{{route('school.file.upload' , $school)}}"
                 data-destroy = "{{route('school.file.destroy', $school)}}"
                 data-student_id = "{{$applicant->id}}"
                 data-name = "files"
                 data-delete-btn-txt="{{__('Delete')}}"
                 data-delete-message="{{__('Are you sure?')}}"
                 data-delete-warning="{{__('You are going to delete this file permanently')}}"
            >
            </div>

            <div class="ajax-file-upload-container"></div>

        </div>
</div>
