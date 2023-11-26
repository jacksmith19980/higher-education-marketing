@extends('back.layouts.core.helpers.table' ,
        [
            'show_buttons' => true,
            'title'        => 'Plans',
        ]
    )
@section('table-content')
    <table id="index_table" class="table table-striped table-bordered display"
        data-order='[[ 1, "desc" ]]'>
        <thead>
            <tr>
                <th>{{__('Plan Name')}}</th>
                <th>{{__('Features')}}</th>
                <th>{{__('Active')}}</th>
                <th>{{__('ID')}}</th>
                <th class="control-column"></th>
            </tr>
        </thead>

        <tbody>
            @if ($plans)
                @foreach ($plans as $plan)
                    <tr data-plans-id="{{$plan->id}}">
                        <td>{{$plan->title}}</td>
                        <td>{{implode(", ",$plan->features)}}</td>
                        <td class="small-column">{{$plan->is_active}}</td>

                        <td>{{$plan->id}}</td>
                        <td class="control-column">
                            @include('back.layouts.core.helpers.table-actions' , [
                                'buttons'=> [
                                        'edit' => [
                                            'text' => 'Edit Plan',
                                            'icon' => 'icon-note',
                                            'attr' => "onclick=app.redirect('".route('plans.edit' , $plan)."')",
                                            'class' => '',
                                        ],

                                        'delete' => [
                                            'text' => 'Delete Plan',
                                            'icon' => 'icon-trash text-danger',
                                            'attr' => 'onclick=app.deleteElement("'.route('plans.destroy' , $plan).'","","data-plans-id")',
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
