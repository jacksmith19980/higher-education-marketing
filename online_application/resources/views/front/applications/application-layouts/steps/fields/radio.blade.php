<div class="col-md-{{$properties['wrapper']['columns']}} col-sm-12 col-xs-12 {{$properties['wrapper']['class']}} ">
	<div class="form-group">
	
		@if($properties['label']['show'])    
	    <label for="{{$name}}">{{ __($properties['label']['text']) }}:</label><br />
	    @endif
		
		@foreach ($data as $k=>$v)
			<div class="form-check form-check-inline">
	            <div class="custom-control custom-radio">
	                <input class="custom-control-input" id="{{$k}}" name="{{$name}}" type="radio" value="{{$k}}" {{($properties['validation']['required']) ? ' required="required"': ' '}}  @checked($value,$k) {{ ' checked ="checked"' }} @endchecked>
	                <label class="custom-control-label" for="{{$k}}">{{$v}}</label>
	            </div>
			</div>
		@endforeach

	</div>
</div>
