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
                <select id='applications' class="filterField multiSelect" name="applications" multiple placeholder="{{__('Application Forms')}}">
                    @foreach($applications as $k => $val)
                        <option value="{{$k}}">{{__($val)}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <select id='status' class="filterField multiSelect" name="submission_status" multiple placeholder="{{__('Status of Applications')}}">
                    @foreach($statuses as $k => $val)
                        <option value="{{$k}}">{{__($val)}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <select id='progress' class="filterField multiSelect" name="progress" multiple placeholder="{{__('Progress of Applications')}}">
                    @foreach($progress as $k => $val)
                        <option value="{{$k}}">{{__($val)}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <select id='student_statuses' class="filterField multiSelect" name="contact_type" multiple placeholder="{{__('Contact Type')}}">
                    @foreach($student_statuses as $k => $val)
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
