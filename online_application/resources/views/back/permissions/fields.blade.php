<div class="col-md-12">
    <h6> {{__('Fields')}} - {{__('User has access to')}}</h6>
</div>
<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
    [
        'name'            => 'field[view]',
        'label'         => false ,
        'class'         => 'permission-field' ,
        'required'      => false,
        'attr'          => 'data-permission=field',
        'helper_text'   => 'View fields',
        'value'         => isset($role) ? $role->hasPermissionTo('view|field') : 0,
        'default'       =>  1,
        'helper'        => 'View'
    ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
    [
        'name'            => 'field[edit]',
        'label'         => false ,
        'class'         => 'permission-field' ,
        'required'      => false,
        'attr'          => 'data-permission=field',
        'helper_text'   => 'Edit fields',
        'value'         => isset($role) ? $role->hasPermissionTo('edit|field') : 0,
        'default'       => 1,
        'helper'        => 'Edit'
    ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
    [
        'name'            => 'field[create]',
        'label'         => false ,
        'class'         => 'permission-field' ,
        'required'      => false,
        'attr'          => 'data-permission=field',
        'helper_text'   => 'Create fields',
        'value'         => isset($role) ? $role->hasPermissionTo('create|field') : 0,
        'default'       => 1,
        'helper'        => 'Create'
    ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
    [
        'name'          => 'field[delete]',
        'label'         => false ,
        'class'         => 'permission-field' ,
        'required'      => false,
        'attr'          => 'data-permission=field',
        'helper_text'   => 'Delete fields',
        'value'         => isset($role) ? $role->hasPermissionTo('delete|field') : 0,
        'default'       =>  1,
        'helper'        => 'Delete'
    ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
    [
        'name'          => 'field[full]',
        'label'         => false ,
        'class'         => '' ,
        'required'      => false,
        'attr'          => 'onchange=app.toggleFullpermissions(this) data-permission=field',
        'helper_text'   => 'Full Permissions on fields',
        'value'         =>  isset($role) ? $role->hasPermissionTo('full|field') : 0,
        'default'       =>  1,
        'helper'        => 'Full'
    ])
</div>
