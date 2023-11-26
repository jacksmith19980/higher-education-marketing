<form  method="POST" action="{{ route($route , $field ) }}" class="validation-wizard wizard-circle">
    @csrf
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#general" role="tab"><span class="hidden-xs-down">{{__('General')}}</span></a> </li>

        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#appearance" role="tab"><span class="hidden-xs-down">{{__('Appearance')}}</span></a></li>

        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#mapping" role="tab"><span class="hidden-xs-down">
                {{__('Mapping')}}
        </span></a></li>
        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#validation" role="tab"><span class="hidden-xs-down">Validation</span></a> </li>

        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#data" role="tab"><span class="hidden-xs-down">{{_('Data')}}</span></a> </li>


        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#intelligence" role="tab"><span class="hidden-xs-down">{{__('Intelligence')}}</span></a> </li>
    </ul>

<!-- Tab panes -->
<div class="tab-content tabcontent-border">

        <div class="tab-pane p-t-20 active" id="general" role="tabpanel">
                @include('back.applications._partials.create-fields.general')
        </div>

        <div class="tab-pane p-t-20" id="data" role="tabpanel">
                @include('back.applications.fields.list.data')
        </div>

        <div class="tab-pane p-t-20" id="appearance" role="tabpanel">
                @include('back.applications._partials.create-fields.appearance')
        </div>
        <div class="tab-pane p-t-20" id="mapping" role="tabpanel">
                @include('back.applications._partials.create-fields.mapping')
        </div>

        <div class="tab-pane p-t-20" id="validation" role="tabpanel">
                @include('back.applications._partials.create-fields.validation')
        </div>

        <div class="tab-pane p-t-20" id="intelligence" role="tabpanel">
                @include('back.applications._partials.create-fields.intelligence')
        </div>

</div>

</form>
