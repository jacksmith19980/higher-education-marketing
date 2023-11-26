@extends('back.layouts.core.helpers.table' ,
        [
            'show_buttons' => true,
            'title'        => 'Promo Codes',
        ]
    )
@section('table-content')

    <table id="index_table" class="table table-striped table-bordered new-table display">
        <thead>
        <tr>
            <!-- <th class="control-column"></th> -->
            <th>{{__('Active')}}</th>
            <th>{{__('Code')}}</th>
            <th>{{__('Quotation')}}</th>
            <th>{{__('Reward')}}</th>
{{--            <th>{{__('Quantity')}}</th>--}}
            <th>{{__('Message')}}</th>
            <th>{{__('Automatic')}}</th>
            <th>{{__('Disposable')}}</th>
            <th>{{__('Start Date')}}</th>
            <th>{{__('Expires In')}}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if ($codes)
            @foreach ($codes as $code)
                <tr>

                    <td class="small-column" style="text-align: center;">
                        <i class="fa fa-circle {{ $code->isActive() ? 'text-success' : 'text-danger' }}"
                           data-toggle="tooltip" data-placement="top" data-code-id="{{$code->id}}"
                           title={{ $code->isActive() ? __('Active') : __('Inactive') }}></i>
                    </td>

                    <td><a href="{{route('promocodes.show' , $code)}}">{{$code->code}}</a></td>

                    <td>
                        @if($code->quotations->count() > 0 )
                            @foreach($code->quotations as $quotation)
                                    <span class="badge badge-info">{{$quotation->title}}</span>
                            @endforeach
                        @else
                            <span class="badge badge-light">{{__('Global')}}</span>
                        @endif
                    </td>

                    <td style="text-align: center;">{{ $code->reward }} {{ $code->type() }}</td>

{{--                    <td class="small-column" style="text-align: center;">--}}
{{--                        @if($code->quantity)--}}
{{--                            {{ $code->quantity}}--}}
{{--                        @else--}}
{{--                            <span class="badge badge-primary">{{__('Infinite')}}</span>--}}
{{--                        @endif--}}
{{--                    </td>--}}

                    <td>
                        @if(isset($code->data['message']) )
                            {{ $code->data['message']}}
                        @else
                            <span class="badge badge-light">{{__('Empty')}}</span>
                        @endif
                    </td>

                    <td class="small-column"
                        style="text-align: center;">{{ $code->is_automatic ? __('Yes') : __('No') }}</td>

                    <td class="small-column"
                        style="text-align: center;">{{ $code->is_disposable ? __('Yes') : __('No') }}</td>

                    <td>{{ $code->commence_at->diffForHumans() }}</td>
                    <td>{{ $code->expires_at->diffForHumans()}}</td>

                    <td class="control-column cta-column">
                        @include('back.layouts.core.helpers.table-actions' , [
                        'buttons'=> [
                            'delete' => [
                                'text' => 'Disable',
                                'icon' => 'icon-ban text-danger',
                                'attr' => 'onclick=app.disablePromoCode("'.route('promocodes.destroy' , $code).'","","data-code-id")',
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