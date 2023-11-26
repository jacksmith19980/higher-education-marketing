<div class="d-flex justify-content-between align-items-center">
    <div>
        <h5 class="m-b-0 mb-0">
            {{$addon['title']}}
            @if (isset($addon['date']))
            <small class="d-block">{{$addon['date']}}</small>
            @endif
        </h5>
    </div>
    <div>
        <span class="badge badge-info">
            {{$addon['price']}}{{$settings['school']['default_currency']}}
            / {{$addon['price_type']}}
        </span>
        <span class="badge badge-success">{{ucwords($addon['category'])}}</span>

        <a href="javascript:void(0)" class="btn btn-light" onclick="app.deleteCourseProp(this)" data-delete-route="{{ route('date.addon.delete' , [
                                        'course'     => $course , 
                                        'date'       => $date,
                                        'addon_key'  => $addonKey
                                    ])}}">
            <i class="ti-trash"></i>
        </a>
        <a href="javascript:void(0)" class="btn btn-light" onclick="app.editCourseProp(this)"
            data-title="Edit - {{$addon['title']}}" data-route="{{ route('date.addon.edit' , [
                                        'course'     => $course , 
                                        'date'       => $date,
                                        'addon_key'  => $addonKey
                                    ])}}">
            <i class="ti-pencil"></i>
        </a>
    </div>
</div>