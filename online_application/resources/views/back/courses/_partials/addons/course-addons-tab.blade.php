<div class="tab-pane fade show" id="addons" role="tabpanel" aria-labelledby="pills-addons-tab">
    <div class="card-body">
       @if (isset($course->addons))
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-success mt-2 right pull-right float-right" data-template="false"
                    data-route="{{route('addon.create' , $course)}}" data-title="Add Course's Addon"
                    onclick="app.addCourseAddon(this)">
                    Add Addon
                </button>
                <div class="clear"></div>
            </div>
            <div class="col-md-12 mt-3">
                <ul class="list-group list-group-full addons-blocks">
                    @foreach ($course->addons as $key => $addon)
                        {{--  <li class="list-group-item" data-key="{{$key}}">  --}}
                            @include('back.courses._partials.addons.course-addons-block' , ['addon' => $addon])
                        {{--  </li>  --}}
                    @endforeach
                </ul>
            </div>
        </div>
        
        @else
        <div class="alert alert-warning">
            <strong>{{__('No Addons Found')}}</strong>
            <span class="d-block">{{__("there are none! You didn't add any addons for this course yet!")}}</span>
            <button class="btn btn-success mt-2 right pull-right float-right" data-template="false"
                data-route="{{route('addon.create' , $course)}}" data-title="Add Course's Addon"
                onclick="app.addCourseAddon(this)">
                Add Addon
            </button>
            <div class="clear"></div>
        </div>
        @endif


    </div>
</div>