<div class="tab-pane fade show" id="promocodeusable" role="tabpanel" aria-labelledby="pills-promocodeusable-tab">
    <div class="card-body">

        <div class="row">

            <div class="col-md-10">

                <table id="applicant_table" class="table table-striped table-bordered display">

                    <thead>

                    <tr>

                        <th class="control-column"></th>

                        <th>{{__('Name')}}</th>

                        <th>{{__('Email')}}</th>

                        <th>{{__('Applications')}}</th>

                        <th>{{__('Payment Status')}}</th>

                    </tr>

                    </thead>

                    <tbody>


                    @if ($users)
                        @foreach ($users as $app_student)
                            <tr data-student-id="{{$app_student->id}}">

                                <td class="control-column">
                                    @include('back.layouts.core.helpers.table-actions' , [
                                        'buttons'=> [
                                               'view' => [
                                                    'text' => 'View',
                                                    'icon' => 'icon-eye',
                                                    'attr' => "onclick=app.redirect('".route('students.show' , $app_student)."')",
                                                    'class' => '',
                                               ],

                                               'delete' => [
                                                    'text' => 'Delete',
                                                    'icon' => 'icon-trash text-danger',
                                                    'attr' => 'onclick=app.deleteElement("'.route('students.destroy' , $app_student).'","","data-student-id")',
                                                    'class' => '',
                                               ],
                                        ]
                                    ])
                                </td>


                                <td><a href="{{route('students.show' , $app_student )}}">{{$app_student->name}}</a></td>
                                <td><a href="#">{{$app_student->email}}</a></td>
                                <td> @include('back.students.submitted-applications') </td>
                                <td class="small-column">

                                    @foreach ($app_student->invoices()->get() as $invoice)

                                        @php
                                            $status = $invoice->status()->orderBy('created_at' , 'desc')->first();
                                        @endphp

                                        @if ($status->status == 'Invoice Created')
                                            @php
                                                $class = "badge-warning"
                                            @endphp
                                        @else
                                            @php
                                                $class = "badge-success"
                                            @endphp
                                        @endif

                                        <span class="badge {{$class}}" data-toggle="tooltip" data-placement="top" title="{{ $status->created_at }}"><a style="color:#FFF" href="{{route('invoice.pdf.action' , ['invoice' => $invoice , 'action' => 'download'] )}}">{{ $status->status }}</a></span>
                                    @endforeach

                                </td>

                            </tr>

                        @endforeach

                    @endif

                    </tbody>

                </table>

                <table>
                    <tr>
                        <td>
                            {{ $users->links() }}
                        </td>
                    </tr>
                </table>

                <div class="col-md-12">
                    {{--                        <button class="btn btn-success float-right">Save</button>--}}
                    <a class="btn btn-success float-right" href="{{route('promocodes.index')}}">{{__('Back')}}</a>
                </div>
            </div>

        </div>

    </div>
</div>