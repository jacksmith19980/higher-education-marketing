@extends('back.layouts.core.helpers.table' , [
    'show_buttons' => true,
    'title'=> 'Cohorts'
])

@section('table-content')

    <table id="index_table" class="table table-striped table-bordered new-table display">

        <thead>
        <tr>
            <!-- <th class="control-column">{{__('Actions')}}</th> -->
            <th>{{__('Active')}}</th>
            <th>{{__('Cohort')}}</th>
            <th>{{__('Program')}}</th>
            <th>{{__('Students')}}</th>
            <th>{{__('Start Date')}}</th>
            <th>{{__('End ')}}</th>
            <th>{{__('Schedule')}}</th>
            <th>{{__('ID')}}</th>
            <th></th>
        </tr>
        </thead>

        <tbody>
        @if ($groups)
            @foreach ($groups as $group)
                <tr data-group-id="{{$group->id}}">
                    <td class="small-column">
                        <a href="javascript:void(0)" onclick="app.toggleStatus(this)" data-prop="is_active" data-id="{{$group->id}}" data-model="group" data-controller="group">
                            @if ($group->is_active)
                                    <i class="fa fa-circle text-success" data-toggle="tooltip" data-placement="top" title=""
                                data-original-title="Active"></i>
                            @else
                                <i class="fa fa-circle text-danger" data-toggle="tooltip" data-placement="top" title=""
                                data-original-title="Inactive"></i>
                            @endif
                        </a>
                    </td>
                    <td>{{$group->title}}</td>
                    <td>{{isset($group->program) ? $group->program->title : ''}}</td>
                    <td>{{$group->students()->count()}}</td>
                    <td>{{$group->start_date}}</td>
                    <td>{{$group->end_date}}</td>


                    <td>
                        @foreach($group->schedules as $schedule)
                            <span class="badge badge-secondary">                    {{$schedule->label}}
                            </span>
                        @endforeach

                    </td>


                    <td class="small-column">{{$group->id}}</td>

                    <td class="control-column cta-column">
                        @include('back.layouts.core.helpers.table-actions' , [
                           'buttons'=> [
                                  'cohort' => [
                                       'text' => 'View Cohort',
                                       'icon' => 'icon-eye',
                                       'attr' => "onclick=app.redirect('".route('groups.show' , $group)."','',this)",
                                       'class' => '',
                                  ],
                                  'edit' => [
                                       'text' => 'Edit Cohort',
                                       'icon' => 'icon-note',
                                       'attr' => "onclick=app.redirect('".route('groups.edit' , $group)."')",
                                       'class' => '',
                                  ],

                                  'delete' => [
                                       'text' => 'Delete Cohort',
                                       'icon' => 'icon-trash text-danger',
                                       'attr' => 'onclick=app.deleteElement("'.route('groups.destroy' , $group).'","","data-group-id")',
                                       'class' => '',
                                  ],
                           ]
                       ])

                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
@endsection
