
<div class="col-md-12">
    <h6> {{__('E-Signatures')}} - {{__('User has access to')}}</h6>
</div>
<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
    [
        'name'          => 'envelope[view]',
        'label'         => false ,
        'class'         => 'permission-field' ,
        'required'      => false,
        'attr'          => 'data-permission=envelope',
        'helper_text'   => 'View E-Signatures',
        'value'         => isset($role) ? $role->hasPermissionTo('view|envelope') : 0,
        'default'       =>  1,
        'helper'        => 'View'
    ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
    [
        'name'          => 'envelope[edit]',
        'label'         => false ,
        'class'         => 'permission-field' ,
        'required'      => false,
        'attr'          => 'data-permission=envelope',
        'helper_text'   => 'Edit E-Signatures',
        'value'         => isset($role) ? $role->hasPermissionTo('edit|envelope') : 0,
        'default'       => 1,
        'helper'        => 'Edit'
    ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
    [
        'name'            => 'envelope[create]',
        'label'         => false ,
        'class'         => 'permission-field' ,
        'required'      => false,
        'attr'          => 'data-permission=envelope',
        'helper_text'   => 'Create E-Signatures',
        'value'         => isset($role) ? $role->hasPermissionTo('create|envelope') : 0,
        'default'       => 1,
        'helper'        => 'Create'
    ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
    [
        'name'            => 'envelope[delete]',
        'label'         => false ,
        'class'         => 'permission-field' ,
        'required'      => false,
        'attr'          => 'data-permission=envelope',
        'helper_text'   => 'Delete E-Signatures',
        'value'         => isset($role) ? $role->hasPermissionTo('delete|envelope') : 0,
        'default'       =>  1,
        'helper'        => 'Delete'
    ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
    [
        'name'            => 'envelope[full]',
        'label'         => false ,
        'class'         => '' ,
        'required'      => false,
        'attr'          => 'onchange=app.toggleFullpermissions(this) data-permission=envelope',
        'helper_text'   => 'Full Permissions on E-Signatures',
        'value'         =>  0,
        'default'       =>  1,
        'helper'        => 'Full'
    ])
</div>
<div class="col-md-12">
<h6> {{__('E-Signatures')}} - {{__('Through Campuses')}}</h6>
</div>
<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
    [
        'name'            => 'envelope[campusesView]',
        'label'         => false ,
        'class'         => 'permission-field' ,
        'required'      => false,
        'attr'          => 'data-permission=envelopeCampuses',
        'helper_text'   => 'View E-Signatures from other campuses',
        'value'         =>  isset($role) ? $role->hasPermissionTo('campusesView|envelope') : 0,
        'default'       =>  1,
        'helper'        => ''
    ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
    [
        'name'            => 'envelope[campusesEdit]',
        'label'         => false ,
        'class'         => 'permission-field' ,
        'required'      => false,
        'attr'          => 'data-permission=envelopeCampuses',
        'helper_text'   => 'Edit E-Signatures from other campuses',
        'value'         =>  isset($role) ? $role->hasPermissionTo('campusesEdit|envelope') : 0,
        'default'       =>  1,
        'helper'        => ''
    ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
    [
        'name'            => 'envelope[campusesCreate]',
        'label'         => false ,
        'class'         => 'permission-field' ,
        'required'      => false,
        'attr'          => 'data-permission=envelopeCampuses',
        'helper_text'   => 'Create E-Signatures for other campuses',
        'value'         =>  isset($role) ? $role->hasPermissionTo('campusesCreate|envelope') : 0,
        'default'       =>  1,
        'helper'        => ''
    ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
    [
        'name'            => 'envelope[campusesDelete]',
        'label'         => false ,
        'class'         => 'permission-field' ,
        'required'      => false,
        'attr'          => 'data-permission=envelopeCampuses',
        'helper_text'   => 'Delete E-Signatures from other campuses',
        'value'         =>  isset($role) ? $role->hasPermissionTo('campusesDelete|envelope') : 0,
        'default'       =>  1,
        'helper'        => ''
    ])
</div>
<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
    [
        'name'            => 'envelope[campusesFull]',
        'label'         => false ,
        'class'         => '' ,
        'required'      => false,
        'attr'          => 'onchange=app.toggleFullpermissions(this) data-permission=envelopeCampuses',
        'helper_text'   => 'Full permissions on E-Signatures from other campuses',
        'value'         =>  isset($role) ? $role->hasPermissionTo('campusesFull|envelope') : 0,
        'default'       =>  1,
        'helper'        => ''
    ])
</div>
