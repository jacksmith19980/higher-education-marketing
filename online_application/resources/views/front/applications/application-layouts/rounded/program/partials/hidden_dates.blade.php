@php
        $defaultValue =  SubmissionHelpers::getDefaultValue( $field );
        $readOnly =  "readonly";
@endphp

<input class="field_wrapper" id="{{$field->name}}[date]" type="hidden" name="{{$field->name}}[date]" value="@if($defaultValue){{ $defaultValue }}@else{{ null }}@endif" required="required">

<input class="field_wrapper" id="{{$field->name}}[end_date]" type="hidden" name="{{$field->name}}[end_date]" value="@if($defaultValue){{ $defaultValue }}@else{{ null }}@endif" required="required">


<input class="field_wrapper" id="{{$field->name}}[schedule]" type="hidden" name="{{$field->name}}[schedule]" value="@if($defaultValue){{ $defaultValue }}@else{{ null }}@endif" required="required">
