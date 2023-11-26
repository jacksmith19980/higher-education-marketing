<!-- ============================================================== -->
<!-- customizer Panel -->
<!-- ============================================================== -->
<aside class="customizer">
    <span class="btn btn-circle customizer-close-btn"><i class="icon-close"></i></span>
    <div class="px-3 py-2">
        <i class="fas fa-sliders-h" style="color: #90918E;"></i> {{__('Filters')}}
    </div>

    <div class="customizer-body">
        <div class="container">
            <!-- Sidebar -->
            <div class="col-12">
                <select id="group" class="filterField multiSelect" name="group" multiple placeholder="{{__('Cohort')}}">
                    @foreach($groups as $k => $val)
                        <option value="{{$k}}">{{$val}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <select id="program" class="filterField multiSelect" name="program" multiple placeholder="{{__('Program')}}">
                    @foreach($programs as $k => $val)
                        <option value="{{$k}}">{{$val}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <select id='course' class="filterField multiSelect" name="course" multiple placeholder="{{__('Course')}}">
                    @foreach($courses as $k => $val)
                        <option value="{{$k}}">{{$val}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 mt-1">
                <button id="apply-students-filter" type="button" class="btn btn-primary float-right">
                    {{__('Apply')}} <i class="fas fa-caret-right"></i>
                </button>
            </div>
        </div>
    </div>
</aside>
