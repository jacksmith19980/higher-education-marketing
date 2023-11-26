@extends('back.layouts.core.helpers.table' , ['show_buttons' => true,'title'=> 'Instructors'])

@section('table-content')
    <a class="btn btn-light  add_new_btn" style="float: right;margin-right: 20px;" target="_blank" href="{{route('school.instructor.login', $school)}}">{{__('Instructors Login')}}</a>

    <table id="index_table" class="table table-striped table-bordered new-table display">

        <thead>

        <tr>
            <!-- <th class="control-column">{{__('Actions')}}</th> -->
            <th>{{__('Name')}}</th>
            <!-- <th>{{__('Email')}}</th> -->
            <th>{{__('Phone')}}</th>
            <th>{{__('Active')}}</th>
            <th>ID</th>
            <th></th>
        </tr>

        </thead>

        <tbody>

        @if ($instructors)

            @foreach ($instructors as $instructor)

                <tr data-instructor-id="{{$instructor->id}}">

{{--                    <td><a href="{{route('instructors.edit' , $instructor)}}">{{$instructor->name}}</a></td>--}}
                    <td>{{$instructor->name}}<br/><small>{{$instructor->email}}</small></td>

                    <!-- <td>{{$instructor->email}}</td> -->

                    <td>{{$instructor->phone}}</td>

                    <td class="small-column">
                        @if ($instructor->is_active)
                            <i class="fa fa-circle text-success" data-toggle="tooltip" data-placement="top" title="" data-original-title="Active"></i>
                        @else
                            <i class="fa fa-circle text-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="Inactive"></i>
                        @endif
                    </td>

                    <td class="small-column">{{$instructor->id}}</td>

                    <td class="control-column cta-column">
                        @include('back.layouts.core.helpers.table-actions' , [
                        'buttons'=> [
                                'user' => [
                                    'text' => 'View Instructor',
                                    'icon' => 'icon-user',
                                    'attr' => "onclick=app.redirect('".route('instructors.show', $instructor)."')",
                                    'class' => '',
                                ],

                                'edit' => [
                                    'text' => 'Edit Instructor',
                                    'icon' => 'icon-note',
                                    'attr' => "onclick=app.redirect('".route('instructors.edit' , $instructor)."')",
                                    'class' => '',
                                ],
                                'impersonate' => [
                                    'text' => 'Impersonate Instructor',
                                    'icon' => 'icon-people',
                                    'attr' => "onclick=app.redirect('".route('instructors.impersonate' , $instructor)."',true)",
                                    'class' => '',
                                ],

                                'delete' => [
                                    'text' => 'Delete Instructor',
                                    'icon' => 'icon-trash text-danger',
                                    'attr' => 'onclick=app.deleteElement("'.route('instructors.destroy' , $instructor).'","","data-instructor-id")',
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
