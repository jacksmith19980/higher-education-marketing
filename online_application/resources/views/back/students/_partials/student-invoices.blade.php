<div class="tab-pane fade l-psuedu-border bg-grey-1 p-4" id="nav-invoices" role="tabpanel" aria-labelledby="pills-invoices-tab">
        <div class="row" id="datatableNewFilter">
            <div class="col-lg-4 col-md-4 col-12 d-flex" id="lenContainer" style="">
            </div>
            <div class="col-lg-5 pl-0 pl-md-2 col-md-5 col-12 d-flex" id="calContainer">
                 <div class="input-group date">
                    <input id="calendarRanges" type="text" class="form-control calendarRanges">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <span class="ti-calendar"></span>
                        </span>
                    </div>
                </div>
               
            </div>
            <div class="col-lg-3 col-md-3 col-12 d-flex justify-content-end" id="filterContainer">
                <div class="dropdown show">
                    <a class="btn dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        New
                    </a>

                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <a class="dropdown-item" href="{{route('invoices.createPolymorph', ['student_id' => $applicant->id])}}">Invoice</a>
                        <a class="dropdown-item" href="{{route('payment.createPolymorph', ['student' => $applicant->id])}}">Receive Payment</a>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="student_id" id="student_id" value="{{$applicant->id}}">

        <div class="table-responsive">
            <table id="accounting_student_table" data-route="{{route('accounting.getInvoicePayment')}}" data-i18n="{{$datatablei18n}}"
                   class="table table-striped table-bordered new-table nowrap display" style="width: 100%;">

                <thead>
                    <tr>
                        <th>{{__('Date')}}</th>
                        <th>{{__('Transaction Type')}}</th>
                        <th>{{__('Payment Method')}}</th>
                        <th>{{__('Id')}}</th>
                        <th>{{__('No.')}}</th>
                        <th>{{__('Due Date')}}</th>
                        <th>{{__('Balance')}}</th>
                        <th>{{__('Status')}}</th>
                        <th>{{__('Total')}}</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
            <span class="total-blue">{{__('Balance')}}:</span> <span id="due-balance"> {{$balance}} {{isset($settings['school']['default_currency'])? $settings['school']['default_currency'] : 'CAD'}} </span>
        </div>
</div>