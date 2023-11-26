<div class="row repeated_fields" id="agents_information">
    <div class="col-md-6">
        <div class="form-group">
            <input type="text" class="form-control form-control-lg" id="agents_names" name="agents_names[]"
                placeholder="Name">
        </div>
    </div>

    <div class="col-md-5">
        <div class="form-group">
            <input type="email" class="form-control form-control-lg" id="agents_emails" name="agents_emails[]"
                placeholder="Email">
        </div>
    </div>
    <div class="col-md-1 action_wrapper">
        <div class="form-group action_button">
            <button class="btn waves-effect waves-light btn-outline-success float-right btn-lg ml-2 mb-3" type="button"
                onclick="app.repeat_fields()"><i class="fa fa-plus"></i></button>
        </div>
    </div>
</div>

<div class="repeated_fields_wrapper" data-parent="agents_information"></div>
