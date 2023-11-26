@php
    $details = json_decode($assistant->details);

@endphp
@extends('back.layouts.core.helpers.table', [
            'show_buttons' => false,
            'title'        => false
        ])
@section('table-content')

    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-3 col-xlg-3 col-md-5">
                <div class="card">
                    <div class="card-body">
                        <center class="m-t-30">
                            <h4 class="card-title m-t-10">{{$details->user->first_name}} {{$details->user->last_name}}</h4>
                            <h6 class="card-subtitle">{{$details->user->email}}</h6>
                        </center>
                    </div>
                    <div><hr></div>

                </div>
            </div>

            <div class="col-lg-9 col-xlg-9 col-md-7">
                <div class="card">
                    <div class="card-body">
                        <div class="row">

                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tbody>
                                    @foreach($assistant->properties as $key => $item)
                                        @if(count($item) == 0)
                                            @continue
                                        @endif

                                        <tr>
                                            <th style="width:30%;"><strong>{{ucfirst($key)}}</strong></th>
                                            <td>
                                                @if($key == 'courses')
                                                    {{$item[0]['title']}}
                                                @elseif($key == 'programs')
                                                    @foreach($item as $key => $program)
                                                        @if(count($program) == 0) @continue @endif
                                                        @php
                                                            $program_obj = \App\Tenant\Models\Program::find($program['id'])
                                                        @endphp
                                                        <strong>{{__('Program')}}:</strong> {{ucfirst($program_obj->title)}}
                                                        <br>
                                                        <strong>{{__('Start Date')}}:</strong> {{$program['start']}}
                                                        <br>
                                                        <strong>{{__('End Date')}}:</strong> {{$program['end']}}
                                                        <br>
                                                        <strong>{{__('Schedule')}}:</strong> {{ \App\Helpers\Assistant\AssistantHelpers::getSchedule($program['schudel'])}}
                                                        <br><br>
                                                    @endforeach
                                                @elseif($key == 'campuses')
                                                    @php
                                                        $campus_obj = \App\Tenant\Models\Campus::find($item[0]['id'])
                                                    @endphp
                                                    <strong>Campus:</strong> {{$campus_obj->title}}
                                                @elseif($key == 'financials')
                                                    <ul>
                                                        @if(isset($item))
                                                            @foreach($item as $f)
                                                                <li>{{ $f }}</li>
                                                            @endforeach
                                                        @endif
                                                    </ul>
                                                @endif



                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>



                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection