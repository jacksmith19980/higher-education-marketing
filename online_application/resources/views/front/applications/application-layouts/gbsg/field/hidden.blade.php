<input  id="field_wrapper {{$name}}" type="hidden" name="{{$name}}" value="@if($value){{ $value }}@else{{ old($name) }}@endif" required="required">