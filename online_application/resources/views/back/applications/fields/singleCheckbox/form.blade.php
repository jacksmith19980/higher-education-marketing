<form  method="POST" action="{{ route($route , $field ) }}" class="validation-wizard text_input_field">
    @csrf
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#general" role="tab"><span class="hidden-xs-down">General</span></a> </li>

        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#appearance" role="tab"><span class="hidden-xs-down">Appearance</span></a></li>

        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#data" role="tab"><span class="hidden-xs-down">Data </span></a> </li>

        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#validation" role="tab"><span class="hidden-xs-down">Validation</span></a> </li>

        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#intelligence" role="tab"><span class="hidden-xs-down">Intelligence</span></a> </li>

        {{--  <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#custom_script" role="tab"><span class="hidden-xs-down">Custom Script</span></a> </li>  --}}

    </ul>

<!-- Tab panes -->
<div class="tab-content tabcontent-border">

    <div class="tab-pane p-20 active" id="general" role="tabpanel">
            @include('back.applications.fields.checkbox.general')
    </div>

    <div class="tab-pane p-20" id="appearance" role="tabpanel">
            @include('back.applications._partials.create-fields.appearance')
    </div>

    <div class="tab-pane p-20" id="data" role="tabpanel">
            @include('back.applications.fields.singleCheckbox.data')
    </div>


    <div class="tab-pane p-20" id="validation" role="tabpanel">
            @include('back.applications._partials.create-fields.validation')
    </div>

    <div class="tab-pane p-20" id="intelligence" role="tabpanel">
            @include('back.applications._partials.create-fields.intelligence')
    </div>

   {{--   <div class="tab-pane p-20" id="custom_script" role="tabpanel">
            @include('back.applications.fields.singleCheckbox.custom-script')
    </div>  --}}

</div>

</form>
