<div class="col-md-12">
    <h6> {{__('Submission Statuses')}} - {{__('User has access to')}}</h6>
</div>
<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
    [
        'name'            => 'stage[view]',
        'label'         => false ,
        'class'         => 'permission-field' ,
        'required'      => false,
        'attr'          => 'data-permission=stage',
        'helper_text'   => 'View Submission statuses',
        'value'         => isset($role) ? $role->hasPermissionTo('view|stage') : 0,
        'default'       =>  1,
        'helper'        => 'View'
    ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
    [
        'name'            => 'stage[edit]',
        'label'         => false ,
        'class'         => 'permission-field' ,
        'required'      => false,
        'attr'          => 'data-permission=stage',
        'helper_text'   => 'Edit Submission statuses',
        'value'         => isset($role) ? $role->hasPermissionTo('edit|stage') : 0,
        'default'       => 1,
        'helper'        => 'Edit'
    ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
    [
        'name'            => 'stage[create]',
        'label'         => false ,
        'class'         => 'permission-field' ,
        'required'      => false,
        'attr'          => 'data-permission=stage',
        'helper_text'   => 'Create Submission statuses',
        'value'         => isset($role) ? $role->hasPermissionTo('create|stage') : 0,
        'default'       => 1,
        'helper'        => 'Create'
    ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
    [
        'name'          => 'stage[delete]',
        'label'         => false ,
        'class'         => 'permission-field' ,
        'required'      => false,
        'attr'          => 'data-permission=stage',
        'helper_text'   => 'Delete Submission statuses',
        'value'         => isset($role) ? $role->hasPermissionTo('delete|stage') : 0,
        'default'       =>  1,
        'helper'        => 'Delete'
    ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
    [
        'name'          => 'stage[full]',
        'label'         => false ,
        'class'         => '' ,
        'required'      => false,
        'attr'          => 'onchange=app.toggleFullpermissions(this) data-permission=stage',
        'helper_text'   => 'Full Permissions on Submission statuses',
        'value'         =>  isset($role) ? $role->hasPermissionTo('full|stage') : 0,
        'default'       =>  1,
        'helper'        => 'Full'
    ])
</div>
