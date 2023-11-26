@extends('back.layouts.default')

@section('styles')
<style>

</style>
@endsection

@section('content')

    <div class="row justify-content-center">

        <div class="col-12">
            <div class="card" style="padding-right: 20px;">
                <div class="card-body">
    
                    <div class="float-left">
                        <h4 class="page-title">{{__('Email Templates')}}</h4>
                    </div>
    
                    @if($permissions['create|email_template'])
                    <div class="float-right btn-group">
                        <a href="{{ route('email-templates.create') }}" class="btn btn-secondary add_new_btn text-light">{{__('Add New Template')}}</a>
                    </div>
                    @endif

                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card hasShadow" style="box-shadow: rgb(15 15 15 / 15%) 0px 10px 10px 1px; padding-top: 20px">
                <div class="card-body" id="table-card" style="padding: 15px!important;padding-bottom: 30px!important;">
                    
                    <div class="row pb-2" id="datatableNewFilter">
                        <div class="col-md-4 col-xs-12 d-flex" id="lenContainer">
                        </div>
                    </div>
                    <table id="email_templates_table" data-permissions="{{ json_encode($permissions) }}" data-url="{{ route('email.templates.list') }}"
                            class="table responsiveDataTable display new-table nowrap w-100 responsive dataTable">
                        <thead>
                            <tr>
                                <th>{{__('Email Template Name')}}</th>
                                <th>{{__('Subject')}}</th>
                                <th>{{__('Date Created')}}</th>
                                <th>{{__('Status')}}</th>
                                <th></th>
                            </tr>
                        </thead>
                    
                        <tbody>        
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        
    </div>

@endsection

@section('scripts')
<script>
    function confirm_deletion(templateId) {
        deleteTemplateUrl = '{{route('email-templates.destroy', 'template_id' )}}';
        route = deleteTemplateUrl.replace('template_id', templateId);
        var i = 0;
        $('#confirm-delete').modal('show');
        $('.btn-ok').click(function (e) {
            if (i == 0) {
                i++;
                e.preventDefault();

                app.appAjax('DELETE', '', route).then(function (data) {
                    $('#confirm-delete').modal('hide');
                    toastr.success('Deleted successfully');
                    $('tr[data-row="' + templateId + '"]').fadeOut();
                    //location.reload();
                    return true;
                }).fail(function () {
                    var errors = $.parseJSON(error.responseText).errors
                    app.displayErrors(errors, '');
                });
            }
        }); 
    }
</script>
@endsection