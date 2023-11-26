@extends('back.layouts.core.helpers.table' ,
        [
            'show_buttons' => $permissions['create|program'],
            'title'        => 'Programs',
        ]
    )
@section('table-content')

        <table id="index_table" class="table table-striped table-bordered new-table display">
            <thead>
                <tr>
                    <!-- <th class="control-column">{{__('Actions')}}</th> -->
                    <th>{{__('Programs')}}</th>
                    <th>{{__('Program Type')}}</th>
                    <th>{{__('Program Code')}}</th>
                    <th>{{__('Campuses')}}</th>
                    <th>ID</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @if ($programs)
                    @foreach ($programs as $program)
                        <tr data-program-id="{{$program->id}}">

                            <td>{{$program->title}}</td>

                           <td>{{ $program->program_type }}</td>

                           <td>{{ $program->slug }}</td>

                            <td>{{ implode(" , " , Arr::pluck($program->campuses->toArray(), 'title')) }}</td>

                            <td class="small-column">{{$program->id}}</td>

                            <td class="control-column cta-column">
                            @include('back.layouts.core.helpers.table-actions-permissions' , [
                                    'buttons'=> [
                                        'edit' => [
                                            'text' => 'Edit',
                                            'icon' => 'icon-note',
                                            'attr' => "onclick=app.redirect('".route('programs.edit' , $program)."')",
                                            'class' => '',
                                            'show'  => PermissionHelpers::checkActionPermission('program', 'edit', $program)
                                        ],
                                        'delete' => [
                                            'text' => 'Delete',
                                            'icon' => 'icon-trash text-danger',
                                            'attr' => 'onclick=app.deleteElement("'.route('programs.destroy' , $program).'","","data-program-id")',
                                            'class' => '',
                                            'show'  => PermissionHelpers::checkActionPermission('program', 'delete', $program)
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
