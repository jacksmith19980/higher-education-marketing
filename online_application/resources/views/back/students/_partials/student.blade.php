    <tr>
        <td>
            <div class="d-flex no-block align-items-center">
                <div class="m-r-10">
                    <img src="https://www.gravatar.com/avatar/{{md5($student->email)}}?s=200&r=pg" alt="user" class="rounded-circle" width="45">
                </div>
                <div class="">
                    <h5 class="m-b-0 font-16 font-medium">{{$student->name}}</h5>
                    <span>{{$student->email}}</span>
                </div>
            </div>
        </td>

{{--        <td>--}}
{{--            <span>{{isset($student->admission_stage) ? $student->admission_stage : 'N/A'}}</span>--}}
{{--        </td>--}}

        <td>
            @include('back.students._partials.student-submissions')
        </td>
{{--        <td> @include('back.students.submitted-applications', ['app_student' => $student]) </td>--}}

        <td class="small-column">

            @foreach ($student->invoices()->get() as $invoice)

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

        <td>
            @if (isset($student->agent))

                @if ($agency =  $student->agent->agency()->first())
                    <p>{{ optional($student->agent)->name }}
                        <a href="{{route('agencies.show' , $agency)}}" class="d-block">
                            <small>{{ $agency->name }}</small>
                        </a>
                    </p>
                @endif

            @endif
        </td>

        <td class="blue-grey-text  text-darken-4 font-medium">
            <span>{{$student->created_at->diffForHumans()}}</span>
        </td>

{{--        <td class="blue-grey-text  text-darken-4 font-medium">--}}
{{--            <span>{{$student->updated_at->diffForHumans()}}</span>--}}
{{--        </td>--}}

        <td class="small-column">
            <a href="javascript:void(0)" onclick="app.redirect(`{{route('students.show' , $student)}}`)">
                <i class="icon-eye"></i>
            </a>
            <a href="javascript:void(0)" onclick="app.updateStudentStage(`{{route('students.stage.update' , $student)}}`)">
                <i class="icon-shuffle"></i>
            </a>
            <a href="javascript:void(0)" onclick="app.deleteElement(`{{route('students.destroy' , $student)}}`, '','data-student-id')">
                <i class="icon-trash text-danger"></i>
            </a>
        </td>
    </tr>
