@extends('back.layouts.core.helpers.table' , ['show_buttons' => true,'title'=> 'Semesters'])

@section('table-content')

        <table id="index_table" class="table table-bordered new-table nowrap display">
            <thead>
                <tr>
                    <!-- <th class="control-column">{{__('Actions')}}</th> -->
                    <th>{{__('Semesters')}}</th>
                    <th>{{__('Start Date')}}</th>
                    <th>{{__('End Date')}}</th>
                    <th>ID</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @if ($semesters)
                    @foreach ($semesters as $semester)
                        <tr data-semester-id="{{$semester->id}}">

                            <td>{{$semester->title}}</td>

                            <td>{{$semester->start_date}}</td>
                            <td>{{$semester->end_date}}</td>

                            <td class="small-column">{{$semester->id}}</td>

                            <td class="control-column cta-column">
                                 @include('back.layouts.core.helpers.table-actions' , [
                                    'buttons'=> [
                                           'edit' => [
                                                'text' => 'Edit',
                                                'icon' => 'icon-note',
                                                'attr' => "onclick=app.redirect('".route('semesters.edit' , $semester)."')",
                                                'class' => '',
                                           ],

                                           'delete' => [
                                                'text' => 'Delete',
                                                'icon' => 'icon-trash text-danger',
                                                'attr' => 'onclick=app.deleteElement("'.route('semesters.destroy' , $semester).'","","data-semester-id")',
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