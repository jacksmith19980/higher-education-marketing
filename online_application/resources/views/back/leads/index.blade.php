@extends('back.layouts.core.helpers.table' , [
    'show_buttons' => $permissions['create|student'],
    'title'        => 'Leads',
])

@section('table-content')
    <div class="row pb-2" id="datatableNewFilter">
        <div class="col-md-4 col-xs-12 d-flex" id="lenContainer">
        </div>
    </div>
    <table id="leads_table" data-route="{{route('leads.list')}}" class="table table-striped table-bordered new-table display">
        <thead>
        <tr>
            <th>
                @include('back.layouts.core.helpers.bulk-actions' ,
                [
                    "buttons" => [
                        'delete' => [
                            'action'     => "onclick=app.bulkDelete('".route('students.bulk.destroy')."')",
                            'icon'       => "fas fa-trash-alt text-dange",
                            'title'      => __("Delete Students"),
                            'allowed'    => PermissionHelpers::checkActionPermission('student' , 'delete')
                        ],
                    ]
                ])
            </th>

            <th>{{__('Id')}}</th>

            <th>{{__('Name')}}</th>

            <th>{{__('Email')}}</th>

            <th>{{__('Created')}}</th>

            <th></th>
        </tr>
        </thead>
    </table>
@endsection