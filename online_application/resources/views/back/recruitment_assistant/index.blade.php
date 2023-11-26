@extends('back.layouts.core.helpers.table' , [
            'show_buttons' => true,
            'title'        => 'Assistant Builder',
])
@section('table-content')

    <table id="index_table" class="table table-striped table-bordered new-table display">
        <thead>
        <tr>
            <!-- <th class="control-column"></th> -->
            <th>{{__('Assistant')}}</th>
            <th class="no-sort"></th>
            <th>ID</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if ($assistantBuilders)
            @foreach ($assistantBuilders as $assistantBuilder)
                <tr data-assistant-id="{{$assistantBuilder->id}}">
                    <td><a href="{{route('assistantsBuilder.edit' , $assistantBuilder)}}">{{$assistantBuilder->title}}</a></td>

                    <td><a href="{{route('assistants.show' , ['school'=>$school , 'assistantBuilder' => $assistantBuilder])}}">{{__('View')}}</a></td>

                    <td class="small-column">{{$assistantBuilder->id}}</td>

                    <td class="control-column cta-column">
                        @include('back.layouts.core.helpers.table-actions' , [
                            'buttons'=> [
                               'edit' => [
                                    'text' => 'Edit',
                                    'icon' => 'icon-note',
                                    'attr' => "onclick=app.redirect('".route('assistantsBuilder.edit' , $assistantBuilder)."')",
                                    'class' => '',
                               ],
                               'delete' => [
                                    'text' => 'Delete',
                                    'icon' => 'icon-trash text-danger',
                                    'attr' => 'onclick=app.deleteElement("'.route('assistantsBuilder.destroy' , $assistantBuilder->id).'","","data-assistant-id")',
                                    'class' => '',
                                ]
                            ]
                        ])
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
@endsection