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
                <select id='classrooms' name="classrooms" class="multiSelect" multiple placeholder="{{__('Classroom')}}">
                    @foreach($classrooms as $k => $val)
                        <option value="{{$k}}">{{__($val)}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <select id='courses' name="courses" class="multiSelect" multiple placeholder="{{__('Course')}}">
                    @foreach($courses as $k => $val)
                        <option value="{{$k}}">{{__($val)}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <select id='instructors' name="instructors" class="multiSelect" multiple placeholder="{{__('Instructor')}}">
                    @foreach($instructors as $k => $val)
                        <option value="{{$k}}">{{__($val)}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 mt-1">
                <button id="apply" type="button" class="btn btn-primary float-right">
                    {{__('Apply')}} <i class="fas fa-caret-right"></i>
                </button>
            </div>
        </div>
    </div>
</aside>
