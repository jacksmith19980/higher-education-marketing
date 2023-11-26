{{-- REVIEW AND SEND --}}
<a href="javascript:void(0)" onclick="app.generateContract({{$submission->id}} , {{$submission->student->id}} , '{{route('submission.contract.generate' , ['submission' => $submission->id])}}', this)" class="mr-2 btn btn-default small-btn"
data-modal-title = "{{__('Generate Contract')}}"
data-toggle="tooltip" data-placement="top" title="{{__('Generate Contract')}}">
<i class="ti-pencil-alt"></i> {{__('Generate Contract')}}
</a>

{{-- SEND CONTRACT --}}
{{--  <a href="javascript:void(0)" onclick="app.sendContract({{$submission->id}} , {{$submission->student->id}} , this)" class="mr-2 btn btn-default small-btn" data-toggle="tooltip" data-placement="top" title="{{__('Send Contract')}}">
<i class="mr-1 icon-pencil"></i> {{__('Send Contract')}}
</a>  --}}
