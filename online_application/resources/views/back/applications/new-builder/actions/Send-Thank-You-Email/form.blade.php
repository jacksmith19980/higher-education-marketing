<h4 class="mb-3">{{__('Add Application Action')}} ({{__($title)}})</h4>
<form  @submit.prevent="storeApplicationAction('{{$route}}' , '{{$method}}')"   class="text_input_field" id="fieldEdit" enctype="multipart/form-data">
    @csrf
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#general" role="tab">
                <span class="hidden-xs-down">{{__('General')}}</span>
            </a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content tabcontent-border">
        <div class="tab-pane p-20 active" id="general" role="tabpanel">
            @include('back.applications.new-builder.actions.'.$actionName.'.general')
        </div>
    </div>
    <div id="sideMenuFooter">
        <div style="text-align:left">
            @if ($action && $application )
                <x-builder.delete
                action="{{route('application.actions.destroy', [
                    'action'        => $action,
                    'application'   => $application
                ] )}}"
                item="application-action"
                buttonText="{{__('Delete')}}"></x-builder.delete>

            @endif
        </div>
        <div style="text-align:rigth; width:50%;">
            <button class="btn btn-light" @click="closeEditField">{{__('Cancle')}}</button>

            @if ($action)
                <button type="submit" class="btn btn-success">{{__('Update')}}</button>

            @else
                <button type="submit" class="btn btn-success">{{__('Save')}}</button>
            @endif
        </div>
    </div>
</form>
