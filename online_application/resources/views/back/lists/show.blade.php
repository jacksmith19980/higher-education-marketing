@extends('back.layouts.core.helpers.table', [
    'show_buttons' => false,
    'title' => $list->name,
])

@section('table-content')
    <form action="{{ route('reports.show', $list->id) }}" method="get">
        <div class="row pb-2" id="datatableNewFilter">
            <div class="col-md-4 col-xs-12 d-flex" id="lenContainer">
            </div>
        </div>
    </form>
    <table id="list_data_table" class="table table-striped table-bordered new-table display">
        <thead>
            <tr>
                @foreach ($columns as $column)
                    <th>{{ __(ucwords(str_replace(['_', '.'], ' ', $column))) }}</th>
                @endforeach
            </tr>
        </thead>

        <tbody>
            @if (isset($data) and count($data))
                @foreach ($data as $record)
                    <tr>
                        @php
                            $columns = (array) $record;
                        @endphp
                        @foreach ($columns as $column)
                            <td>{{ $column }}</td>
                        @endforeach
                    </tr>
                @endforeach
            @endif
        </tbody>

    </table>

@endsection

@section('scripts')
    <script>
        // app.initListsTable = function() {
        //     $('#list_data_table').DataTable({
        //         processing: true,
        //         paging: true,
        //         buttons: [
        //             'copy', 'csv', 'excel', 'pdf', 'print'
        //         ],
        //         responsive: true,
        //         'columnDefs': [{
        //                 'targets': [-1],
        //                 orderable: false,
        //             },
        //             {
        //                 'targets': [0],
        //                 orderable: false,
        //             },
        //             {
        //                 responsivePriority: 1,
        //                 targets: 1
        //             },
        //             {
        //                 responsivePriority: 2,
        //                 targets: -1
        //             }
        //         ],
        //         "order": [
        //             [1, "desc"]
        //         ],
        //     });
        //     $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass(
        //         'btn btn-primary mr-1');
        // };

        // app.initListsTable();
    </script>
@endsection
