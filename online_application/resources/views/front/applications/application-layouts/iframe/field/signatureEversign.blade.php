

<div class="col-md-12">
    
    <input type="hidden" name="document_hash" value="{{isset($submission['data']['document_hash']) ? $submission['data']['document_hash'] : '' }}">

    <input type="hidden" name="document_signed" value="{{isset($submission['data']['document_signed']) ? $submission['data']['document_signed'] : '' }}">

    <input type="hidden" name="document_signed_at" value="{{isset($submission['data']['document_signed_at']) ? $submission['data']['document_signed_at'] : '' }}">

    @if (isset($submission['data']['document_signed_at']) && isset($submission['data']['document_hash']) )

            @php
                $downloadLink = Sign::downloadLink($submission['data']['document_hash']);
            @endphp
            
        @if ($downloadLink['success'])
            <div class="alert alert-success">   
                
                <p><strong>{{$field->label}}</strong> {{__('Signed Successfully')}}</p>
                <p><a href="https://{{ $downloadLink['url'] }}" target="_blank">{{__('Click here to download your copy!')}}</a></p>
                
            </div>
        @else
            <div class="alert alert-warning">   
                <p>{{__('Pending for approval')}}</p>
            </div>
            
        @endif
        

    @else 
    
        @if (isset($field->properties['beforeSignText']))
            <div class="col-md-12">
                {!!  $field->properties['beforeSignText']  !!}
            </div>
        @endif
    
        <div
                class="eversigned-body"
                id="eversigned-body"
                data-eversign_url='{{ route("sign.eversign" , [ 'school' => $school , 'application' => $application ]) }}'
                data-field-id="{{ $field->id }}"
        >
        </div>
        <script type="text/javascript"
                src="https://s3.amazonaws.com/eversign-embedded-js-library/eversign.embedded.latest.js"></script>
    @endif
