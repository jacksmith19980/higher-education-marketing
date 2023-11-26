<form method="POST" action="{{ route($route , $student ) }}" class="validation-wizard text_input_field">
    @csrf
    <input type="hidden" name="_method" value="PUT">

    <div class="accordion-head bg-info text-white">{{$student->name}} Cohorts</div>

    <div class="accordion-content accordion-active">

        <div class="row">
            <div class="col-md-10">
                @include('back.layouts.core.forms.select',
                [
                    'name'      => 'cohort',
                    'label'     => 'Cohort' ,
                    'class'     => 'ajax-form-field' ,
                    'required'  => true,
                    'attr'      => '',
                    'placeholder' => 'Cohorts',
                    'data'      => \App\Helpers\School\GroupHelpers::getGroupsInArrayOnlyTitleId(),
                    'value'     => ''
                ])
            </div>

            <div class="col-md-1 action_wrapper">
                <div class="form-group action_button">
                    <label for="">{{__('Add')}}</label>

                    <button class="btn btn-success" type="button"
                            onclick="app.addStudentToCohort();">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="groups_fields_wrapper">

            <div class="table-responsive">
                <table id="index_table" class="table table-striped table-bordered display">
                    <thead>
                    <tr>
                        <th class="control-column">{{__('Actions')}}</th>
                        <th>{{__('Cohort')}}</th>
                        <th>{{__('Program')}}</th>
                        <th>{{__('Start Date')}}</th>
                        <th>{{__('End ')}}</th>
                    </tr>
                    </thead>
                    <tbody id="group-body">
                        @if (count($student->groups) > 0)
                            @foreach ($student->groups as $group)
                                <tr data-group-id="{{$group->id}}">
                                    <td class="control-column">
                                        <a href="javascript:void(0)"
                                           onclick="app.deleteStudentFromGroup('{{ route('students.destroy.cohort' , ['student' => $student, 'group' => $group] ) }}')"
                                           class="btn btn-circle
                                                btn-light text-muted" data-toggle="tooltip" data-placement="top"
                                           data-original-title="Delete">
                                            <i class="icon-trash text-danger"></i>
                                        </a>
                                    </td>
                                    <td><span class="badge badge-secondary">{{$group->title}}</span></td>
                                    <td>{!! isset($group->program) ? '<span class="badge badge-secondary">' . $group->program->title . '</span>' : '' !!}</td>
                                    <td>{{$group->start_date}}</td>
                                    <td>{{$group->end_date}}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</form>
