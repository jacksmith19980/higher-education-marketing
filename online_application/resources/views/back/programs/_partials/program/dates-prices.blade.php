<div class="fluid-container">
    <div class="row p-b-15">
    <div class="col-12">
        <div class="float-right btn-group mb-3" role="group">
            <a href="javascript:void(0)"
            class="btn btn-secondary add_new_btn text-light"
            onclick="app.addDate(this)"
            data-date-type="specific-dates"
            data-entity="{{$program->id}}"
            data-entity-type="program"
            data-title="{{__('Add Date')}}"
            >{{__('Add Date')}}</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 datesTableContainer">
        @php
            $dates = $program->dates()->get();
        @endphp
        @if ($dates->count())
            @include('back.courses._partials.course.dates-prices-table' , [
                'dates' => $dates,
                'entity'=> $program
            ])
        @else
            @include('back.students._partials.student-no-results')
        @endif
    </div>
</div>

</div>
<script>
    $('#course_dates_prices').DataTable({
        "searching": false,
        "lengthChange": false,
        "columnDefs": [
            {
                "targets": 4,
                "orderable": false
            }
        ]
    });
</script>
