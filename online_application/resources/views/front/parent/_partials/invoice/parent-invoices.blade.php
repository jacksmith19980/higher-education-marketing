<div class="col-lg-6">
    <div class="card">
        <div class="card-body">
            <div class="d-md-flex align-items-center">
                <div>
                    <h4 class="card-title">{{__('Invoices')}}</h4>
                </div>
                <div class="ml-auto d-flex no-block align-items-center">
                    <div class="dl m-b-15">
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table no-wrap v-middle">
                    <thead class="bg-info text-white">
                        <tr class="border-0">
                            <th class="border-0">{{__('Invoice')}}</th>
                            <th class="border-0">{{__('Total')}}</th>
                            <th class="border-0">{{__('Status')}}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        @foreach ($children as $child)
                            @foreach ($child->invoices as $invoice)
                                @php
                                    
                                    $status = $invoice->status()->orderBy('created_at' , 'desc')->first();

                                @endphp
                                <tr>
                                    <td>{{$invoice->uid}}</td>
                                    <td>{{$invoice->total}} {{$settings['school']['default_currency']}}</td>
                                    <td>{{$status->status}}
                                        <br/><small>{{$status->created_at->diffForHumans()}}</small>
                                    </td>
                                    <td>
                                        @if ($status->status == 'Invoice Created')
                                            <a href="{{route('show.payment' , ['school' => $school , 'application' => $invoice->application , 'invoice' => $invoice])}}" class="btn btn-small btn-success text-white" data-toggle="tooltip" data-placement="top" title="{{__('Pay Invoice Now')}}" ><span class="ti-check-box "></span> {{__('Pay Now')}}</a>
                                        @else

                                            <a href="javascript:void(0)" class="btn btn-light btn-info" 
                                                data-toggle="tooltip" data-placement="top" title="{{__('View Invoice Details')}}">
                                                    <span class="ti-file"></span>  {{__('Details')}}
                                            </a>


                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>