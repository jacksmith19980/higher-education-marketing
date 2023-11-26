@extends('back.layouts.default')
{{-- @section('table-content') --}}
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="sec-app-dashboard no-pseudo-border">
                    <div class="p-4 mb-4 app-dashboard-container box-shadow ">
                        <div class="app-dashboard-header">
                            <div class="row align-items-center">
                                <div class="col-md-6 col-lg-7">
                                    <div class="d-flex align-items-center">
                                        <div class="col flex-grow-1">
                                            <div class="applicant-main-info">
                                                <h4 class="mb-1">{{__('Lesson')}}</h4>
                                                <br>

                                                <p class="mb-1"><strong>{{__('Classroom')}}: </strong> {{ $lesson->classroom->title }}</p>

                                                <p class="mb-1"><strong>{{__('Program')}}: </strong>{{ $lesson->program->title }}</p>

                                                <p class="mb-1">
                                                    <strong>
                                                        {{__('Course')}}:
                                                    </strong> {{$lesson->course->title }}
                                                    </p>

                                                <p class="mb-1"><strong>{{__('Date')}}: </strong>{{ $lesson->date }}</p>
                                                <p class="mb-1"><strong>{{__('Start Time')}}: </strong>
                                                    {{ QuotationHelpers::amOrPm($schedule->start_time)}}
                                                </p>
                                                <p class="mb-1"><strong>{{__('End Time')}}: </strong>
                                                    {{ QuotationHelpers::amOrPm($schedule->end_time) }}
                                                </p>
                                                <p class="mb-1"><strong>{{__('Instructor')}}: </strong>
                                                    {{ $lesson->instructor->first_name }} {{ $lesson->instructor->last_name }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>

                        <div class="student-record-content">
                            <table class="table responsiveDataTable display new-table nowrap w-100 responsive dataTable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{__('Name')}}</th>
                                    <th>{{__('Cohort')}}</th>
                                    <th>{{__('Status')}}</th>
                                </tr>
                                </thead>

                                <tbody>
                                @if ($students)
                                    @foreach ($students as $app_student)
                                        <tr data-student-id="{{$app_student->id}}">

                                            <td>{{$loop->index + 1}}</td>


                                            <td>
                                            <a href="{{route('students.show' , ['student' => $app_student])}}">
                                                {{$app_student->name}}
                                            </a>
                                            </td>

                                            @php
                                                $intersect = $lesson->groups->intersect($app_student->groups);
                                            @endphp

                                            <td>
                                                @if (count($intersect) > 0)
                                                    @foreach($intersect as $group)
                                                        <span class="badge badge-secondary">{{$group->title}}</span>
                                                    @endforeach
                                                @endif
                                            </td>

                                            <td class="small-column">
                                                @php
                                                    $attendance = $app_student->attendances->firstWhere('lesson_id', $lesson->id);
                                                @endphp
                                                <div style="width: 200px">
                                                    <span class="badge badge-secondary">{{ucfirst(isset($attendance) ? $attendance->status : 'N/A')}}</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
