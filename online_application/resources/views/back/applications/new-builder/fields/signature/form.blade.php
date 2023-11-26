@php
if($action == 'edit'){
        $event= "updateField('". route($route , $field )."')";
}elseif($action == 'create'){
        $event= "storeField('". route($route , $field ) ."')";
}
@endphp

<!-- Tab panes -->
<form  method="POST" @submit.prevent="{{$event}}"   class="text_input_field" id="fieldEdit" enctype="multipart/form-data">
        @csrf
        @yield('defaults')
        <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#general" role="tab"><span class="hidden-xs-down">
                        {{__('General')}}
                </span></a> </li>

                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#appearance" role="tab"><span class="hidden-xs-down">
                        {{__('Appearance')}}
                </span></a></li>

                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#validation" role="tab"><span class="hidden-xs-down">
                        Validation
                </span></a></li>

                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#intelligence" role="tab"><span class="hidden-xs-down">
                        {{__('Intelligence')}}
                </span></a>
                </li>
        </ul>
<!-- Tab panes -->
<div class="tab-content tabcontent-border">
        <div class="tab-pane p-t-20 active" id="general" role="tabpanel">
                @include('back.applications.new-builder.fields.signature.general')
        </div>

        <div class="tab-pane p-t-20" id="appearance" role="tabpanel">
                @include('back.applications.new-builder.fields.signature.appearance')
        </div>

        <div class="tab-pane p-t-20" id="validation" role="tabpanel">
                @include('back.applications.new-builder.fields._partials.validation')
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
