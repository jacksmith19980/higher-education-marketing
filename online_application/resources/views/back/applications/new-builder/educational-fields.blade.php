<div class="m-0 card">
    <div class="py-0 pt-2 pl-0 pr-1 ">
        <div class="mb-0 d-flex justify-content-between btn-toggler align-items-center" data-toggle="collapse"
        data-target="#NewProps" aria-expanded="false" aria-controls="app_pInfo">
        <h5>{{__("Educational Properties")}}</h5>
        <i class="mdi mdi-plus text-primary"></i>
        </div>
    </div>
    <div id="NewProps" class="collapse" aria-labelledby="apph_pInfo" data-parent="#accordionExample" style="">
        <div class="p-0 card-body">
            @foreach (ApplicationHelpers::getEducationFieldsType(true) as $slug => $fieldType)
                <div class="custom-prop"
                :draggable="true"
                x-on:dragstart.self="startFieldDragging('{{$slug}}',true)"
                x-on:dragend.self="endFieldDragging('{{$slug}}', true)"
                >
                    <span>
                        <i class="{{$fieldType['icon']}} mr-2 text-mute"></i> {{$fieldType['title']}}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</div>
