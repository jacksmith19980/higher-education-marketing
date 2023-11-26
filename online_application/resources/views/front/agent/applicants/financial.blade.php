<div class="tab-pane fade l-psuedu-border bg-grey-1 p-4" id="nav-invoices" role="tabpanel"
     aria-labelledby="pills-invoices-tab">

    <div class="row" id="datatableNewFilter">
        <div class="col-md-6 col-sm-4 col-xs-12" id="lenContainer">
        </div>
        <div class="col-md-3 col-sm-4 col-xs-12" id="calContainer">
            <div class="input-group mr-3">
                <input id="calendarRanges" type="text" class="form-control calendarRanges">
                <div class="input-group-append">
                        <span class="input-group-text">
                            <span class="ti-calendar"></span>
                        </span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-4 col-xs-12" id="filterContainer">
        </div>
    </div>
    <input type="hidden" name="student_id" id="student_id" value="{{$applicant->id}}">

    <div class="table-responsive">
        <table id="accounting_student_table" data-route="{{route('school.agent.getInvoicePayment', $school)}}"
               data-i18n="{{$datatablei18n}}"
               class="table table-striped table-bordered new-table nowrap display" style="width: 100%;"
               data-school="{{$school->slug}}">

            <thead>
            <tr>
                <th>{{__('Date')}}</th>
                <th>{{__('Type')}}</th>
                <th>{{__('No.')}}</th>
                <th>{{__('Balance')}}</th>
                <th>{{__('Total')}}</th>
                <th>{{__('Status')}}</th>
                <th></th>
            </tr>
            </thead>
        </table>
        Balance: {{$balance}} {{isset($settings['school']['default_currency'])? $settings['school']['default_currency'] : 'CAD'}}
    </div>
</div>