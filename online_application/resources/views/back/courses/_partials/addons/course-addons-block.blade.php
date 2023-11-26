<li class="list-group-item" data-key="{{$addon->key}}">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h5 class="m-b-0 mb-0">{{$addon->title}}</h5>
        </div>
        <div>
            <span class="badge badge-info">
                {{$addon->price}}{{$settings['school']['default_currency']}}
                / {{$addon->price_type}}
            </span>
            <span class="badge badge-success">{{ucwords($addon->category)}}</span>

            <a href="javascript:void(0)" class="btn btn-light" onclick="app.deleteCourseProp(this)" data-delete-route="{{ route('addon.delete' , [
                                            'course' => $course , 
                                            'addon'  => $addon
                                        ])}}">
                <i class="ti-trash"></i>
            </a>
            <a href="javascript:void(0)" class="btn btn-light" onclick="app.editCourseProp(this)"
                data-title="Edit - {{$addon->title}}" 
                data-route="{{ route('addon.edit' , [
                                            'course' => $course , 
                                            'addon'  => $addon
                                        ])}}">
                <i class="ti-pencil"></i>
            </a>
        </div>
    </div>
</li>