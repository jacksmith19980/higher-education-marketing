<div class="col-md-12">
  <h6> {{__('Users')}} - {{__('User has access to')}}</h6>
</div>
<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'user[view]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'View User',
       'value'         => isset($role) ? $role->hasPermissionTo('view|user') : 0,
       'default'       =>  1,
       'helper'        => 'View'
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'user[edit]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Edit User',
       'value'         => isset($role) ? $role->hasPermissionTo('edit|user') : 0,
       'default'       =>  1,
       'helper'        => 'Edit'
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'user[create]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Create User',
       'value'         => isset($role) ? $role->hasPermissionTo('create|user') : 0,
       'default'       =>  1,
       'helper'        => 'Create'
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'user[delete]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Delete User',
       'value'         => isset($role) ? $role->hasPermissionTo('delete|user') : 0,
       'default'       =>  1,
       'helper'        => 'Delete'
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'user[full]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Full Permissions on User',
       'value'         =>  0,
       'default'       =>  1,
       'helper'        => 'Full'
   ])
</div>

<div class="col-md-12">
  <h6> {{__('Users')}} - {{__('Through Campuses')}}</h6>
</div>
<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'user[campusesView]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'View users from other campuses',
       'value'         =>  isset($role) ? $role->hasPermissionTo('campusesView|user') : 0,
       'default'       =>  1,
       'helper'        => ''
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'user[campusesEdit]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Edit users from other campuses',
       'value'         =>  isset($role) ? $role->hasPermissionTo('campusesEdit|user') : 0,
       'default'       =>  1,
       'helper'        => ''
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'user[campusesCreate]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Create users for other campuses',
       'value'         =>  isset($role) ? $role->hasPermissionTo('campusesCreate|user') : 0,
       'default'       =>  1,
       'helper'        => ''
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'user[campusesDelete]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Delete users from other campuses',
       'value'         =>  isset($role) ? $role->hasPermissionTo('campusesDelete|user') : 0,
       'default'       =>  1,
       'helper'        => ''
   ])
</div>
<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'user[campusesFull]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Full permissions on users from other campuses',
       'value'         =>  isset($role) ? $role->hasPermissionTo('campusesFull|user') : 0,
       'default'       =>  1,
       'helper'        => ''
   ])
</div>
