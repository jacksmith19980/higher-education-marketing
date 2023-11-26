
<div class="row mt-2">
    <div class="col-md-1">
        <div class="form-group" style="width: 100px">
            <select class="form-control" id="length_menu">
                <option value="10">10</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="150">150</option>
            </select>
        </div>
    </div>
    <div class="col-md-5">
        <div class="form-group custom-search-bar">
            <input type="text" class="form-control searchicon" id="search_box" placeholder="Search">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group ">
            <div class="input-group date_range">
                <input id="calendar_ranges" type="text" class="form-control calendar_ranges">
                <div class="input-group-append">
                    <span class="input-group-text">
                        <span class="ti-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="btn-group float-right">
            <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" 
            style="border: 1px solid var(--color-ablue); color: var(--color-ablue); background-color: transparent; height: 35px;">
                Actions
            </button>
            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 35px, 0px);">
                <a class="dropdown-item" href="javascript:void(0)" onclick="app.exportStudents(this)" data-title="Export Students Excel File" data-route="https://localhost/application_portal/public_html/students/download/excel" data-file="excel">
                    Export Excel
                </a>
                <a class="dropdown-item" href="javascript:void(0)" onclick="app.exportStudents(this)" data-title="Export Students CSV File" data-route="https://localhost/application_portal/public_html/students/download/excel" data-file="csv">
                    Export CSV
                </a>
            </div>
        </div>
    </div>
</div>

@php
$totalAgencyApplications = 0;
$totalSubmissions = [];
$students = $agency
    ->students()
    ->orderBy('created_at', 'desc')
    ->get();
@endphp
@if ($students->count())
    <div class="m-t-0">
        <table id="students_datatable" class="table table-striped table-bordered new-table display">
            <thead>
                <tr>
                    <th class="hidding no-sort"></th>
                    <th class="all no-sort text-left" style="width: 115px!important">
                        <div class="btn-group" style="display: inline-flex!important;">
                            <div class="btn btn-light">
                                <input style="width: 15px; height: 15px;" type="checkbox" id="select_all" name="select_all" value="" onchange="app.toggleSelectAll(this)"/>
                            </div>
                            <button type="button" class="btn btn-light dropdown-toggle dropdown-toggle-split toggle-bulk-actions"
                                    id="toggle-bulk-actions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>
                                <span class="sr-only">{{__('Toggle Dropdown')}}</span>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item bulk-action-item" href="javascript:void(0)"
                                    onclick="bulkDeleteStudents()">
                                    <i class="fas fa-trash-alt mr-1 text-danger"></i> {{__('Delete Selected')}}
                                </a>
                            </div>
                        </div>
                    </th>
                    <th class="all">{{__('NAME')}}</th>
                    <th>{{__('APPLICATION')}}</th>
                    <th>{{__('APPLICATION STATUS')}}</th>
                    <th>{{__('PROGRESS BAR')}}</th>
                    <th>{{__('AGENT NAME')}}</th>
                    <th>{{__('CREATED')}}</th>
                    <th class="no-sort all"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                    <tr data-student-id="{{$student->id}}">
                        <td>{{$student->id}}</td>
                        <td class="text-left" style="padding-left: 39px;"><input type="checkbox" name="multi-select" 
                            value="{{$student->id}}" style="width: 15px; height: 15px;" onchange="checkSelectAll()" /></td>
                        <td>
                            <a href="{{ route('students.show', $student) }}" class="link">
                                <h5>{{ $student->name }}</h5>
                            </a>
                            <p><small class="text-muted">{{ $student->email }}</small></p>
                        </td>
                        @if ($student->submissions()->count())
                            <td>
                                @foreach ($student->submissions as $studentSubmission)
                                    <p>{{ $studentSubmission->application->title }}</p>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($student->submissions as $studentSubmission)
                                    <p>{{ $studentSubmission->status }}</p>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($student->submissions as $studentSubmission)
                                <div class="progressbar-container">
                                    <div class="c-progressbar-el">
                                        <div class="c-progressbar-pcolor">
                                            <div style="z-index: 999;">
                                                <i class="mdi mdi-play"></i>
                                                <span class="c-progressbar-num">{{ $studentSubmission->steps_progress_status }}%</span>
                                            </div>
                                            <div class="progress-overlay" style="left:{{ $studentSubmission->steps_progress_status }}%;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="c-progress-details">{{__('Steps : ')}} {{$studentSubmission->properties['step']}}/{{count($studentSubmission->application->sections)}}</div></div>
                                @endforeach
                            </td>
                        @else
                            <td></td>
                            <td></td>
                            <td></td>
                        @endif
                        <td>
                            {{ $student->agent->name }}
                        </td>
                        <td>
                            <span>
                                {{ $student->created_at->diffForHumans() }}
                             </span> <br>
                            <span class="sl-date">
                               {{ $student->created_at->format('Y-m-d'); }}
                            </span>
                        </td>
                        <td class="control-column cta-column">
                            @include('back.layouts.core.helpers.table-actions' , [
                            'buttons'=> [
                            'view' => [
                                'text' => 'View',
                                'icon' => 'icon-user',
                                'attr' => 'onclick=app.redirect("'.route('students.show' , $student).'","true")',
                                'class' => '',
                            ],
                            'delete' => [
                                'text' => 'Delete',
                                'icon' => 'icon-trash text-danger',
                                'attr' => 'onclick=app.deleteElement("'.route('students.destroy' , $student).'","","data-student-id")',
                                'class' => '',
                            ],

                            ]
                            ])
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @php
            session()->put('totalAgencyApplications-' . $agency->id, $totalAgencyApplications);
            session()->put('totalSubmissions-' . $agency->id, $totalSubmissions);
        @endphp
    </div>
@else
    <div class="alert alert-warning">
        <strong>No Results Found</strong>
        <span class="d-block">there are none! {{ $agency->name }} didn't add any student yet!</span>
    </div>
@endif

@section('scripts')
<script>
    $(document).ready(function() {
        $('#students_datatable').DataTable({
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
            "order": [[6, 'desc']],
        });
        $('#length_menu').change(function(){
            var l = $('#length_menu option:selected').val();
            var t = $('#students_datatable').DataTable();
            t.page.len(l).draw();
        });
        $('#search_box').on('keyup', function () {
            var t = $('#students_datatable').DataTable();
            t.search($('#search_box').val()).draw();
        });
        $('#calendar_ranges').daterangepicker({
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
            opens: 'right',
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
        $('#calendar_ranges').on('apply.daterangepicker', function () {
            filter_by_date_range();
        });
        function filter_by_date_range()
        {
            var t = $('#students_datatable').DataTable();
            var start_date = '';
            var end_date = '';
            var dates = $('#calendar_ranges').val().split(" - ");
            start_date = dates[0];
            end_date = dates[1];
            end_date = new Date(end_date);        
            end_date.setDate(end_date.getDate() + 1);
            end_date = end_date.toString();
            end_date = formatDate(end_date);
            dates = generateDateList(start_date, end_date);
            dates = dates.toString();
            dates = '"'+ dates.replace(/,/g, '|') +'"';
            t.search(dates, true, false).draw();
        }
        function generateDateList(from, to) 
        {
            var getDate = function(date) { 
                var m = date.getMonth(), d = date.getDate();
                return date.getFullYear() + '-' + (m < 10 ? '0' + m : m) + '-' + (d < 10 ? '0' + d : d);
            }
            var fs = from.split('-'), startDate = new Date(fs[0], fs[1], fs[2]), result = [getDate(startDate)], start = startDate.getTime(), ts, end;

            if ( typeof to == 'undefined') {
                end = new Date().getTime();
            } else {
                ts = to.split('-');
                end = new Date(ts[0], ts[1], ts[2]).getTime();
            }
            while (start < end) {
                start += 86400000;
                startDate.setTime(start);
                result.push(getDate(startDate));
            }
            return result;
        }
        function formatDate(date) 
        {
            let d = new Date(date);
            let month = (d.getMonth() + 1).toString();
            let day = d.getDate().toString();
            let year = d.getFullYear();
            if (month.length < 2) {
                month = '0' + month;
            }
            if (day.length < 2) {
                day = '0' + day;
            }
            return [year, month, day].join('-');
        }
        var dp = document.getElementById("calendar_ranges");
        dp.value = "";
    });
</script>
<script>
    /**Toggle Select all for Bulk Actions**/
    app.toggleSelectAll = function (el) 
    {
        if ($(el).is(':checked')) {
            $("[name='multi-select']").prop("checked", true);
            $('.toggle-bulk-actions').prop('disabled', false);
        } else {
            $("[name='multi-select']").prop("checked", false);
            $('.toggle-bulk-actions').prop('disabled', true);
        }
    }
    app.exportStudents = function (el) 
    {
        var t = $('#students_datatable').DataTable();
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
    function bulkDeleteStudents() {
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
                    url:"{{ route('students.bulk.destroy') }}",
                    data:dataSelected,
                    dataType: "json",
                    success:function(data){
                        if (data.response == "success") {
                            $('#confirm-delete').modal('hide');
                            var chk_all = document.getElementById('select_all');
                            chk_all.checked = false;
                            dataSelected.selected.forEach(element => {
                                $('[data-student-id="' + element + '"]').fadeOut(function () {
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
</script>
@endsection