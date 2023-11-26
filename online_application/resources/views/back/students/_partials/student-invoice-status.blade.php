<div class="list-group-item list-group-item-action flex-column align-items-start">
    <div class="d-flex w-100 justify-content-between">
        <h5 class="mb-1">
            <span class="d-block">
                <i class="fa fa-circle {{($status->status == 'Paid') ? 'text-success' : 'text-warning'}}" style="font-size:50%" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{$status->status}}"></i>
                {{$status->status}}
            </span>
            <small class="text-muted">{{$status->created_at->diffForHumans()}}</small>
        </h5>
        <div>
            @if ($status->status == 'Invoice Created')
                <button class="btn btn-circle btn-light" data-toggle="tooltip" data-placement="top" title=""
                data-original-title="{{__('Send reminder email')}}"
                onclick="app.sendInvoiceReminder('{{ route('invoice.reminder.email' , ['invoice' => $invoice , 'student' => $applicant] )}}' , '' , 'Send Reminder Email' , this)"
                ><i class="ti ti-email"></i></button>
            @endif
        </div>
    </div>
    @if (isset($status->properties))
        @php
          $properties =  $status->properties
        @endphp

        @if (!is_array($status->properties))

            @php
                    $properties =  json_decode($status->properties , true);
            @endphp

        @endif

        @foreach ($properties as $key=>$value)
            @if(is_array($value))
                @php
                    $value = json_encode($value)
                @endphp
            @endif

            <small class="text-muted d-block"><strong>{{$key}}: </strong> {{$value}}</small>

        @endforeach
    @endif
</div>
