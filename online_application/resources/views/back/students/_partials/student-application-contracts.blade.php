@if($contract->status == 'created')

    <a href="javascript:void(0)" onclick="app.voidContract({{$submission->id}},{{$submission->student->id}}, this)" class="mr-2 btn btn-danger small-btn" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{__('Void Contract')}}">
        <i class="mr-1 icon-times"></i> {{__('Void Contract')}}
    </a>

    <a href="javascript:void(0)" onclick="app.reviewContract({{$submission->id}},{{$submission->student->id}}, this)" class="mr-2 btn btn-success small-btn" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{__('Review Contract')}}">
    <i class="mr-1 icon-eye"></i> {{__('Review Contract')}}
    </a>

@elseif($contract->status == 'delivered')

<a href="javascript:void(0)" onclick="app.voidContract({{$submission->id}},{{$submission->student->id}}, this)" class="mr-2 btn btn-danger small-btn" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{__('Void Contract')}}">
    <i class="mr-1 icon-times"></i> {{__('Void Contract')}}
</a>

@elseif($contract->status == 'voided')

    @include('back.students._partials.student-application-contract-buttons')

@elseif($contract->status == 'sent')

    <a href="javascript:void(0)" onclick="app.voidContract({{$submission->id}},{{$submission->student->id}}, this)" class="mr-2 btn btn-danger small-btn" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{__('Void Contract')}}">
        <i class="mr-1 icon-times"></i> {{__('Void Contract')}}
    </a>

    {{--  <a href="javascript:void(0)" onclick="app.sendContractReminder('{{$contract->uid}}' , {{$student->id}} , this)"
        class="mr-2 text-white btn btn-light bg-primary small-btn" data-toggle="tooltip" data-placement="top" title="{{__('Send Reminder')}}">
        <i class="mr-1 ti-timer"></i> {{__('Send Reminder')}}
    </a>  --}}

    <a
    href="javascript:void(0)"
    onclick="app.downloadContractDocument('combined', '{{$contract->uid}}' , false , this)"
    class="mr-2 btn btn-success small-btn"
    data-toggle="tooltip" data-placement="top" title="" data-original-title="{{__('View Contract')}}">

    <i class="mr-1 icon-eye"></i> {{__('View Contract')}}
    </a>

@elseif($contract->status == 'completed')

    <a href="javascript:void(0)" onclick="app.voidContract({{$submission->id}},{{$submission->student->id}}, this)" class="mr-2 btn btn-danger small-btn" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{__('Void Contract')}}">
        <i class="mr-1 icon-times"></i> {{__('Void Contract')}}
    </a>

    <a
    href="javascript:void(0)"
    onclick="app.downloadContractDocument('combined', '{{$contract->uid}}' , false , this)"
    class="mr-2 btn btn-success small-btn" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{__('View Contract')}}">
    <i class="mr-1 icon-eye"></i> {{__('View Contract')}}
    </a>

    @include('back.students._partials.student-application-contract-buttons')

@endif
