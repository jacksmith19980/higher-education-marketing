<form  method="POST" action="{{ $route }}" class="validation-wizard wizard-circle" id="ajax-form">



    @csrf

    <ul class="nav nav-tabs" role="tablist">

        <li class="nav-item">

        	<a class="nav-link active" data-toggle="tab" href="#general" role="tab">

        		<span class="hidden-xs-down">General</span>

        	</a>

        </li>

    </ul>



<!-- Tab panes -->

<div class="tab-content tabcontent-border">

   

     <div class="tab-pane p-20 active" id="general" role="tabpanel">

		 @include('back.applications.actions.'.$actionName.'.general')

    </div>





</div>



</form>