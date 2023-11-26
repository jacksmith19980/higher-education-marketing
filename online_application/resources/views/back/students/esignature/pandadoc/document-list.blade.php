<table class="table responsiveDataTable display new-table nowrap w-100 responsive dataTable">
            <thead>
                <tr>
                    <th>{{__('Document Title')}}</th>
                    <th>{{__('Action')}}</th>
                </tr>
            </thead>
        <tbody>

            @foreach($list as $documentId => $name)
            <tr>
                <td>
                    {{$name}}
                </td>
                <td>
                    <a href="javascript:void(0)" class="ml-2 mr-2 btn btn-circle small-btn btn-light text-dark" data-toggle="tooltip" onclick="app.downloadContractDocument('{{$documentId}}', '{{str_replace('.pdf' , '' , $name)}}' ,'{{$contract->properties['envelopeId']}}' , true , this)" data-placement="top" title="" data-original-title="{{__('Download Document')}}">
                    <i class="ti-download"></i>
                    </a>

                    <a href="javascript:void(0)" class="ml-2 mr-2 btn btn-circle small-btn btn-light text-dark" data-toggle="tooltip" onclick="app.downloadContractDocument('{{$documentId}}', '{{str_replace('.pdf' , '' , $name)}}' ,'{{$contract->properties['envelopeId']}}' , false , this)" data-placement="top" title="" data-original-title="{{__('View Document')}}">
                    <i class="icon-eye"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
</table>
