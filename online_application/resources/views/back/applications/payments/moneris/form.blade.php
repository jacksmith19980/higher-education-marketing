<form method="POST" action="{{ $route }}" class="validation-wizard wizard-circle" id="ajax-form">
    @csrf
    <ul class="nav nav-tabs" role="tablist">

        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#general" role="tab">
                <span class="hidden-xs-down">{{__('General')}}</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#products" role="tab">
                <span class="hidden-xs-down">{{__('Products')}}</span>
            </a>
        </li>
    </ul>



    <!-- Tab panes -->

    <div class="tab-content tabcontent-border">



        <div class="tab-pane p-20 active" id="general" role="tabpanel">
            @include('back.applications.payments.'.$gateway.'.general')
        </div>
        <div class="tab-pane p-20" id="products" role="tabpanel">
            @include('back.applications.payments.'.$gateway.'.products')
        </div>



    </div>



</form>
