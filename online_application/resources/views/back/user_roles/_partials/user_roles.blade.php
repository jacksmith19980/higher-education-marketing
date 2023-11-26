<form method="POST" action="{{ route($route, [$school, $user]) }}" class="validation-wizard text_input_field">
    @csrf
    <input type="hidden" name="_method" value="PUT">

    <div class="accordion-head bg-info text-white">{{__('Update')}} {{$user->name}} {{__('Roles')}}</div>

    <div class="accordion-content accordion-active">

        <div class="row">
            <div class="col-md-12">
                @include('back.layouts.core.forms.multi-select',
                [
                'name' => 'roles[]',
                'label' => 'Roles',
                'class' => 'ajax-form-field' ,
                'required' => true,
                'attr' => '',
                'data' => $roles,
                'value' => $roles_owned
                ])

            </div>
        </div>
    </div>

</form>
