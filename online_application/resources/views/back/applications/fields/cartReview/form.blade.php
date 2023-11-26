<form method="POST" action="{{ route($route , $field ) }}" class="validation-wizard wizard-circle">
    @csrf
    <div class="tab-pane p-20 active" id="general" role="tabpanel">
        @include('back.applications.fields.cartReview.general')
    </div>
</form>
