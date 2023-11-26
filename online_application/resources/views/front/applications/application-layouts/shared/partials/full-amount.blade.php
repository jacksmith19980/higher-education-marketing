<div class="card-body" style="padding-bottom: 0px;padding-top: 0px;">
    <h3 class="card-title">{!! __('Payment Selected') !!}</h3>

    <div class="bg-light-grey">
        <div class="list-group d-block w-100">
            <table class="table table-striped">
                <tbody>
                <tr>
                    <td>{!! __('Total Amount') !!}</td>
                    <td>
                        <strong class="text-info price">{{$settings['school']['default_currency']}}{{number_format($total)}}</strong>
                    </td>
                    <td>
                        {{$date}}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>