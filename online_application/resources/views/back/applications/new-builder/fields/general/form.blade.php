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
                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#general" role="tab"><span class="hidden-xs-down">
                        {{__('General')}}
                </span></a> </li>

                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#appearance" role="tab"><span class="hidden-xs-down">
                        {{__('Appearance')}}
                </span></a></li>

                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#mapping" role="tab"><span class="hidden-xs-down">
                        {{__('Mapping')}}
                </span></a></li>

                @if(in_array($type , ['list']))
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#data" role="tab"><span class="hidden-xs-down">
                        {{__('Data')}}
                </span></a></li>
                @endif

                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#validation" role="tab"><span class="hidden-xs-down">
                        Validation
                </span></a> </li>

                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#intelligence" role="tab"><span class="hidden-xs-down">
                        {{__('Intelligence')}}
                </span></a>
                </li>
        </ul>

<!-- Tab panes -->

<div class="tab-content tabcontent-border">

        <div class="tab-pane p-t-20 active" id="general" role="tabpanel">
                @include('back.applications.new-builder.fields._partials.general')
        </div>

        <div class="tab-pane p-t-20" id="appearance" role="tabpanel">
                @include('back.applications.new-builder.fields._partials.appearance')
        </div>

        <div class="tab-pane p-t-20" id="mapping" role="tabpanel">
                @include('back.applications.new-builder.fields._partials.mapping')
        </div>

        @if(in_array($type , ['list']))
        <div class="tab-pane p-t-20" id="data" role="tabpanel">
                @include('back.applications.new-builder.fields._partials.data')
        </div>

        @endif

        <div class="tab-pane p-t-20" id="validation" role="tabpanel">
                @include('back.applications.new-builder.fields._partials.validation')
        </div>

        <div class="tab-pane p-t-20" id="intelligence" role="tabpanel">
                @include('back.applications.new-builder.fields._partials.intelligence')
        </div>

</div>

        <div id="sideMenuFooter">
        <div style="text-align:left">
                @if ($field )
                        <x-builder.delete
                        action="{{route('fields.destroy', [
                        'field'        => $field,
                        'section'      => $field->section->id
                        ] )}}"
                        item="application-field"
                        buttonText="{{__('Delete')}}"></x-builder.delete>

                @endif
                </div>
                <div style="text-align:rigth; width:50%;">
                <button class="btn btn-light" @click="closeEditField">{{__('Cancle')}}</button>

                <button type="submit" class="btn btn-success">{{ __('Save')}}</button>
                </div>
    </div>


</form>
