@php
if($action == 'edit'){
    $event= "updateField('". route($route , $field )."')";
}elseif($action == 'create'){
    $event= "storeField('". route($route , $field ) ."')";
}
@endphp
<!-- Tab panes -->
<form  method="POST" @submit.prevent="{{$event}}"   class="text_input_field" id="fieldEdit" enctype="multipart/form-data">

    @yield('defaults')
    @csrf
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#general" role="tab"><span class="hidden-xs-down">{{__('General')}}</span></a> </li>

        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#data" role="tab"><span class="hidden-xs-down">{{__('Data')}}</span></a></li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#intelligence" role="tab">
                <span class="hidden-xs-down">
                    {{__('Intelligence')}}
                </span>
            </a>
        </li>

        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#custom-fields" role="tab"><span class="hidden-xs-down">{{__('Custom Fields')}}</span></a> </li>
    </ul>

<!-- Tab panes -->
<div class="tab-content tabcontent-border">

        <div class="tab-pane p-20 active" id="general" role="tabpanel">
            @include('back.applications.new-builder.fields.course.general')
        </div>

        <div class="tab-pane p-20" id="data" role="tabpanel">
            @include('back.applications.new-builder.fields.course.data')
        </div>
        <div class="tab-pane p-20" id="custom-fields" role="tabpanel">
            @include('back.applications.new-builder.fields.course.custom-fields')
        </div>

         <div class="tab-pane p-t-20" id="intelligence" role="tabpanel">
            @include('back.applications.new-builder.fields._partials.intelligence')
        </div>

    </div>
<div id="sideMenuFooter">
    <button class="btn btn-light" @click="closeEditField">{{__('Cancle')}}</button>
    <button type="submit" class="btn btn-success">{{__('Save')}}</button>
</div>
</form>
