@extends('back.layouts.core.helpers.table' , [
            'show_buttons' => false,
            'title'        => 'Leads',
])

@section('table-content')

    <table id="index_table" class="table table-bordered new-table nowrap display">
        <thead>
        <tr>
            <th>{{__('Name')}}</th>
            <th>{{__('Request Type')}}</th>
            <th>{{__('Updated')}}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if ($leads)
            @foreach ($leads as $lead)
                @php
                    $details = json_decode($lead->details);
                @endphp
                <tr data-lead-id="{{$lead->id}}-{{$lead->request_type}}">

                   @if($lead->request_type == 'quote')
                        <td><a href="{{ route('quote.show' , $lead ) }}">{{ $details->user->first_name }} {{ $details->user->last_name}}</a><br/><small>{{ $details->user->email }}</small></td>
                   @else
                        <td><a href="{{ route('assistant.show' , $lead ) }}">{{ $details->user->first_name }} {{ $details->user->last_name }}</a><br/><small>{{ $details->user->email }}</small></td>
                   @endif

                   @if($lead->request_type == 'quote')
                    <td>{{$lead->request_type }}</td>
                  @else
                    <td>assistant</td>
                  @endif
                    <td>
                      {{ $lead->updated_at->toDateTimeString() }}
                    </td>

                    <td class="control-column cta-column">
                        @include('back.layouts.core.helpers.table-actions' , [
                           'buttons'=> [
                              'delete' => [
                                   'text' => 'Delete Lead',
                                   'icon' => 'icon-trash text-danger',
                                   'attr' => 'onclick=app.deleteElement("'.route('students.leads.destroy', ['lead' => $lead, 'type' => ($lead->request_type) ? $lead->request_type : 'lead']).'","","data-lead-id")',
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
