@extends('back.layouts.core.helpers.table' , [
    'show_buttons' => false,
    'title'        => $report->name,
])

@section('table-content')
    <form action="{{route('reports.show', $report->id)}}" method="get">
    <div class="row pb-2" id="datatableNewFilter">
        <div class="col-md-4 col-xs-12 d-flex" id="lenContainer">
        </div>
        <div class="col-lg-6 col-md-5 col-12 d-flex" id="calContainer">
            <div class="input-group">
                <input id="reportCalendarRanges" type="text" class="form-control" value="">
                <div class="input-group-append">
                    <span class="input-group-text">
                        <span class="ti-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-12 d-flex justify-content-end" id="filterContainer">
            <div class="">
                @include('back.layouts.core.forms.select',
                [
                    'name'          => 'report-date',
                    'label'         => '',
                    'attr'          => 'id=report-date',
                    'required'      => false,
                    'placeholder'   => 'Select Date',
                    'class'         => '' ,
                    'value'         => $selectedReportDate,
                    'data'          => $reportDates,
                ])
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    {{__('Actions')}}
                </button>
                <div class="dropdown-menu">
                    @if(PermissionHelpers::checkActionPermission('report', 'edit', $report))
                    <a class="dropdown-item" href="javascript:void(0)"
                        onclick="app.redirect('{{ route('reports.edit', $report->id) }}')">
                        <i class="icon-note"></i> <span class="icon-text">{{__('Edit')}}</span>
                    </a>
                    @endif
                    <a class="dropdown-item" href="javascript:void(0)"
                        onclick="app.exportReport(this)"
                            data-title="{{__('Export Excel File')}}"
                            data-route={{route('reports.download.excel', $report->id)}}
                            data-file={{'xlsx'}}>
                                    {{__('Export Excel')}}
                    </a>
                    <a class="dropdown-item" href="javascript:void(0)"
                        onclick="app.exportReport(this)"
                            data-title="{{__('Export CSV File')}}"
                            data-route={{route('reports.download.excel', $report->id)}}
                            data-file={{'csv'}}>
                                    {{__('Export CSV')}}
                    </a>
                    @if(PermissionHelpers::checkActionPermission('report', 'delete', $report))
                    <a class="dropdown-item " href="javascript:void(0)" onclick="app.deleteElement('{{ route('reports.destroy', $report) }}','','data-report-id', '{{ route('reports.index') }}')">
                        <i class="icon-trash text-danger"></i> <span class="icon-text">{{__('Delete')}}</span>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </form>
    <table id="reports_table" class="table table-striped table-bordered new-table display">
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
                        @foreach ($columns as $column)
                        <td>{{ $record[$column] }}</td>
                        @endforeach
                    </tr>
                @endforeach
            @endif
        </tbody>

    </table>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {

        $('#reportCalendarRanges').daterangepicker({
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
                'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]

            },
            alwaysShowCalendars: true,
            opens: 'left',
            startDate: moment(),
            endDate: moment().subtract(29, 'days'),
            locale: {
                format: 'YYYY-MM-DD'
            }
        });

        $("#reportCalendarRanges").blur(function(e) { $(this).val('') });

        @if(!(isset($startDate) and isset($endDate)))
        $('#reportCalendarRanges').val('')
        @endif

        $('#reportCalendarRanges').on('apply.daterangepicker', function () {
            app.getReports("{{ route('reports.show', $report->id)}}");
        });
    
    })
</script>
@endsection