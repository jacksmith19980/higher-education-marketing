<div class="card-body" style="padding-bottom: 0px;padding-top: 0px;">
    <h3 class="card-title">{!! __('Payment Selected') !!}</h3>

    <div class="bg-light-grey">
        <div class="list-group d-block w-100">
            <table class="table table-striped">
                <tbody>

                @if($first_payment > 0)
                    <tr>
                        <td>{!! __('First Payment') !!}</td>
                        <td>
                            <strong class="text-info price">{{$settings['school']['default_currency']}}{{$first_payment}}</strong>
                        </td>
                        <td>
                            {{$first_payment_date}}
                        </td>
                    </tr>
                @endif

                @foreach($installments as $intallment)
                    <tr>
                        <td>{!! __($payment_type) !!}</td>
                        <td>
                            <strong class="text-info price">{{$settings['school']['default_currency']}}{{$intallment['amount']}}</strong>
                        </td>
                        <td>
                            {{$intallment['date']}}
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>