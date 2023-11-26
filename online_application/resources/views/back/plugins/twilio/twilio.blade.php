<div class="card">
    <div class="card-body">
        <div class="d-flex flex-row">
            <div class="align-self-center plugin-img-container">
                <img class="img" src="{{$helper->config('icon')}}">
            </div>
            <div class="m-l-10 align-self-center">
                <a href="JavaScript:void(0);"
                    onclick="app.setupPlugin('{{ route('plugins.setup' , $helper->plugin )}}' , 'Setup {{ucwords($helper->plugin)}}' , false)"
                    class="d-block active-link">
                    <div class="m-t-10" style="text-align:left">
                        <h4 class="font-medium m-b-0">{{$helper->config('name')}}</h4>
                        @if ($helper->isActive())
                        <small class="plugin-status text-success">{{__('ACTIVE')}}</small>
                        @else
                        <small class="plugin-status text-danger">{{__('INACTIVE')}}</small>
                        @endif
                    </div>
                </a>

            </div>
        </div>
    </div>
</div>
