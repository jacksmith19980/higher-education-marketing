<div class="col-md-12">
  <h6> {{__('Applications')}} - {{__('User has access to')}}</h6>
</div>
<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'application[view]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'View Application',
       'value'         => isset($role) ? $role->hasPermissionTo('view|application') : 0,
       'default'       =>  1,
       'helper'        => 'View'
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'application[edit]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Edit Application',
       'value'         => isset($role) ? $role->hasPermissionTo('edit|application') : 0,
       'default'       =>  1,
       'helper'        => 'Edit'
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'application[create]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Create Application',
       'value'         => isset($role) ? $role->hasPermissionTo('create|application') : 0,
       'default'       =>  1,
       'helper'        => 'Create'
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'application[delete]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Delete Application',
       'value'         => isset($role) ? $role->hasPermissionTo('delete|application') : 0,
       'default'       =>  1,
       'helper'        => 'Delete'
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'application[full]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Full Permissions on Application',
       'value'         =>  0,
       'default'       =>  1,
       'helper'        => 'Full'
   ])
</div>

<div class="col-md-12">
  <h6> {{__('Applications')}} - {{__('Through Campuses')}}</h6>
</div>
<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'application[campusesView]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'View applications from other campuses',
       'value'         =>  isset($role) ? $role->hasPermissionTo('campusesView|application') : 0,
       'default'       =>  1,
       'helper'        => ''
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'application[campusesEdit]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Edit applications from other campuses',
       'value'         =>  isset($role) ? $role->hasPermissionTo('campusesEdit|application') : 0,
       'default'       =>  1,
       'helper'        => ''
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'application[campusesCreate]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Create applications for other campuses',
       'value'         =>  isset($role) ? $role->hasPermissionTo('campusesCreate|application') : 0,
       'default'       =>  1,
       'helper'        => ''
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'application[campusesDelete]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Delete applications from other campuses',
       'value'         =>  isset($role) ? $role->hasPermissionTo('campusesDelete|application') : 0,
       'default'       =>  1,
       'helper'        => ''
   ])
</div>
<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'application[campusesFull]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Full permissions on applications from other campuses',
       'value'         =>  isset($role) ? $role->hasPermissionTo('campusesFull|application') : 0,
       'default'       =>  1,
       'helper'        => ''
   ])
</div>
