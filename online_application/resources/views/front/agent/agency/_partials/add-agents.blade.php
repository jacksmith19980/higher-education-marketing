<h6>{!! __('Invite Agents') !!}</h6>
<section>
<div class="alert alert-info">
    {{__('You can invite more agents to join your agency and give them the ability to submit students data')}}
</div>

<div class="row repeated_fields" id="agents_information">
    <div class="col-md-5">
        <div class="form-group">
            <input type="text" class="form-control form-control-lg" id="agents_names" name="agents_names[]" placeholder="{{__('Name')}}">
        </div>
    </div>
    
    <div class="col-md-5">
        <div class="form-group">
            <input type="email" class="form-control form-control-lg" id="agents_emails" name="agents_emails[]" placeholder="{{__('Email')}}">
        </div>
    </div>
    <div class="col-md-1 action_wrapper">
        <div class="form-group action_button">
            <button class="btn waves-effect waves-light btn-outline-success btn-lg" type="button" onclick="app.repeat_fields()"><i class="fa fa-plus"></i></button>
        </div>
    </div>
</div>
<div class="repeated_fields_wrapper" data-parent="agents_information"></div>
</section>