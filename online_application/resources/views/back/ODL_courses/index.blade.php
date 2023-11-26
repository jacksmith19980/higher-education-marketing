@extends('back.layouts.core.helpers.table' , 
        [
            'show_buttons' => true,
            'title'        => 'Courses',
        ]
    )
@section('table-content')

        <table id="index_table" class="table table-striped table-bordered display">
            <thead>
                <tr>
                    <th class="control-column"></th>
                    <th>{{__('Courses')}}</th>
                    <th>{{__('Campuses')}}</th>
                    <th>ID</th>
                </tr>
            </thead>
            <tbody>
                @if ($courses)
                    @foreach ($courses as $course)
                        <tr>
                            <td class="control-column">
                                @include('back.layouts.core.helpers.table-actions' , [

                                    'buttons'=> [

                                           'edit' => [

                                                'text' => 'Edit',

                                                'icon' => 'icon-note',

                                                'attr' => "onclick=app.redirect('".route('courses.edit' , $course)."')",

                                                'class' => '',

                                           ],

                                           'delete' => [

                                                'text' => 'Delete',

                                                'icon' => 'icon-trash text-danger',

                                                'attr' => 'onclick=app.deleteElement("'.route('applications.destroy' , $course).'","","data-course-id")',

                                                'class' => '',

                                           ],

                                    ]

                                ])
                                </td>
                            <td><a href="{{route('courses.edit' , $course)}}">{{$course->title}}</a></td>
                            
                            <td>{{ implode(" , " , Arr::pluck($course->campuses->toArray(), 'title')) }}</td>

                            <td class="small-column">{{$course->id}}</td>
                        </tr>
                    @endforeach
                @endif      
            </tbody>
        </table>
@endsection