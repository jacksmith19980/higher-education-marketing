<div class="col-12">
<div class="card">
    <div class="card-body">

        <div class="d-flex justify-content-between">
            <div>
                <h4 class="m-b-0">{{__('Active Api Key')}}</h4>
                @if($key)
                <span class="text-muted">
                    {{__('Last Update')}}:
                    {{$key->created_at->diffForHumans()}}
                </span>
                @else
                <span class="text-muted">
                    {{__('Last Update')}}: {{__('Never')}}
                </span>
                @endif
            </div>

            <div>
                @if($key)
                    <p class="m-b-0 api_key_wrapper">*****-*****-*****-*****</p>

                    <a class="show_button" href="javascript:void(0)" onclick="app.showApiKey('{{$key->api_key}}' , this)"><span class="text-muted">{{__('Show')}}</span></a>

                    <a class="hidden hide_button" href="javascript:void(0)" onclick="app.hideApiKey(this)"><span class="text-muted">{{__('Hide')}}</span></a>

                @else
                <div class="disabled" style="cursor: not-allowed">
                    <p class="m-b-0 api_key_wrapper text-muted">*****-*****-*****-*****</p>
                    <span class="text-muted">{{__('Show')}}</span>
                </div>
                @endif

            </div>

            <div>
                @if($key)
                <div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{__('Actions')}}
                    </button>
                        @if (isset($permissions['edit|settings']) && $permissions['edit|settings'])
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0)" onclick="app.generateApiKey()">
                                    {{__('Renew the Key')}}
                                </a>

                                <a class="dropdown-item" href="javascript:void(0)" onclick="app.deactivateApiKey()">
                                    {{__('Deactivate the Key')}}
                                </a>
                            </div>
                        @endif

                </div>
                @else
                    <a href="javascript:void(0)" onclick="app.generateApiKey()" class="btn btn-success">{{__('Generate Api Key')}}</a>
                @endif
            </div>
        </div>
    </div>
</div>

</div>
