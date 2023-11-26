@extends('back.layouts.core.helpers.table' , ['show_buttons' => true,'title'=> 'Campuses'])

@section('table-content')

<table id="index_table" class="table table-bordered new-table nowrap display">
    <thead>
        <tr>
            <!-- <th class="control-column">{{__('Actions')}}</th> -->
            <th>{{__('Campuses')}}</th>
            <th>{{__('Code\Slug')}}</th>
            <th>ID</th>
            <th></th>
        </tr>
    </thead>

    <tbody>
        @if ($campuses)
            @foreach ($campuses as $campus)
                <tr data-campus-id="{{$campus->id}}">

                    <td>{{$campus->title}}</td>
                    <td>{{$campus->slug}}</td>

                    <td class="small-column">{{$campus->id}}</td>

                    <td class="control-column cta-column">
                            @include('back.layouts.core.helpers.table-actions' , [
                            'buttons'=> [
                                    'edit' => [
                                        'text' => 'Edit',
                                        'icon' => 'icon-note',
                                        'attr' => "onclick=app.redirect('".route('campuses.edit' , $campus)."')",
                                        'class' => '',
                                    ],

                                    'calendar' => [
                                        'text' => 'Calendar for ' . $campus->title,
                                        'icon' => 'icon-calender',
                                        'attr' => "onclick=app.redirect('".route('campuses.calendar' , $campus)."')",
                                        'class' => '',
                                    ],

                                    'delete' => [
                                        'text' => 'Delete',
                                        'icon' => 'icon-trash text-danger',
                                        'attr' => 'onclick=app.deleteElement("'.route('campuses.destroy' , $campus).'","","data-campus-id")',
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
