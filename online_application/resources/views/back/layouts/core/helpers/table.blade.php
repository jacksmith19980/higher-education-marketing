@extends('back.layouts.default')
@section('styles')
<style>
@media (max-width: 1300px) {
    .custom-search-bar {
        padding-left: 48px;
    }
}
@media (max-width: 770px) {
    .custom-search-bar {
        padding-left: 12px;
        padding-right: 24px;
    }
}
</style>
@endsection

@section('content')

    <div class="row justify-content-center">
        @include('back.layouts.core.helpers.page-actions')
        <div class="col-12">
            <div class="card hasShadow" style="box-shadow: rgb(15 15 15 / 15%) 0px 10px 10px 1px;">
                <div class="card-body" id="table-card">
                    <div class="table-responsive" style="padding:15px;">
                        @yield('table-content')
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
<script>
    function checkSelectAll() {
        var chk_all = document.getElementById('select_all');
        var bulk_actions = document.getElementById('toggle-bulk-actions');
        var isChecked =  $("[name='multi-select']").is(':checked')
        if (isChecked) {
            chk_all.checked = true;
            bulk_actions.disabled = false;
        } else {
            chk_all.checked = false;
            bulk_actions.disabled = true;
        }
    }
    function bulkDeleteAgencies() {
        var i = 0;
        $('#confirm-delete').modal('show');
        $('.btn-ok').click(function (e) {
            if (i == 0) {
                i++;
                e.preventDefault();
                var selected = [];
                $("input:checkbox[name=multi-select]:checked").each(function () {
                    selected.push($(this).val());
                });
                var dataSelected = {
                    selected: selected
                }
                $.ajaxSetup({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
                    '_token': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    type:'POST',
                    url:"{{ route('agencies.bulk.destroy') }}",
                    data:dataSelected,
                    dataType: "json",
                    success:function(data){
                        if (data.response == "success") {
                            $('#confirm-delete').modal('hide');
                            var chk_all = document.getElementById('select_all');
                            chk_all.checked = false;
                            dataSelected.selected.forEach(element => {
                                $('[data-agency-id="' + element + '"]').fadeOut(function () {
                                    $(this).remove();
                                });
                            });
                            toastr.success(data.extra.message);
                        } else {
                            toastr.error('Please, Try again later', 'Somthing went wrong!');
                            $('#confirm-delete').modal('hide');
                        }
                    },
                    error: function(){
                        $('#confirm-delete').modal('hide');
                        toastr.error('Please, Try again later', 'Somthing went wrong!');
                    }
                });
            }
        });
    }
    function exportAgencies(el)
    {
        var t = $('#agencies_index_table').DataTable();
        var file = $(el).data('file');
        var route = $(el).data('route');
        var title = $(el).data('title');
        var data = [];
        t.column(0,  { search:'applied' } ).data().each(function(value, index) {
            data.push(value);
        });
        route += '?file=' + file;
        route += '&data=' + data;
        app.redirect(route);
    }
</script>
<script>
    $(document).ready(function() {
        $('#agencies_index_table').DataTable({
            "columnDefs": [
                {
                    "targets": 'no-sort',
                    "orderable": false,
                    "searchable": true,
                },
                {
                    "targets": "hidding",
                    "visible": false,
                    "orderable": false,
                    "searchable": false,
                }
            ],
            "language": {
                "searchPlaceholder": "Search records",
                "search": "",
                "sLengthMenu": ""
            },
            "lengthChange": true,
            "lengthMenu": [[10, 50, 100, 150], [10, 50, 100, 150]],
            "searching": true,
            "sDom": 'lrtip',
            "responsive": {"details": false},
            "order": [[5, 'desc']],
        });
        $('#length_menu').change(function(){
            var l = $('#length_menu option:selected').val();
            var t = $('#agencies_index_table').DataTable();
            t.page.len(l).draw();
        });
        $('#search_box').on('keyup', function () {
            var t = $('#agencies_index_table').DataTable();
            t.search($('#search_box').val()).draw();
        });
    });
</script>
@endsection
