<div class="row">
    <div class="col-md-2 offset-10 m-b-30">
        @include('back.layouts.core.helpers.add-elements-button' , [
            'text'          => 'Add Dates',
            'action'        => 'course.addSingleDay',
            'container'     => '#single_day_wrapper'
        ])
    </div>
</div>

<div class="row" id="single_day_wrapper">
@if(isset($courseDates))
        @foreach($courseDates as $c)
            @if(isset($c['properties']['date']) && isset($c['properties']['start_time']) && !empty(isset($c['properties']['date'])))

                    <div style="display: flex; flex-wrap: wrap;" class="col-md-12 date-row 2">
                        <input type='hidden' name="date_id[]" value="{{ $c['id']}}">
                        <div class="col-md-3">
                        	@include('back.layouts.core.forms.date-input',
				            [
				            'name'  => 'properties[date][]',
				            'label' => 'Date' ,
				            'class' => 'ajax-form-field' ,
				            'required' => true,
				            'attr' => '',
				            'value' => isset($c['properties']['date']) ? $c['properties']['date']  : '',
				            'data' => ''
				            ])
                        </div>

                        <div class="col-md-3">
                        	@include('back.layouts.core.forms.time-input',
				            [
				            'name' => 'properties[start_time][]',
				            'label' => 'Start time' ,
				            'class' => 'ajax-form-field' ,
				            'required' => true,
				            'attr' => '',
				            'value' => isset($c['properties']['start_time']) ? $c['properties']['start_time'] : '',
				            'data' => ''
				            ])
                        </div>


                            <div class="col-md-3">

                            	@include('back.layouts.core.forms.time-input',
					            [
					            'name' => 'properties[end_time][]',
					            'label' => 'End time' ,
					            'class' => '' ,
					            'required' => false,
					            'attr' => '',
					            'value' => isset($c['properties']['end_time']) ? $c['properties']['end_time'] : '',
					            'data' => ''
					            ])
                            ])
                            </div>

                            <div class="col-md-2">
                            	@include('back.layouts.core.forms.text-input',
					            [
					            'name' => 'properties[date_price][]',
					            'label' => 'Price' ,
					            'class' => '' ,
					            'required' => false,
					            'attr' => '',
					            'value' => isset($c['properties']['date_price']) ? $c['properties']['date_price'] : '',
					            'data' => '',
					            ])

                            </div>

                        <div class="col-md-1 action_wrapper">
                            <div class="form-group m-t-27">
                                <button class="btn btn-danger" type="button" onclick="app.deleteElementsRow(this, 'date-row')">
                                <i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                </div>
            @endif
        @endforeach
    @elseif(isset($course->properties['date']) && isset($course->properties['date']) && !empty($course->properties['date']))

        @foreach ($course->properties['date'] as $key=>$startDate )
            <div style="display: flex; flex-wrap: wrap;" class="col-md-12 date-row 1">
                <div class="col-md-3">
                	@include('back.layouts.core.forms.date-input',
		            [
		            'name'  => 'properties[date][]',
		            'label' => 'Date' ,
		            'class' => '' ,
		            'required' => false,
		            'attr' => '',
		            'value' => isset($startDate) ? $startDate : '',
		            'data' => ''
		            ])
                </div>

                <div class="col-md-3">
                	@include('back.layouts.core.forms.time-input',
		            [
		            'name' => 'properties[start_time][]',
		            'label' => 'Start time' ,
		            'class' => '' ,
		            'required' => false,
		            'attr' => '',
		            'value' => isset($course->properties['start_time'][$key]) ? $course->properties['start_time'][$key] : '',
		            'data' => ''
		            ])
                </div>
                <div class="col-md-3">
                	@include('back.layouts.core.forms.time-input',
		            [
		            'name' => 'properties[end_time][]',
		            'label' => 'End time' ,
		            'class' => 'false' ,
		            'required' => false,
		            'attr' => '',
		            'value' => isset($course->properties['end_time'][$key]) ? $course->properties['end_time'][$key] : '',
		            'data' => ''
		            ])
                    </div>

                    <div class="col-md-2">
                    	@include('back.layouts.core.forms.text-input',
			            [
			            'name' => 'properties[date_price][]',
			            'label' => 'Price' ,
			            'class' => '' ,
			            'required' => false,
			            'attr' => '',
			            'value' => isset($course->properties['date_price'][$key]) ? $course->properties['date_price'][$key] : '',
			            'data' => '',
			            ])

                    </div>

                <div class="col-md-1 action_wrapper">
                    <div class="form-group m-t-27">
                        <button class="btn btn-danger" type="button" onclick="app.deleteElementsRow(this, 'date-row')">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>

            </div>
        @endforeach
    @endif
</div>