@extends('back.layouts.core.helpers.table' ,
       [
           'show_buttons' => false,
           'title'        => 'Contact Search Results',
       ]
   )

@section('table-content')
    <table id="applicant_table" class="table new-table table-bordered nowrap display">
        <thead>
        <tr>
            <!-- <th class="control-column"></th> -->
            <th>{{__('Name')}}</th>
            <th>{{__('Email')}}</th>
            <th>{{__('Created At')}}</th>
            <th class="control-column"></th>

        </tr>

        </thead>

        <tbody>

        @if ($students)
            @foreach ($students as $app_student)
                <tr data-student-id="{{$app_student->id}}">

                    <td><a href="{{route('students.show' , $app_student )}}">{{$app_student->name}}</a></td>
                    <td>{{ $app_student->email }}</td>
                    <td>{{$app_student->created_at->format('Y/m/d') }}</td>

                    <td class="control-column cta-column">
                        @include('back.layouts.core.helpers.table-actions' , [
                            'buttons'=> [
                                   'delete' => [
                                        'text' => 'Delete',
                                        'icon' => 'icon-trash text-danger',
                                        'attr' => 'onclick=app.deleteElement("'.route('students.destroy' , $app_student).'","","data-student-id")',
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
    <table>
        <tr>
            <td>
                {{-- $students->links() --}}
            </td>
        </tr>
    </table>

@endsection

