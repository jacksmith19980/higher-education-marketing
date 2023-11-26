<form method="POST" action="{{ route($route , $submission) }}" class="validation-wizard text_input_field">
    @csrf
    <input type="hidden" name="_method" value="PUT">

    <div class="accordion-head bg-info text-white">{{__('Update')}} {{$submission->application->title}} {{__('Status')}}</div>

    <div class="accordion-content accordion-active">

        <div class="row">
            <div class="col-md-12">
                @include('back.layouts.core.forms.select',
                [
                    'name'          => 'status',
                    'label'         => 'Status',
                    'class'         => 'ajax-form-field' ,
                    'required'      => true,
                    'attr'          => '',
                    'data'          => $application_status,
                    'value'         => $submission->statusLast()
                ])

            </div>
        </div>
    </div>

</form>
