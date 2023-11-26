<div class="col-md-12 file_list_uploader">
    <div class="row">
        
        <div class="col-md-12">
            {!! $params['properties']['details'] !!}
        </div>
    </div>
    <div class="row">

        <div class="col-md-12 document_type">
            @include('front.applications.application-layouts.iframe.file_list.list' , $params)
        </div>
        
        <div class="col-md-12 document_uploader" style="margin-top:15px;">
            @include('front.applications.application-layouts.iframe.file_list.uploader' , [
                'properties' => $params['properties'],
                'name'       => $params['name'],
                'value'      => isset($params['value']) ? $params['value'] : ''
            ] )
        </div>

        <div class="clearfix clear"></div>
    </div>
</div>