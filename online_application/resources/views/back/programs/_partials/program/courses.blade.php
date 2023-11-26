<div class="row p-b-15">
    <div class="col-12">
        <div class="float-right" role="group">
            <div data-name="add_new_application_dropdown" class="btn-group" role="group">
                <button type="button" class="btn btn-secondary">
                    {{__("Add Course")}}
                </button>
            </div>
        </div>
    </div>
</div>

@if ($program->courses->count())
<table id="course_programs" class="table responsiveDataTable display new-table nowrap w-100 responsive dataTable">
    <thead>
        <tr>
            <th>{{__('Course')}}</th>
            <th>{{__('Code')}}</th>
            <th>{{__('Campus')}}</th>
            <th></th>
        </tr>
    </thead>

    <tbody>
            @foreach ($program->courses as $course)
                @include('back.programs._partials.program.course' , [
                    'course' => $course
                ])
            @endforeach
    </tbody>
</table>
@else
    @include('back.students._partials.student-no-results')
@endif
<script>
    $('#course_programs').DataTable({
        "searching": false,
        "lengthChange": false,
        "columnDefs": [
            {
                "targets": 3,
                "orderable": false
            }
        ]
    });
</script>
