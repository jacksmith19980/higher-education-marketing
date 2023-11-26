@extends('back.layouts.core.helpers.table' , [
    'show_buttons' => true,
    'title'=> 'Classrooms'
])

@section('table-content')

    <table id="index_table" class="table table-striped table-bordered new-table display">

        <thead>
        <tr>
            <!-- <th class="control-column">{{__('Actions')}}</th> -->
            <th>{{__('Classrooms')}}</th>
            <th>{{__('Campus')}}</th>
            <th>{{__('Time Slot')}}</th>
            <th></th>
{{--            <th>{{__('# of Seats')}}</th>--}}
        </tr>
        </thead>

        <tbody>
        @if ($classrooms)
            @foreach ($classrooms as $classroom)
                <tr data-classroom-id="{{$classroom->id}}">

                    <td>{{$classroom->title}}</td>
                    <td>
                        @include('back.campuses._partials.show', ['campus' => $classroom->campus])
                    </td>

                    <td>
                        @foreach ($classroom->classroomSlots as $slot)
                            <span class="badge badge-custom">
                            {{$slot->day}} -
                            @foreach($schedules as $schedule)
                            @if($schedule->id == $slot->schedule_id)
                              {{\App\Helpers\Quotation\QuotationHelpers::amOrPm($schedule->start_time)}}
                              {{\App\Helpers\Quotation\QuotationHelpers::amOrPm($schedule->end_time)}}
                            @endif
                            @endforeach
                            </span><br>
                        @endforeach
                    </td>
                    <td class="control-column cta-column">
                        @include('back.layouts.core.helpers.table-actions' , [
                           'buttons'=> [
                                  'edit' => [
                                       'text' => 'Edit Classroom',
                                       'icon' => 'icon-note',
                                       'attr' => "onclick=app.redirect('".route('classrooms.edit' , $classroom)."')",
                                       'class' => '',
                                  ],
                            
                                  'add_multi_lesson' => [
                                       'text' => 'Add Multiples Lessons in ' . $classroom->title,
                                       'icon' => 'far fa-calendar-plus',
                                       'attr' => "onclick=app.lessonCreate('".route('lessons.createMultiWithClassroom' , $classroom)."')",
                                       'class' => '',
                                  ],

                                  'calendar' => [
                                       'text' => 'Calendar for ' . $classroom->title,
                                       'icon' => 'icon-calender',
                                       'attr' => "onclick=app.redirect('".route('classrooms.show' , $classroom)."')",
                                       'class' => '',
                                  ],

                                  'delete' => [
                                       'text' => 'Delete Classroom',
                                       'icon' => 'icon-trash text-danger',
                                       'attr' => 'onclick=app.deleteElement("'.route('classrooms.destroy' , $classroom).'","","data-classroom-id")',
                                       'class' => '',
                                  ],
                           ]
                       ])

                    </td>

{{--                    <td class="small-column">{{$classroom->capacity}}</td>--}}
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>

@endsection
