<form class="ajax-form" method="POST"
	action="{{route('date.store' , [
		'entity'  => $entity
	] )}}"
>

	@include('back.layouts.core.forms.hidden-input',
			[
			'name' => 'date_type',
			'label' => 'date_type' ,
			'class' => '' ,
			'required' => true,
			'attr' => '',
			'value' => $dateType,
			'data' => '',
	])
	@include('back.layouts.core.forms.hidden-input',
			[
			'name' => 'entity',
			'label' => 'entity' ,
			'class' => '' ,
			'required' => true,
			'attr' => '',
			'value' => $entity,
			'data' => '',
	])

	@include('back.layouts.core.forms.hidden-input',
			[
			'name'          => 'entity_type',
			'label'         => 'entity_type' ,
			'class'         => '' ,
			'required'      => true,
			'attr'          => '',
			'value'         => $entityType,
			'data'          => '',
	])
	@include('back.layouts.core.forms.hidden-input',
			[
			'name' => 'key',
			'label' => 'key' ,
			'class' => '' ,
			'required' => true,
			'attr' => '',
			'value' => Str::random(6),
			'data' => '',
	])
	@include('back.dates._partials.date-blocks')

</form>
