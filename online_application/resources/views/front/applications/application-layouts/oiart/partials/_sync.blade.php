@if(isset($properties['sync']['isSynced']) && $properties['sync']['isSynced'])

	data-sync="{{$properties['sync']['isSynced']}}"

	data-sync-target="{{$properties['sync']['target']}}"

	data-sync-source="{{ $properties['sync']['source'] }}"

	data-sync-field="{{(isset($repeater) && $repeater) ? $properties['sync']['field'] ."[". $params['order'] ."]" : $properties['sync']['field']}}"

	data-route="{{route('application.field.sync' , ['school'=>$school,'application' => $application , 'field' => $field])}}"

	data-field-value="{{$defaultValue}}"

	@if (!isset($defaultValue) || empty($defaultValue))
		disabled="disabled"
	@endif

@endif
