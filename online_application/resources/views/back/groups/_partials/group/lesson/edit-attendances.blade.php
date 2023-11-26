@include('back.groups._partials.group.lesson.lesson-info')

<div style="padding-top: 3%">

<form method="POST" action="{{ route('update.lesson.attendances', ['lesson' => $lesson->id]) }}" 
    enctype="multipart/form-data">
  @csrf
  @if ( $attendances_count = $lesson->attendances->count() )
      <div class="form-group">
          <table id="lesson_edit_attendances" class="table responsiveDataTable display new-table nowrap w-100 responsive dataTable">
              <thead>
                  <tr>
                      <th></th>
                      <th class="all no-sort text-left" style="width: 115px!important">
                          <div class="btn-group" style="display: inline-flex!important;">
                              <div class="btn btn-light">
                                  <input style="width: 15px; height: 15px;" type="checkbox" id="select_all_attendances" name="select_all" value="" onchange="app.toggleSelectAll(this)"/>
                              </div>
                              <button type="button" class="btn btn-light dropdown-toggle dropdown-toggle-split toggle-bulk-actions"
                                  id="toggle-bulk-actions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>
                                  <span class="sr-only">Toggle Dropdown</span>
                              </button>
                              <div class="dropdown-menu">
                                  <a class="dropdown-item bulk-action-item" href="javascript:void(0)"
                                      onclick="app.bulkEdit('{{route('attendances.bulk.edit')}}' , 'Edit Attendances')">
                                      <i class="fas fa-pencil-alt mr-1"></i> {{__('Edit Selected')}}
                                  </a>
                              </div>
                          </div>                    
                      </th>
                      <th>{{__('Name')}}</th>
                      <th>{{__('Status')}}</th>
                  </tr>
              </thead>
              @foreach($lesson->attendances as $attendance)
                  <tr>
                      <td></td>
                      <td class="text-left" style="padding-left: 39px;">
                          <input style="width: 15px; height: 15px;" type="checkbox" name="multi-select" value="{{$attendance->id}}" onchange="checkSelectAll()">
                      </td>
                      <td width="50%">{{ $attendance->student ? $attendance->student->first_name .' '. $attendance->student->last_name : '' }}</td>
                      <td width="35%">
                          <select class="form-control custom-select select2" name="{{$attendance->id}}">
                              @php
                                  $statuses = ["présent - classe", "absent", "retard", "présent - en ligne", "withdrawn"];
                              @endphp
                              @foreach ( $statuses as $status)
                                  <option value="{{$status}}" @if($attendance->status == $status) selected  @endif>{{$status}}</option>
                              @endforeach
                          </select>
                      </td>
                  </tr>
              @endforeach
          </table>
          <div style="text-align:right;">
              <button style="margin-top: 30px; margin-right: 20px; padding: 10px 25px 10px 25px;" type="submit" class="btn btn-primary">Save</button>
          </div>
      </div>
  @else
      <div class="alert alert-warning">
          <strong>{{__('No Results Found')}}</strong>
          <span class="d-block">{{__('there are none data to show!')}}</span>
      </div>        
  @endif
</form>

<script>
    $('#lesson_edit_attendances').DataTable({
        "searching": false,
        "lengthChange": false,
        "columnDefs": [{
            "targets": 0,
            "visible": false,
            "orderable": false
        },{
            "targets": 1,
            "orderable": false
        }]
    });
</script>