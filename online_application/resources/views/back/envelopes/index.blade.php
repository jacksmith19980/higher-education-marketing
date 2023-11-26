@extends('back.layouts.core.helpers.table' , [
'show_buttons' => true,
'title' => 'E-Signatures',
])
@section('table-content')

<table id="index_table" class="table table-striped table-bordered new-table display">
    <thead>
        <tr>

            <th>{{__('Title')}}</th>
            <th>{{__('Campuses')}}</th>
            <th>{{__('Date Added')}}</th>
            <th>ID</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @if ($envelopes)
        @foreach ($envelopes as $envelope)
            @php
                $campuses = $envelope->campuses->pluck('title')->toArray();
            @endphp
        <tr data-envelope-id="{{$envelope->id}}">
            <td>{{$envelope->title}}</td>
            <td>{{ ($campuses) ? implode(", " , $campuses) : 'N/A'  }}</td>
            <td>{{$envelope->created_at->diffForHumans()}}</td>
            <td>{{$envelope->id}}</td>
            <td class="control-column cta-column">
                @include('back.layouts.core.helpers.table-actions' , [
                'buttons'=> [
                    'view' => [
                        'text' => 'View',
                        'icon' => 'icon-eye',
                        'href' => route('envelope.show' , $envelope),
                        'attr' => "",
                        'class' => '',
                    ],
                    'edit' => [
                        'text' => 'Edit',
                        'icon' => 'icon-note',
                        'href' => route('envelope.edit' , $envelope),
                        'attr' => "",
                        'class' => '',
                    ],

                    'clone' => [
                        'text' => 'Clone',
                        'icon' => 'icon-layers',
                        'href' => route('envelope.clone' , $envelope),
                        'attr' => "",
                        'class' => '',
                    ],

                    'delete' => [
                    'text' => 'Delete',
                    'icon' => 'icon-trash text-danger',
                    'attr' => 'onclick=app.deleteElement("'.route('envelope.destroy' ,
                    $envelope).'","","data-envelope-id")',
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
