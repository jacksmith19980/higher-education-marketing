@extends('back.layouts.core.helpers.table' , [
            'show_buttons' => false,
            'title'        => 'Call Back Requests',
])
@section('table-content')

    <table id="index_table" class="table table-striped table-bordered new-table display">
        <thead>
        <tr>

            <th>{{__('Name')}}</th>
            <th>{{__('Phone')}}</th>
            <th>{{__('Status')}}</th>
            <th>{{__('Date Added')}}</th>
            <th>{{__('Assigned To')}}</th>
            <th>ID</th>
        </tr>
        </thead>
        <tbody>
        @if ($calls)
            @foreach ($calls as $call)
                <tr>

                    <td>
                        <a href="#">
                            <span class="d-block">{{$call->name}}</span>
                            <small class="text-muted">{{$call->email}}</small>
                        </a>
                    </td>

                    <td>{{$call->phone}}</td>

                    <!-- <td class="d-flex justify-content-between" style="align-items: center"> -->
                        <td class="" style="align-items: center">
                        <span class="d-block">
                            {{ucwords($call->status)}}
                        </span>

                        @if ($call->status == 'scheduled')
                            <a href="#" class="btn btn-outline-success waves-effect waves-light btn-small">
                                <i class="fa fa-phone"></i>
                            </a>
                        @endif
                    </td>

                    <td>{{$call->created_at->diffForHumans()}}</td>

                    <td>
                        @if($call->admission)
                            {{$call->admission->name}}
                        @endif
                    </td>

                    <td>{{$call->id}}</td>

                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
@endsection