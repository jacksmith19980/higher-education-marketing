<div class="col-lg-6 col-md-6 col-sm-12 draggable">
    <div class="card new-card card-hover recent-accounts">
        <div class="card-body">
            <div class="d-flex card-header two-col-header justify-content-between pr-2">
                <h4 class="card-title">
                    <i class="flaticon-leader pr-2 regular-icon"></i>
                    {{__('Recent Transactions')}}
                </h4>
                <a href="{{ route('accounting.index') }}" class="text-center text-primary">
                    <small>{{__('VIEW ALL')}}</small>
                </a>
            </div>
            <div class="striped-row card-content">
                <table class="table table-striped">
                    <tr class="bg-white">
                        <th class="border-0">{{__('Applicant')}}</th>
                        <th class="border-0">{{__('Type')}}</th>
                        <th class="border-0">{{__('Total')}}</th>
                    </tr>
                    <tr />
                    @foreach($latestTransactions as $transaction)
                        @if(isset($transaction->applicant))
                        <tr>
                            <td>
                                <a href="{{ route('applicants.show', [
                                    'student' => $transaction->applicant
                                ]) }}">
                                    <h6 class="font-medium">
                                        {{ $transaction->applicant->name }}
                                    </h6>
                                </a>
                            </td>
                            <td>
                                <h6 class="font-medium">
                                    {{ $transaction->type }}
                                </h6>
                            </td>
                            <td>
                                <span class="badge badge-default badge-info text-white d-block">
                                    {{ money($transaction->amount) }}
                                </span>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
