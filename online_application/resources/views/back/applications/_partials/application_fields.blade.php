<div id="sortable" class="container">

    <div class="card">
        <div class="card-header bg-info">
            <div class="d-flex align-items-center handle">
                <h4 class="m-b-0 text-white">{{__('Section 1')}}</h4>

                <div class="w-0 ml-auto">
                    <div class="btn-group">
                        <button type="button" class="btn btn-info text-white"><i class="ti-pencil-alt"></i></button>
                        <button type="button" class="btn btn-info text-white"><i class="ti-trash"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="inline-scroll">
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <div class="alert alert-warning" style="text-align: left">
                            <h3 class="text-warning"><i class="fa fa-exclamation-circle"></i> {{__('Start Now')}}</h3> {{__('Get started by adding your first field.')}}
                            <p style="padding: 15px 40px 0 40px;color:#fff">

                                <a href="javascript:void" class="btn waves-effect waves-light btn-block btn-info add_field_toggle">{{ __('Add Field')}}</a></p>
                        </div>
                    </div>
                </div>
            </div>{{-- inline-scroll --}}
        </div>
    </div>

</div>
