@extends('front.instructor._partials.table', [
'show_buttons' => false,
'title' => 'Lesson Students',
])

@php
$type = explode('\\', $lesson->lessoneable_type);
@endphp


@section('table-content')
<form method="POST" action="{{ route('attendances.store', ['lesson' => $lesson, 'school' => $school]) }}">
    @csrf
    <table class="table table-striped table-bordered table-sm display">
        <thead>
            <tr>
                <th>{{__('Name')}}</th>
                <th>{{__('Action')}}</th>
            </tr>
        </thead>

        <tbody>
            @if ($students)

            @foreach ($students as $app_student)
            <tr data-student-id="{{$app_student->id}}">

                <td>{{$app_student->name}}</td>

                <td class="small-column">
                    @php
                    $attendance = $app_student->attendances->firstWhere('lesson_id', $lesson->id);
                    @endphp
                    <div style="width: 200px">
                        @include('back.layouts.core.forms.select', [
                        'name' => 'attendance[' . $app_student->id . ']',
                        'label' => '' ,
                        'class' => '' ,
                        'required' => true,
                        'attr' => isset($attendance) ? 'disabled' : '',
                        'data' => [
                        'présent - classe' => 'Présent - classe',
                        'présent - en ligne' => 'Présent - en ligne',
                        'absent' => 'Absent',
                        'retard' => 'Retard',
                        'withdrawn' => 'Withdrawn',
                        ],
                        'value' => isset($attendance) ? $attendance->status : ''
                        ])
                    </div>
                </td>
            </tr>
            @endforeach

            @endif
        </tbody>
    </table>
    {{-- {{$students->links()}} --}}
    <button class="btn btn-primary float-right" type="submit">{{__('Save')}}</button>
</form>
@endsection
