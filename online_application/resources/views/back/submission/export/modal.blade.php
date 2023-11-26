<table id="submissions_table" data-route="{{route('submissions.getSubmissions')}}" class="table table-bordered new-table nowrap display">

    <thead>
    <tr>
        <th>{{__('Application')}}</th>
        <th>{{__('Action')}}</th>
    </tr>

        @foreach($submissions as $submission)
            @if($submission->application)
            <tr>
                <td>{{$submission->application->title}}</td>
                <td><a href="{{route('submissions.download.excel', [
                    'filters'       => $filters,
                    'file'          => $file,
                    'application'   => $submission->application->id
                ])}}" class="btn btn-default">{{__('Download')}}</a></td>
            </tr>
            @endif
        @endforeach
    </thead>
</table>
