<div class="m-0 card">
    <div class="py-0 pt-2 pl-0 pr-1 ">

        <div class="mb-0 d-flex justify-content-between btn-toggler align-items-center" data-toggle="collapse"
        data-target="#{{$object}}Props" aria-expanded="false" aria-controls="app_pInfo">

        <h5>{{__($title)}}</h5>
        <i class="mdi mdi-plus text-primary"></i>
        </div>

    </div>

    <div id="{{$object}}Props" class="collapse" aria-labelledby="apph_pInfo" data-parent="#{{$object}}Props" style="">
        <div class="p-0 card-body">
            <template x-if="customFields.{{$object}}">
                <template x-for="customfield in customFields.{{$object}} ">
                    <div
                    class="custom-prop"
                    :draggable="true"
                    x-on:dragstart.self="startFieldDragging(customfield.id,true)"
                    x-on:dragend.self="endFieldDragging(customfield.id, true)"
                    >
                        <div class="custom-prop-name" x-text="customfield.name"></div>
                        <div class="custom-prop-type" x-text="customfield.field_type"></div>
                    </div>
                </template>
            </template>
            <template x-if="!customFields.{{$object}}">
                <div class="alert alert-warning">
                    {{__('No Custom Fields yet')}}, <a href="{{route('customfields.create')}}">{{__('Click Here')}}</a> {{__('to create a new custom field')}}
                </div>
            </template>
        </div>
    </div>
</div>
