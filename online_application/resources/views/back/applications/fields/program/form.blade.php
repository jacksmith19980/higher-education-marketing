<form  method="POST" action="{{ route($route , $field ) }}" class="validation-wizard wizard-circle">
    @csrf
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#general" role="tab"><span class="hidden-xs-down">{{__('General')}}</span></a> </li>

        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#appearance" role="tab"><span class="hidden-xs-down">{{__('Appearance')}}</span></a></li>

        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#data" role="tab"><span class="hidden-xs-down">{{__('Data')}}</span></a></li>

        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#custom-fields" role="tab"><span class="hidden-xs-down">{{__('Custom Fields')}}</span></a> </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content tabcontent-border">

        <div class="tab-pane p-20 active" id="general" role="tabpanel">
            @include('back.applications.fields.program.general')
        </div>

        <div class="tab-pane p-20" id="appearance" role="tabpanel">
            @include('back.applications._partials.create-fields.appearance')
        </div>

        <div class="tab-pane p-20" id="data" role="tabpanel">
            @include('back.applications.fields.program.data')
        </div>
        <div class="tab-pane p-20" id="custom-fields" role="tabpanel">
            @include('back.applications.fields.program.custom-fields')
        </div>

    </div>

</form>
