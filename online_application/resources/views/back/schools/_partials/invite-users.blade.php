<div data-name="settings_invite_user_form" class="row repeated_fields" id="users_information">
    <div class="col-md-5">
        <div class="form-group">
            <input type="text" class="form-control form-control-lg" id="users_names" name="users_names[]"
                placeholder="Name">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <input type="email" class="form-control form-control-lg" id="users_emails" name="users_emails[]"
                placeholder="Email">
        </div>
    </div>
    <input type="hidden" name="users_roles[]" value="0" class="">


   {{--   <div class="col-md-3" onmouseenter="" ontouchstart="">
        <div class="form-group">

            <select class="form-control custom-select form-control-lg" name="users_roles[]" id="users_roles" readOnly>
                @foreach ($roles as $k=>$v)
                <option value="{{$k}}">{{__($v)}}</option>
                @endforeach
            </select>
        </div>
    </div>  --}}

    <div class="col-md-1 action_wrapper">
        <div class="form-group action_button">
            <button class="btn waves-effect waves-light btn-outline-success btn-lg" type="button"
                onclick="app.repeat_fields()"><i class="fa fa-plus"></i></button>
        </div>
    </div>
</div>

<div class="repeated_fields_wrapper" data-parent="users_information"></div>
