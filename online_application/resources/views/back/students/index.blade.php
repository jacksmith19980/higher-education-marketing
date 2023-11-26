 @extends('back.layouts.core.helpers.table' ,
        [
            'show_buttons' => $permissions['create|contact'],
            'title'        => 'Applicants',
        ]
    )

@section('table-content')
    <table id="applicant_table" class="table new-table table-bordered nowrap display">

        <thead>

        <tr>
            <th>{{__('Name')}}</th>
            <th>{{__('Email')}}</th>
            <th>{{__('Applications')}}</th>
            <th>{{__('Campus')}}</th>
            <th>{{__('Payment Status')}}</th>
            <th>{{__('Agent')}}</th>
            <th>{{__('Created At')}}</th>
            <th class="control-column"></th>

        </tr>

        </thead>

        <tbody>

        @if ($students)
            @foreach ($students as $app_student)
                @php
                    $campuses = $app_student->campuses->pluck('title')->toArray();
                @endphp
                <tr data-student-id="{{$app_student->id}}">

                    <td><a href="{{route('students.show' , $app_student )}}">{{$app_student->name}}</a></td>
                    <td>{{ $app_student->email }}</td>
                    <td> @include('back.students.submitted-applications') </td>
                    <td> {{ implode(", " , $campuses) }} </td>
                    <td class="small-column">

                        @foreach ($app_student->invoices()->get() as $invoice)

                            @php
                                $status = $invoice->status()->orderBy('created_at' , 'desc')->first();
                            @endphp

                            @if (isset($status->status) && ($status->status == 'Invoice Created'))
                                @php
                                    $class = "badge-warning"
                                @endphp
                                <span class="badge {{$class}}" data-toggle="tooltip" data-placement="top" title="{{ $status->created_at }}"><a style="color:#FFF" href="{{route('invoice.pdf.action' , ['invoice' => $invoice , 'action' => 'download'] )}}">{{ $status->status }}</a></span>
                            @else
                                @php
                                    $class = "badge-success"
                                @endphp
                            @endif
                        @endforeach

                    </td>

                    <td>
                        @if (isset($app_student->agent))

                            @if ($agency =  $app_student->agent->agency()->first())
                                <p>{{ optional($app_student->agent)->name }}
                                    <a href="{{route('agencies.show' , $agency)}}" class="d-block">
                                        <small>{{ $agency->name }}</small>
                                    </a>
                                </p>
                            @endif

                        @endif
                    </td>

                    <td>{{$app_student->created_at}}</td>

                    @php
                        $buttons['buttons']['view'] = [
                            'text' => 'View',
                            'icon' => 'icon-eye',
                            'attr' => "onclick=app.redirect('".route('students.show' , $app_student)."')",
                            'class' => '',
                            'show'  => PermissionHelpers::checkActionPermission('contact', 'view', $app_student),
                        ];
                        $buttons['buttons']['stage'] = [
                            'text' => 'Change Stage',
                            'icon' => 'icon-shuffle',
                            'attr' => 'onclick=app.updateStudentStage("'.route('students.stage.update' , $app_student).'","","data-student-id")',
                            'class' => '',
                            'show'  => PermissionHelpers::checkActionPermission('contact', 'edit', $app_student),
                        ];
                        $buttons['buttons']['delete'] = [
                            'text' => 'Delete',
                            'icon' => 'icon-trash text-danger',
                            'attr' => 'onclick=app.deleteElement("'.route('students.destroy' , $app_student).'","","data-student-id")',
                            'class' => '',
                            'show'  => PermissionHelpers::checkActionPermission('contact', 'delete', $app_student),
                        ];

                    @endphp

                    <td class="control-column cta-column">
                        @include('back.layouts.core.helpers.table-actions-permissions' ,$buttons)
                    </td>

                </tr>

            @endforeach

        @endif

        {{-- <tr>
            <td colspan="6"></td>
            <td>

                <div class="btn-group">

                    <button type="button" class="btn btn-primary btn-small dropdown-toggle m-a-10 d-block ma-20" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Export Application
                    </button>

                    <div class="dropdown-menu">

                        @foreach ($applications as $application)

                           <a class="dropdown-item" target="_blank"
                           href="{{ route('export.application' , ['application' => $application , 'format'=>'excel'])}}"><span class="icon-cloud-download"></span>{{$application->title}}</a>

                        @endforeach

                    </div>

                </div>
            </td>
        </tr>
--}}
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
