@extends('back.layouts.core.helpers.table' ,
        [
            'show_buttons' => true,
            'title'        => 'Build Quote Builder',
        ]
    )
@section('table-content')

        <table id="index_table" class="table table-striped table-bordered new-table display">
            <thead>
                <tr>
                    <!-- <th class="control-column"></th> -->
                    <th>{{__('Quote')}}</th>
                    <th>{{__('Application')}}</th>
                    <th class="no-sort"></th>
                    <th>ID</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @if ($quotations)
                    @foreach ($quotations as $quotation)
                        <tr>
                            <td><a href="{{route('quotations.edit' , $quotation)}}">{{$quotation->title}}</a></td>

                            <td>
                                <a target="_blank" href="{{route('applications.build' , ['application'=> $quotation->application ])}}"><span class="badge badge-info">{{$quotation->application->title}}</span></a>
                            </td>

                            <td><a href="{{route('quotations.show' , ['school'=>$school , 'quotation' => $quotation])}}">{{__('View')}}</a></td>

                            <td class="small-column">{{$quotation->id}}</td>

                            <td class="control-column cta-column">
                                @include('back.layouts.core.helpers.table-actions' , [

                                    'buttons'=> [

                                           'edit' => [

                                                'text' => 'Edit',

                                                'icon' => 'icon-note',

                                                'attr' => "onclick=app.redirect('".route('quotations.edit' , $quotation)."')",

                                                'class' => '',

                                           ],

                                           'delete' => [

                                                'text' => 'Delete',

                                                'icon' => 'icon-trash text-danger',

                                                'attr' => 'onclick=app.deleteElement("'.route('quotations.destroy' , $quotation).'","","data-quotation-id")',
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