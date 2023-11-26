@if (!isset($properties))

  @php
    $properties = $field->payment->properties;
  @endphp

@endif
<script src = "https://wl.flywire.com/assets/js/flywire.js" ></script>
<div class="col-md-12 justify-content-center">
  <div class="d-flex justify-content-center">
      <div id = "flywire-payex" style="width:100%;min-height:500px;"
      data-amount="{{$invoice->total * 100}}"
      data-destination="{{$properties['destination']}}"
      data-env="{{ (isset($properties['is_sandbox_account']) && $properties['is_sandbox_account'] ) ? "demo" : "production" }}"
      data-invoice-number = "{{$invoice->uid}}"
      data-sender-first-name = "{{$sender->first_name}}"
      data-sender-last-name = "{{$sender->last_name}}"
      data-sender-email = "{{$sender->email}}"
      data-student-id = "{{$student->email}}"
      data-student-first-name = "{{$student->first_name}}"
      data-student-last-name = "{{$student->last_name}}"
      data-call-back={{ route('payment.track' , ['school' => $school , 'student' => auth()->guard('student')->user()] ) }} ></div>
  </div>
</div>
