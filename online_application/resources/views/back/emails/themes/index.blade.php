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
                        <h4 class="page-title">{{__('Email Themes')}}</h4>
                    </div>

                    <!--
                    <div class="float-right btn-group">
                        <a href="#" class="btn btn-secondary add_new_btn text-light">{{__('Import New Theme')}}</a>
                    </div>
                    -->

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
                    <table id="email_themes_table" class="table responsiveDataTable display new-table nowrap w-100 responsive dataTable">
                        <thead>
                            <tr>
                                <th>{{__('Email Theme Name')}}</th>
                                <th>{{__('Thumb Image')}}</th>
                                <th>{{__('Date Created')}}</th>
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
    $('#email_themes_table').DataTable({
        processing: true,
        responsive: true,
        serverSide: true,
        ajax: "{{ route('email.themes.list') }}",
        order: [[ 2, 'desc' ]],
        columnDefs: [
            { 'orderable': false, 'targets': [3] }
        ],
        columns: [
            {
                data: 'name',
                name: 'name'
            },{
                data: 'thumb_image_name',
                name: 'thumb_image_name'
            },{
                data: 'created_at',
                name: 'created_at',
                render: function name(data, type, row) {
                    let inputDateStr = row.created_at;
                    let inputDate = new Date(inputDateStr);
                    return inputDate.toLocaleString('en-US', {year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit'});
                }
            },{
                data: 'id',
                name: 'id',
                render: function (data, type, row) {
                    html_data = '<div class="btn-group more-optn-group" style="float: right">' +
                        '<button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split ti-more-alt flat-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>' +
                        '<div class="dropdown-menu">';
                    html_data += '<a href="{{ route("view.email.themes", "themeId") }}" style="font-weight: bold;" target="_blank"' +
                    'class="dropdown-item" data-toggle="tooltip" data-placement="top" data-original-title="View"' +
                    '<span class="pl-2 icon-text"><i class="fas fa-eye"></i> View</span></a>';
                    html_data += '<a href="{{ route("email-themes.edit", "themeId") }}" style="font-weight: bold;"' +
                    'class="dropdown-item" data-toggle="tooltip" data-placement="top" data-original-title="Edit"' +
                    '<span class="pl-2 icon-text"><i class="fas fa-pencil-alt"></i> Edit</span></a>';
                    html_data += '<a href="javascript:void(0)" style="font-weight: bold;"' +
                    'onclick="confirm_theme_deletion(themeId)" ' +
                    'class="dropdown-item" data-toggle="tooltip" data-placement="top" data-original-title="Delete"' +
                    '<span class="pl-2 icon-text"><i class="fas fa-trash-alt"></i> Delete</span></a>';
                    html_data += '</div></div>';
                    return html_data.replaceAll('themeId', row.id);
                }
            }
        ],
        rowCallback: function(row, data, index) {
            $(row).attr("data-row", data.id);
        },
        initComplete: function() {
            var firstColTableFilter = $('#datatableNewFilter #lenContainer');
            var searchConatiner = $('#email_themes_table_wrapper #email_themes_table_filter');
            var sInput = $("#email_themes_table_filter").find('input');
            var sLabel = $("#email_themes_table_filter").find('label');
            sLabel.replaceWith(sInput);
            var iPaginate = $('#email_themes_table_wrapper .dataTables_length select');
            $(iPaginate).detach().appendTo(firstColTableFilter);
            $(searchConatiner).detach().appendTo(firstColTableFilter);
            $('#email_themes_table_wrapper .dataTables_length').hide();
            if (window.location.hash) {
                window.location.href = window.location.hash;
            }
        }
    });

    function confirm_theme_deletion(themeId) {
        deleteTemplateUrl = '{{route('email-themes.destroy', 'theme_id' )}}';
        route = deleteTemplateUrl.replace('theme_id', themeId);
        var i = 0;
        $('#confirm-delete').modal('show');
        $('.btn-ok').click(function (e) {
            if (i == 0) {
                i++;
                e.preventDefault();

                app.appAjax('DELETE', '', route).then(function (data) {
                    $('#confirm-delete').modal('hide');
                    toastr.success('Deleted successfully');
                    $('tr[data-row="' + themeId + '"]').fadeOut();
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
