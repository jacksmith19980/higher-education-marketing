<!-- ============================================================== -->
<!-- customizer Panel -->
<!-- ============================================================== -->
<aside class="customizer">

    <div style="background-color: #E8ECED; color: #282827; padding: 10px 20px;">
        <i class="fas fa-filter" style="color: #90918E;"></i> {{__('Filters')}}
    </div>

    <div class="customizer-body">
        <div class="container">
            <!-- Sidebar -->
			
	    <div class="col-12">
		<select id='students' class="filterField multiSelect w-100 form-select pt-1 pb-1 mt-1 mb-1" multiple name="students" placeholder="{{__('Students')}}">
                    <!--<option selected>Courses</option>-->
		    @foreach($students as $k => $val)
                        <option value="{{$k}}">{{ $val }}</option>
                    @endforeach
                </select>
            </div>
			
	    <div class="col-12">
                <select id='courses' class="filterField multiSelect w-100 form-select pt-1 pb-1 mt-1 mb-1" multiple name="courses" placeholder="{{__('Courses')}}">
                    <!--<option selected>Courses</option>-->
		    @foreach($courses as $k => $val)
                        <option value="{{$k}}">{{ $val }}</option>
                    @endforeach
                </select>
            </div>
			
	    <div class="col-12">
                <select id='instructors' class="filterField multiSelect w-100 form-select pt-1 pb-1 mt-1 mb-1" multiple name="instructors" placeholder="{{__('Instructors')}}">
			<!--<option selected>Instructors</option>-->
		    @foreach($instructors as $k => $val)
                        <option value="{{$k}}">{{ $val }}</option>
                    @endforeach
                </select>
            </div>
			
	    <div class="col-12">
		<select id='timeslots' class="filterField multiSelect w-100 form-select pt-1 pb-1 mt-1 mb-1" multiple name="timeslots" placeholder="{{__('Timeslots')}}">
			<!--<option selected>Instructors</option>-->
		    @foreach($classroomslots as $k => $val)
                        <option value="{{$k}}">{{ $val }}</option>
                    @endforeach
                </select>
            </div>
			
            <div class="col-12">
                <select id='statuses' class="filterField multiSelect w-100 form-select pt-1 pb-1 mt-1 mb-1" name="statuses" multiple placeholder="{{__('Status')}}">
                    @foreach($statuses as $k => $val)
                        <option value="{{ $val }}">{{ $val }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 mt-1">
                <button id="apply" type="button" class="btn btn-primary float-right" >
                    {{__('Apply')}} <i class="fas fa-caret-right"></i>
                </button>
            </div>
        </div>
    </div>
</aside>
